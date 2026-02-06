@extends('layouts.admin')

@section('title', __('admin.contact_links.edit_title'))
@section('page-title', __('admin.contact_links.edit_heading'))
@section('page-subtitle', __('admin.contact_links.edit_subheading', ['name' => $contactLink->title_ar])) 

@section('content')
    <form method="POST" action="{{ route('admin.contact-links.update', $contactLink) }}"
          class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur space-y-6">
        @csrf
        @method('PUT')
        @include('admin.contact-links._form', ['contactLink' => $contactLink])
    </form>
@endsection

