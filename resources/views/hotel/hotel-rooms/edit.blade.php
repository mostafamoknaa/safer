@extends('layouts.hotel')

@section('title', __('hotel.hotel_rooms.edit_title'))
@section('page-title', __('hotel.hotel_rooms.edit_heading'))
@section('page-subtitle', __('hotel.hotel_rooms.edit_subheading'))

@section('content')
    <form method="POST" action="{{ route('hotel.hotel-rooms.update', $hotelRoom) }}"
          enctype="multipart/form-data"
          class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur space-y-6">
        @csrf
        @method('PUT')
        @include('hotel.hotel-rooms._form')
    </form>
@endsection

