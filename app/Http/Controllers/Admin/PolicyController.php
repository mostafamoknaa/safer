<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Str;

class PolicyController extends Controller
{
    public function index(): View
    {
        $policies = Policy::orderBy('slug')->paginate(10);

        return view('admin.policies.index', compact('policies'));
    }

    public function create(): View
    {
        return view('admin.policies.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        if (! $data['slug']) {
            $data['slug'] = Str::slug($data['title_en']);
        }

        Policy::create($data);

        return redirect()
            ->route('admin.policies.index')
            ->with('success', trans('admin.policies.messages.created'));
    }

    public function edit(Policy $policy): View
    {
        return view('admin.policies.edit', compact('policy'));
    }

    public function update(Request $request, Policy $policy): RedirectResponse
    {
        $data = $this->validatedData($request, $policy->id);

        if (! $data['slug']) {
            $data['slug'] = Str::slug($data['title_en']);
        }

        $policy->update($data);

        return redirect()
            ->route('admin.policies.index')
            ->with('success', trans('admin.policies.messages.updated'));
    }

    public function destroy(Policy $policy): RedirectResponse
    {
        $policy->delete();

        return redirect()
            ->route('admin.policies.index')
            ->with('success', trans('admin.policies.messages.deleted'));
    }

    protected function validatedData(Request $request, ?int $policyId = null): array
    {
        return $request->validate([
            'slug' => [
                'nullable',
                'string',
                'max:150',
                Rule::unique('policies', 'slug')->ignore($policyId),
            ],
            'title_ar' => ['required', 'string', 'max:200'],
            'title_en' => ['required', 'string', 'max:200'],
            'body_ar' => ['required', 'string'],
            'body_en' => ['required', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]) + [
            'is_active' => $request->boolean('is_active'),
        ];
    }
}

