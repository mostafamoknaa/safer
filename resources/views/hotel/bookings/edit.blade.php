@extends('layouts.hotel')

@section('title', __('hotel.bookings.edit_title'))
@section('page-title', __('hotel.bookings.edit_heading'))
@section('page-subtitle', __('hotel.bookings.edit_subheading'))

@section('content')
    <form method="POST" action="{{ route('hotel.bookings.update', $booking) }}"
          class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur space-y-6">
        @csrf
        @method('PUT')
        @include('hotel.bookings._form', ['booking' => $booking, 'hotels' => $hotels, 'users' => collect([$booking->user]), 'rooms' => $rooms])
    </form>
@endsection

