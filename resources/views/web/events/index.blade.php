@extends('layouts.web')

@section('title', 'الفعاليات')

@section('content')
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">الفعاليات</h1>

        <!-- Filter -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-8">
            <form method="GET" action="{{ route('web.events.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-700 mb-2">البحث</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="w-full border rounded-lg px-4 py-2" placeholder="اسم الفعالية...">
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">الفلتر</label>
                        <select name="filter" class="w-full border rounded-lg px-4 py-2">
                            <option value="upcoming" {{ request('filter') == 'upcoming' ? 'selected' : '' }}>القادمة</option>
                            <option value="past" {{ request('filter') == 'past' ? 'selected' : '' }}>السابقة</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                            بحث
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Events Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @forelse($events as $event)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                    @if($event->image)
                        <img src="{{ Storage::url($event->image) }}"
                            alt="{{ app()->getLocale() == 'ar' ? $event->name_ar : $event->name_en }}"
                            class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-400">لا توجد صورة</span>
                        </div>
                    @endif

                    <div class="p-4">
                        <h3 class="text-xl font-semibold mb-2">
                            {{ app()->getLocale() == 'ar' ? $event->name_ar : $event->name_en }}
                        </h3>
                        <p class="text-gray-600 mb-2">
                            📅 {{ \Carbon\Carbon::parse($event->event_date)->format('Y-m-d') }}
                        </p>
                        <p class="text-gray-600 mb-2">
                            📍 {{ app()->getLocale() == 'ar' ? $event->location_ar : $event->location_en }}
                        </p>
                        <p class="text-blue-600 font-semibold mb-2">
                            {{ number_format($event->ticket_price) }} ريال
                        </p>
                        <p class="text-sm text-gray-500 mb-3">
                            التذاكر المتاحة: {{ $event->available_tickets }}
                        </p>

                        <a href="{{ route('web.events.show', $event) }}"
                            class="block w-full text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                            عرض التفاصيل
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 text-gray-500">
                    لا توجد فعاليات متاحة
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($events->hasPages())
            <div class="mt-8">
                {{ $events->links() }}
            </div>
        @endif
    </div>
@endsection