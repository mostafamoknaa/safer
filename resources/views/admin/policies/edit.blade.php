@extends('layouts.admin')

@section('title', __('admin.policies.edit_title'))
@section('page-title', __('admin.policies.edit_heading'))
@section('page-subtitle', __('admin.policies.edit_subheading', ['title' => $policy->title_ar])) 

@section('content')
    <form method="POST" action="{{ route('admin.policies.update', $policy) }}"
          class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur space-y-6">
        @csrf
        @method('PUT')
        @include('admin.policies._form', ['policy' => $policy])
    </form>
@endsection

