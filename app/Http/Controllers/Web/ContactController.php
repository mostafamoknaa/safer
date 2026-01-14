<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactMessage; // Assuming you have a model for this, or just email it.
// If no model, we'll just flash a success message for now as the user didn't specify backend logic for contact, just UI.

class ContactController extends Controller
{
    public function index()
    {
        return view('web.contact.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
        ]);

        // Logic to store message or send email would go here.
        // For now, we'll simply redirect back with success.

        return back()->with('success', 'تم إرسال رسالتك بنجاح، سنتواصل معك قريباً.');
    }
}
