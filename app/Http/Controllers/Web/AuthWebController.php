<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class AuthWebController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        // إذا مسجل دخول، ارجعه للهوم
        if (Auth::check()) {
            return redirect()->route('web.home');
        }
        
        return view('web.auth.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            // دايماً فاكر المستخدم (أو استخدم الـ checkbox)
            $remember = $request->boolean('remember', true);

            if (Auth::attempt($credentials, $remember)) {
                $request->session()->regenerate();
                
                // Force session save before sending response to ensure cookie is set
                $request->session()->save();
                
                Log::info('User logged in', [
                    'user_id' => Auth::id(),
                    'email' => Auth::user()->email
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'تم تسجيل الدخول بنجاح',
                    'redirect' => route('web.home')
                ]);
            }

            throw ValidationException::withMessages([
                'email' => ['البريد الإلكتروني أو كلمة المرور غير صحيحة'],
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تسجيل الدخول'
            ], 500);
        }
    }

    /**
     * Show the registration form.
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('web.home');
        }
        
        return view('web.auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ], [
                'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
                'password.confirmed' => 'كلمة المرور غير متطابقة',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'is_active' => true,
            ]);

            // تسجيل دخول تلقائي مع التذكر
            Auth::login($user, true);
            $request->session()->regenerate();
            
            // Force session save before sending response to ensure cookie is set
            $request->session()->save();
            
            Log::info('New user registered', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء الحساب بنجاح',
                'redirect' => route('web.home')
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى التحقق من البيانات',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء الحساب'
            ], 500);
        }
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        $userId = Auth::id();
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($userId) {
            Log::info('User logged out', ['user_id' => $userId]);
        }

        return redirect()->route('web.home')->with('success', 'تم تسجيل الخروج بنجاح');
    }
}