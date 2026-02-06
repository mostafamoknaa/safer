<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactLinkController extends Controller
{
    public function index(): View
    {
        $links = ContactLink::orderByDesc('created_at')->paginate(12);

        return view('admin.contact-links.index', compact('links'));
    }

    public function create(): View
    {
        return view('admin.contact-links.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        ContactLink::create($data);

        return redirect()
            ->route('admin.contact-links.index')
            ->with('success', trans('admin.contact_links.messages.created'));
    }

    public function edit(ContactLink $contactLink): View
    {
        return view('admin.contact-links.edit', compact('contactLink'));
    }

    public function update(Request $request, ContactLink $contactLink): RedirectResponse
    {
        $data = $this->validatedData($request);

        $contactLink->update($data);

        return redirect()
            ->route('admin.contact-links.index')
            ->with('success', trans('admin.contact_links.messages.updated'));
    }

    public function destroy(ContactLink $contactLink): RedirectResponse
    {
        $contactLink->delete();

        return redirect()
            ->route('admin.contact-links.index')
            ->with('success', trans('admin.contact_links.messages.deleted'));
    }

    protected function validatedData(Request $request): array
    {
        return $request->validate([
            'type' => ['required', 'string', 'max:50'],
            'title_ar' => ['required', 'string', 'max:150'],
            'title_en' => ['required', 'string', 'max:150'],
            'url' => ['required', 'url', 'max:255'],
            'icon' => ['nullable', 'string', 'max:150'],
            'is_active' => ['sometimes', 'boolean'],
        ]) + [
            'is_active' => $request->boolean('is_active'),
        ];
    }
}

