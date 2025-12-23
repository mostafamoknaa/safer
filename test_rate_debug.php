<?php

use App\Models\Hotel;
use Illuminate\Support\Facades\Log;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $hotel = Hotel::find(5);
    if (!$hotel) {
        // Fallback to first hotel if 5 doesn't exist
        $hotel = Hotel::first();
    }
    
    if ($hotel) {
        echo "Hotel ID: " . $hotel->id . "\n";
        echo "Old Rate: " . $hotel->rate . "\n";
        
        $hotel->update(['rate' => 4]);
        
        echo "Updated Rate (from instance): " . $hotel->rate . "\n";
        
        $hotel->refresh();
        echo "Refreshed Rate (from DB): " . $hotel->rate . "\n";
    } else {
        echo "No hotels found.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
