@extends('layouts.admin')

@section('title', __('admin.events.edit_title'))
@section('page-title', __('admin.events.edit_heading'))
@section('page-subtitle', __('admin.events.edit_subheading'))

@section('content')
    <form method="POST" action="{{ route('admin.events.update', $event) }}"
          class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur space-y-6">
        @csrf
        @method('PUT')
        @include('admin.events._form', ['event' => $event])
    </form>
@endsection

