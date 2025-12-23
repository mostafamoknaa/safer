@extends('layouts.admin')

@section('title', __('admin.faqs.edit_title'))
@section('page-title', __('admin.faqs.edit_heading'))
@section('page-subtitle', __('admin.faqs.edit_subheading', ['question' => $faq->question_ar])) 

@section('content')
    <form method="POST" action="{{ route('admin.faqs.update', $faq) }}"
          class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur space-y-6">
        @csrf
        @method('PUT')
        @include('admin.faqs._form', ['faq' => $faq])
    </form>
@endsection

