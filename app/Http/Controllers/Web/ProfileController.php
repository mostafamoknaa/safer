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

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'image' => 'nullable|image|max:2048', // 2MB Max
            'type' => 'nullable|in:customer,provider',
            'card_number' => 'nullable|string|max:50',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->type = $validated['type'] ?? $user->type;
        $user->card_number = $validated['card_number'] ?? $user->card_number;

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/profile'), $filename);

            // Delete old image if exists (optional, good practice)
            if ($user->image && file_exists(public_path('uploads/profile/' . $user->image))) {
                @unlink(public_path('uploads/profile/' . $user->image));
            }

            $user->image = $filename;
        }

        $user->save();

        return redirect()->route('web.profile.edit')->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }
}
