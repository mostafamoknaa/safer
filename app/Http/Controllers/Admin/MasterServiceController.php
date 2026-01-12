<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MasterServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::orderByDesc('created_at')->paginate(15);
        return view('admin.services.master.index', compact('services'));
    }

    public function create(): View
    {
        return view('admin.services.master.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        Service::create($data);

        return redirect()
            ->route('admin.master-services.index')
            ->with('success', trans('admin.services.messages.created'));
    }

    public function edit(Service $master_service): View
    {
        return view('admin.services.master.edit', ['service' => $master_service]);
    }

    public function update(Request $request, Service $master_service): RedirectResponse
    {
        $data = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($master_service->image) {
                Storage::disk('public')->delete($master_service->image);
            }
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        $master_service->update($data);

        return redirect()
            ->route('admin.master-services.index')
            ->with('success', trans('admin.services.messages.updated'));
    }

    public function destroy(Service $master_service): RedirectResponse
    {
        if ($master_service->image) {
            Storage::disk('public')->delete($master_service->image);
        }
        
        $master_service->delete();

        return redirect()
            ->route('admin.master-services.index')
            ->with('success', trans('admin.services.messages.deleted'));
    }
}
