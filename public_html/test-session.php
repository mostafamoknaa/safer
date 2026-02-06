<!DOCTYPE html>
<html>
<head>
    <title>Session Configuration Check</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { background: white; padding: 20px; border-radius: 8px; max-width: 900px; margin: 0 auto; }
        h1 { color: #333; }
        pre { background: #f9f9f9; padding: 15px; border-radius: 4px; overflow-x: auto; }
        .success { color: #22c55e; }
        .error { color: #ef4444; }
        .warning { color: #f59e0b; }
        .section { margin: 20px 0; padding: 15px; border: 1px solid #e5e7eb; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Session Configuration Check</h1>
        
        <?php
        // Load Laravel
        require __DIR__.'/../vendor/autoload.php';
        $app = require_once __DIR__.'/../bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
        $response = $kernel->handle(
            $request = Illuminate\Http\Request::capture()
        );
        
        echo '<div class="section">';
        echo '<h2>Environment Variables (.env)</h2>';
        echo '<pre>';
        echo "SESSION_DRIVER: " . env('SESSION_DRIVER', 'NOT SET') . "\n";
        echo "SESSION_LIFETIME: " . env('SESSION_LIFETIME', 'NOT SET') . "\n";
        echo "SESSION_DOMAIN: " . env('SESSION_DOMAIN', 'NOT SET') . "\n";
        echo "SESSION_SECURE_COOKIE: " . env('SESSION_SECURE_COOKIE', 'NOT SET') . "\n";
        echo "SESSION_HTTP_ONLY: " . env('SESSION_HTTP_ONLY', 'NOT SET') . "\n";
        echo "SESSION_SAME_SITE: " . env('SESSION_SAME_SITE', 'NOT SET') . "\n";
        echo "APP_URL: " . env('APP_URL', 'NOT SET') . "\n";
        echo '</pre>';
        echo '</div>';
        
        echo '<div class="section">';
        echo '<h2>Config Values (Actual - After Processing)</h2>';
        echo '<pre>';
        echo "session.driver: " . config('session.driver') . "\n";
        echo "session.lifetime: " . config('session.lifetime') . "\n";
        echo "session.domain: " . (config('session.domain') ?: 'null') . "\n";
        echo "session.secure: " . (config('session.secure') ? 'true' : 'false') . "\n";
        echo "session.http_only: " . (config('session.http_only') ? 'true' : 'false') . "\n";
        echo "session.same_site: " . config('session.same_site') . "\n";
        echo "session.cookie: " . config('session.cookie') . "\n";
        echo '</pre>';
        echo '</div>';
        
        echo '<div class="section">';
        echo '<h2>Server Information</h2>';
        echo '<pre>';
        echo "Server Name: " . $_SERVER['SERVER_NAME'] . "\n";
        echo "HTTP Host: " . $_SERVER['HTTP_HOST'] . "\n";
        echo "HTTPS: " . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'ON' : 'OFF') . "\n";
        echo "Request URI: " . $_SERVER['REQUEST_URI'] . "\n";
        echo "PHP Version: " . PHP_VERSION . "\n";
        echo '</pre>';
        echo '</div>';
        
        echo '<div class="section">';
        echo '<h2>Database - Sessions Table</h2>';
        echo '<pre>';
        try {
            $pdo = DB::connection()->getPdo();
            echo "<span class='success'>✓ Database Connected</span>\n\n";
            
            $tables = DB::select("SHOW TABLES LIKE 'sessions'");
            if (count($tables) > 0) {
                echo "<span class='success'>✓ Sessions Table Exists</span>\n\n";
                
                $count = DB::table('sessions')->count();
                echo "Active Sessions: " . $count . "\n";
                
                if ($count > 0) {
                    echo "\nRecent Sessions:\n";
                    $recent = DB::table('sessions')
                        ->orderBy('last_activity', 'desc')
                        ->limit(5)
                        ->get(['id', 'user_id', 'ip_address', 'last_activity']);
                    
                    foreach ($recent as $session) {
                        echo "- User ID: " . ($session->user_id ?: 'Guest') . 
                             " | IP: " . $session->ip_address . 
                             " | Last: " . date('Y-m-d H:i:s', $session->last_activity) . "\n";
                    }
                }
            } else {
                echo "<span class='error'>✗ Sessions Table NOT FOUND</span>\n";
            }
        } catch (Exception $e) {
            echo "<span class='error'>✗ Database Error: " . $e->getMessage() . "</span>\n";
        }
        echo '</pre>';
        echo '</div>';
        
        echo '<div class="section">';
        echo '<h2>Analysis & Recommendations</h2>';
        echo '<pre>';
        
        $issues = [];
        
        // Check driver
        if (config('session.driver') !== 'database') {
            $issues[] = "<span class='warning'>⚠ SESSION_DRIVER should be 'database', currently: " . config('session.driver') . "</span>";
        } else {
            echo "<span class='success'>✓ SESSION_DRIVER is correct (database)</span>\n";
        }
        
        // Check domain
        if (config('session.domain')) {
            if (strpos(config('session.domain'), '.') === 0) {
                $issues[] = "<span class='warning'>⚠ SESSION_DOMAIN starts with dot (.), this may cause issues</span>";
            } else {
                echo "<span class='success'>✓ SESSION_DOMAIN is set: " . config('session.domain') . "</span>\n";
            }
        } else {
            echo "<span class='success'>✓ SESSION_DOMAIN is null (auto-detect)</span>\n";
        }
        
        // Check secure
        $isHttps = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
        if (config('session.secure') && !$isHttps) {
            $issues[] = "<span class='error'>✗ SESSION_SECURE_COOKIE is true but site is on HTTP!</span>";
        } else if (!config('session.secure') && $isHttps) {
            $issues[] = "<span class='warning'>⚠ Site is HTTPS but SESSION_SECURE_COOKIE is false (not secure)</span>";
        } else {
            echo "<span class='success'>✓ SESSION_SECURE_COOKIE matches HTTPS status</span>\n";
        }
        
        // Check sessions table
        try {
            $tables = DB::select("SHOW TABLES LIKE 'sessions'");
            if (count($tables) > 0) {
                echo "<span class='success'>✓ Sessions table exists in database</span>\n";
            } else {
                $issues[] = "<span class='error'>✗ Sessions table NOT FOUND in database</span>";
            }
        } catch (Exception $e) {
            $issues[] = "<span class='error'>✗ Cannot connect to database</span>";
        }
        
        if (!empty($issues)) {
            echo "\n<strong>Issues Found:</strong>\n";
            foreach ($issues as $issue) {
                echo $issue . "\n";
            }
        } else {
            echo "\n<span class='success'><strong>✓ All checks passed!</strong></span>\n";
        }
        
        echo '</pre>';
        echo '</div>';
        
        echo '<div class="section">';
        echo '<h2>Next Steps</h2>';
        echo '<ul>';
        echo '<li>If all checks passed, try logging in at: <a href="/login">/login</a></li>';
        echo '<li>If issues found, fix them in .env file and run: <code>php artisan config:clear</code></li>';
        echo '<li>Check browser cookies in Developer Tools (F12) > Application > Cookies</li>';
        echo '</ul>';
        echo '</div>';
        ?>
    </div>
</body>
</html>
