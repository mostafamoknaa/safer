@extends('layouts.admin')

@section('title', __('admin.hotels.create_title'))
@section('page-title', __('admin.hotels.create_heading'))
@section('page-subtitle', __('admin.hotels.create_subheading'))

@section('content')
    <form method="POST" action="{{ route('admin.hotels.store') }}"
          enctype="multipart/form-data"
          class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur space-y-6">
        @csrf
        @include('admin.hotels._form')
    </form>
@endsection

