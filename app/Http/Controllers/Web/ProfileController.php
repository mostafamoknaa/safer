<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('web.profile.edit', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        \Log::info('Profile Update Attempt', [
            'user_id' => $user->id,
            'request_data' => $request->except(['password', 'password_confirmation', 'image']),
            'has_password' => $request->has('password'),
            'password_filled' => $request->filled('password'),
            'password_length' => $request->has('password') ? strlen($request->password) : 'N/A',
            'has_confirmation' => $request->has('password_confirmation'),
            'confirmation_length' => $request->has('password_confirmation') ? strlen($request->password_confirmation) : 'N/A',
        ]);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'image' => 'nullable|image|max:2048', // 2MB Max
        ];

        if ($request->filled('new_password')) {
            $rules['new_password'] = 'required|string|min:8|confirmed';
        }

        try {
            $validated = $request->validate($rules);
            \Log::info('Profile Validation Passed');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Profile Validation Failed', ['errors' => $e->errors()]);
            throw $e;
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];

        if ($request->filled('new_password')) {
            $user->password = Hash::make($validated['new_password']);
            \Log::info('Password updated');
        }

        if ($request->hasFile('image')) {
            \Log::info('Processing image upload');
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();

            // Fixed: use getRawOriginal to get the actual filename from DB, not the full URL from the getter
            $oldImage = $user->getRawOriginal('image');

            $image->move(public_path('uploads/profile'), $filename);
            \Log::info('New image moved', ['filename' => $filename]);

            if ($oldImage && file_exists(public_path('uploads/profile/' . $oldImage))) {
                @unlink(public_path('uploads/profile/' . $oldImage));
                \Log::info('Old image deleted', ['old_image' => $oldImage]);
            }

            $user->image = $filename;
        }

        $user->save();
        \Log::info('User profile saved successfully', ['user_id' => $user->id]);

        return redirect()->route('web.profile.edit')->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }
}
