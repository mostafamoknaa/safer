@extends('layouts.admin')

@section('title', __('admin.buses.edit_title'))
@section('page-title', __('admin.buses.edit_heading'))
@section('page-subtitle', __('admin.buses.edit_subheading'))

@section('content')
    <form method="POST" action="{{ route('admin.buses.update', $bus) }}"
          class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur space-y-6">
        @csrf
        @method('PUT')
        @include('admin.buses._form', ['bus' => $bus])
    </form>
@endsection

