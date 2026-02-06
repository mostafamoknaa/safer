<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        $faqs = Faq::orderBy('order_column')->orderByDesc('created_at')->paginate(15);

        return view('admin.faqs.index', compact('faqs'));
    }

    public function create(): View
    {
        return view('admin.faqs.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        Faq::create($data);

        return redirect()
            ->route('admin.faqs.index')
            ->with('success', trans('admin.faqs.messages.created'));
    }

    public function edit(Faq $faq): View
    {
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq): RedirectResponse
    {
        $data = $this->validatedData($request);

        $faq->update($data);

        return redirect()
            ->route('admin.faqs.index')
            ->with('success', trans('admin.faqs.messages.updated'));
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $faq->delete();

        return redirect()
            ->route('admin.faqs.index')
            ->with('success', trans('admin.faqs.messages.deleted'));
    }

    protected function validatedData(Request $request): array
    {
        return $request->validate([
            'question_ar' => ['required', 'string', 'max:255'],
            'question_en' => ['required', 'string', 'max:255'],
            'answer_ar' => ['required', 'string'],
            'answer_en' => ['required', 'string'],
            'order_column' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]) + [
            'order_column' => $request->input('order_column', 0),
            'is_active' => $request->boolean('is_active'),
        ];
    }
}

