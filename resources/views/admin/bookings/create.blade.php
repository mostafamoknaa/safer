@extends('layouts.admin')

@section('title', __('admin.bookings.create_title'))
@section('page-title', __('admin.bookings.create_heading'))
@section('page-subtitle', __('admin.bookings.create_subheading'))

@section('content')
    <form method="POST" action="{{ route('admin.bookings.store') }}"
          class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur space-y-6">
        @csrf
        @php $booking = null; @endphp
        @include('admin.bookings._form', ['booking' => null, 'hotels' => $hotels, 'users' => $users, 'rooms' => $rooms ?? collect()])
    </form>
@endsection

