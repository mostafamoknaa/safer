@extends('layouts.admin')

@section('title', __('admin.hotels.edit_title'))
@section('page-title', __('admin.hotels.edit_heading'))
@section('page-subtitle', __('admin.hotels.edit_subheading', ['name' => $hotel->name]))

@section('content')
    <form method="POST" action="{{ route('admin.hotels.update', $hotel) }}"
          enctype="multipart/form-data"
          class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur space-y-6">
        @csrf
        @method('PUT')
        @include('admin.hotels._form')
    </form>
@endsection

