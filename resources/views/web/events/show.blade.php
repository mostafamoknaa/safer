@extends('layouts.web')

@section('title', app()->getLocale() == 'ar' ? $event->name_ar : $event->name_en)

@section('content')
    <div class="container mx-auto px-4">
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            @if($event->image)
                <img src="{{ Storage::url($event->image) }}"
                    alt="{{ app()->getLocale() == 'ar' ? $event->name_ar : $event->name_en }}" class="w-full h-96 object-cover">
            @else
                <div class="w-full h-96 bg-gray-200 flex items-center justify-center">
                    <span class="text-gray-400 text-xl">لا توجد صورة</span>
                </div>
            @endif

            <div class="p-6">
                <h1 class="text-3xl font-bold mb-4">
                    {{ app()->getLocale() == 'ar' ? $event->name_ar : $event->name_en }}
                </h1>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-gray-600 mb-2">
                            📅 <strong>التاريخ:</strong> {{ \Carbon\Carbon::parse($event->event_date)->format('Y-m-d') }}
                        </p>
                        <p class="text-gray-600 mb-2">
                            📍 <strong>الموقع:</strong>
                            {{ app()->getLocale() == 'ar' ? $event->location_ar : $event->location_en }}
                        </p>
                        <p class="text-gray-600 mb-2">
                            🎫 <strong>التذاكر المتاحة:</strong> {{ $event->available_tickets }}
                        </p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-blue-600 mb-4">
                            {{ number_format($event->ticket_price) }} ريال
                        </p>
                        <p class="text-gray-500">سعر التذكرة الواحدة</p>
                    </div>
                </div>

                <div class="prose max-w-none mb-6">
                    <h3 class="text-xl font-semibold mb-2">الوصف</h3>
                    <p class="text-gray-700">
                        {{ app()->getLocale() == 'ar' ? $event->description_ar : $event->description_en }}
                    </p>
                </div>

                @auth
                    @if($event->available_tickets > 0 && $event->event_date >= now())
                        <form method="POST" action="{{ route('web.events.purchase') }}" class="bg-gray-50 p-6 rounded-lg">
                            @csrf
                            <input type="hidden" name="event_id" value="{{ $event->id }}">

                            <div class="mb-4">
                                <label class="block text-gray-700 font-semibold mb-2">عدد التذاكر</label>
                                <input type="number" name="number_of_tickets" min="1" max="{{ $event->available_tickets }}"
                                    value="1" class="w-full md:w-48 border rounded-lg px-4 py-2">
                            </div>

                            <button type="submit"
                                class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition text-lg font-semibold">
                                شراء التذاكر
                            </button>
                        </form>
                    @elseif($event->available_tickets == 0)
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            التذاكر غير متاحة - نفدت الكمية
                        </div>
                    @else
                        <div class="bg-gray-100 border border-gray-400 text-gray-700 px-4 py-3 rounded">
                            هذه الفعالية انتهت
                        </div>
                    @endif
                @else
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                        <a href="{{ route('login') }}" class="font-semibold hover:underline">سجل دخول</a> لشراء التذاكر
                    </div>
                @endauth
            </div>
        </div>
    </div>
@endsection