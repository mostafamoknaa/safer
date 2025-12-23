@extends('layouts.hotel')

@section('title', __('hotel.hotel_rooms.create_title'))
@section('page-title', __('hotel.hotel_rooms.create_heading'))
@section('page-subtitle', __('hotel.hotel_rooms.create_subheading'))

@section('content')
    <form method="POST" action="{{ route('hotel.hotel-rooms.store') }}"
          enctype="multipart/form-data"
          class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur space-y-6">
        @csrf
        @include('hotel.hotel-rooms._form')
    </form>
@endsection

