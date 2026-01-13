@extends('layouts.web')

@section('title', 'الفنادق')

@section('content')
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">الفنادق المتاحة</h1>

        <!-- Search and Filter -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <form method="GET" action="{{ route('web.hotels.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-gray-700 mb-2">البحث</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="w-full border rounded-lg px-4 py-2" placeholder="اسم الفندق...">
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">المحافظة</label>
                        <select name="province_id" class="w-full border rounded-lg px-4 py-2">
                            <option value="">الكل</option>
                            @foreach($provinces as $province)
                                <option value="{{ $province->id }}" {{ request('province_id') == $province->id ? 'selected' : '' }}>
                                    {{ app()->getLocale() == 'ar' ? $province->name_ar : $province->name_en }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">التقييم الأدنى</label>
                        <select name="min_rating" class="w-full border rounded-lg px-4 py-2">
                            <option value="">الكل</option>
                            <option value="4" {{ request('min_rating') == 4 ? 'selected' : '' }}>4+ نجوم</option>
                            <option value="3" {{ request('min_rating') == 3 ? 'selected' : '' }}>3+ نجوم</option>
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

        <!-- Hotels Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @forelse($hotels as $hotel)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                    @if($hotel->image)
                        <img src="{{ Storage::url($hotel->image) }}"
                            alt="{{ app()->getLocale() == 'ar' ? $hotel->name_ar : $hotel->name_en }}"
                            class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-400">لا توجد صورة</span>
                        </div>
                    @endif

                    <div class="p-4">
                        <h3 class="text-xl font-semibold mb-2">
                            {{ app()->getLocale() == 'ar' ? $hotel->name_ar : $hotel->name_en }}
                        </h3>
                        <p class="text-gray-600 mb-2">
                            {{ $hotel->province ? (app()->getLocale() == 'ar' ? $hotel->province->name_ar : $hotel->province->name_en) : '' }}
                        </p>

                        @if($hotel->ratings_avg_rating)
                            <div class="flex items-center mb-3">
                                <span class="text-yellow-500">★</span>
                                <span class="ml-1 text-gray-700">{{ number_format($hotel->ratings_avg_rating, 1) }}</span>
                                <span class="text-gray-500 text-sm mr-2">({{ $hotel->ratings_count }} تقييم)</span>
                            </div>
                        @endif

                        <a href="{{ route('web.hotels.show', $hotel) }}"
                            class="block w-full text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                            عرض التفاصيل
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 text-gray-500">
                    لا توجد فنادق متاحة
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $hotels->links() }}
        </div>
    </div>
@endsection