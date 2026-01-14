@extends('layouts.web')

@section('title', 'خدمة الباصات')

@section('content')
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">خدمة الباصات</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($buses as $bus)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold mb-2">
                        {{ app()->getLocale() == 'ar' ? $bus->name_ar : $bus->name_en }}
                    </h3>
                    <p class="text-gray-600 mb-2">
                        {{ app()->getLocale() == 'ar' ? $bus->description_ar : $bus->description_en }}
                    </p>
                    <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
                        <span>👥 السعة: {{ $bus->capacity }} شخص</span>
                    </div>

                    @if($bus->trips->count() > 0)
                        <p class="text-blue-600 font-semibold mb-3">
                            {{ $bus->trips->count() }} رحلة متاحة
                        </p>
                    @endif

                    <a href="{{ route('web.services.trips', ['bus_id' => $bus->id]) }}"
                        class="block w-full text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                        عرض الرحلات
                    </a>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 text-gray-500">
                    لا توجد باصات متاحة حالياً
                </div>
            @endforelse
        </div>

        <!-- Link to Trips -->
        <div class="mt-8 text-center">
            <a href="{{ route('web.services.trips') }}"
                class="inline-block bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition">
                عرض جميع الرحلات
            </a>
        </div>
    </div>
@endsection