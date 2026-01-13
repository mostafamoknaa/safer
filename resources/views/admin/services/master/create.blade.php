@extends('layouts.admin')

@section('title', __('admin.services.create_title'))
@section('page-title', __('admin.services.create_heading'))
@section('page-subtitle', __('admin.services.create_subheading'))

@section('content')
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-8 shadow-lg shadow-slate-200/60 backdrop-blur">
        <form action="{{ route('admin.master-services.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            @include('admin.services.master._form')

            <div class="mt-8 flex items-center justify-end gap-3 border-t border-slate-100 pt-8">
                <a href="{{ route('admin.master-services.index') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                    {{ __('admin.services.actions.cancel') }}
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-6 py-2 text-sm font-semibold text-white shadow-md shadow-slate-400/40 transition hover:bg-slate-700">
                    <i class="fas fa-save"></i>
                    {{ __('admin.services.actions.store') }}
                </button>
            </div>
        </form>
    </div>
@endsection
