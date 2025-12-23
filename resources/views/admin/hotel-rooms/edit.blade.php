@extends('layouts.admin')

@section('title', __('admin.hotel_rooms.edit_title'))
@section('page-title', __('admin.hotel_rooms.edit_heading'))
@section('page-subtitle', __('admin.hotel_rooms.edit_subheading'))

@section('content')
    <form method="POST" action="{{ route('admin.hotel-rooms.update', $hotelRoom) }}"
          enctype="multipart/form-data"
          class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur space-y-6">
        @csrf
        @method('PUT')
        @include('admin.hotel-rooms._form')
    </form>
@endsection

