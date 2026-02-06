@extends('layouts.admin')

@section('title', __('admin.bookings.edit_title'))
@section('page-title', __('admin.bookings.edit_heading'))
@section('page-subtitle', __('admin.bookings.edit_subheading'))

@section('content')
    <form method="POST" action="{{ route('admin.bookings.update', $booking) }}"
          class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur space-y-6">
        @csrf
        @method('PUT')
        @include('admin.bookings._form', ['booking' => $booking])
    </form>
@endsection

