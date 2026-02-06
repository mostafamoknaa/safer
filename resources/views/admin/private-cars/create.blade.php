@extends('layouts.admin')

@section('title', __('admin.private_cars.create_title'))
@section('page-title', __('admin.private_cars.create_heading'))
@section('page-subtitle', __('admin.private_cars.create_subheading'))

@section('content')
    <form method="POST" action="{{ route('admin.private-cars.store') }}" enctype="multipart/form-data"
          class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur space-y-6">
        @csrf
        @include('admin.private-cars._form')
    </form>
@endsection

