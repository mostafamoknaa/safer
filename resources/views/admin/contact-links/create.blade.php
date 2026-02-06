@extends('layouts.admin')

@section('title', __('admin.contact_links.create_title'))
@section('page-title', __('admin.contact_links.create_heading'))
@section('page-subtitle', __('admin.contact_links.create_subheading'))

@section('content')
    <form method="POST" action="{{ route('admin.contact-links.store') }}"
          class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur space-y-6">
        @csrf
        @include('admin.contact-links._form')
    </form>
@endsection

