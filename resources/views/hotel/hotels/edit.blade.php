@extends('layouts.hotel')

@section('title', __('hotel.hotels.edit_title'))
@section('page-title', __('hotel.hotels.edit_heading'))
@section('page-subtitle', __('hotel.hotels.edit_subheading'))

@section('content')
    <form method="POST" action="{{ route('hotel.hotels.update', $hotel) }}"
          enctype="multipart/form-data"
          class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur space-y-6">
        @csrf
        @method('PUT')
        @include('hotel.hotels._form')
    </form>
@endsection

