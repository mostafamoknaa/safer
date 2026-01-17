@extends('layouts.hotel')

@section('title', 'إضافة سيارة')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">إضافة سيارة</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('hotel.cars.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name_ar" class="block text-sm font-medium text-gray-700">الاسم (عربي)</label>
                    <input type="text" name="name_ar" id="name_ar" value="{{ old('name_ar') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @error('name_ar')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="name_en" class="block text-sm font-medium text-gray-700">الاسم (إنجليزي)</label>
                    <input type="text" name="name_en" id="name_en" value="{{ old('name_en') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @error('name_en')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="car_model" class="block text-sm font-medium text-gray-700">الموديل</label>
                    <input type="text" name="car_model" id="car_model" value="{{ old('car_model') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @error('car_model')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price_per_day" class="block text-sm font-medium text-gray-700">السعر لليوم</label>
                    <input type="number" name="price_per_day" id="price_per_day" value="{{ old('price_per_day') }}" step="0.01" min="0"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @error('price_per_day')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price_per_hour" class="block text-sm font-medium text-gray-700">السعر للساعة</label>
                    <input type="number" name="price_per_hour" id="price_per_hour" value="{{ old('price_per_hour') }}" step="0.01" min="0"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('price_per_hour')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="seats_count" class="block text-sm font-medium text-gray-700">عدد المقاعد</label>
                    <input type="number" name="seats_count" id="seats_count" value="{{ old('seats_count') }}" min="1"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @error('seats_count')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="fuel_type" class="block text-sm font-medium text-gray-700">نوع الوقود</label>
                    <input type="text" name="fuel_type" id="fuel_type" value="{{ old('fuel_type') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('fuel_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="transmission" class="block text-sm font-medium text-gray-700">ناقل الحركة</label>
                    <input type="text" name="transmission" id="transmission" value="{{ old('transmission') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('transmission')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label for="images" class="block text-sm font-medium text-gray-700">الصور</label>
                <input type="file" name="images[]" id="images" multiple accept="image/*"
                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                @error('images')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="mr-2 text-sm text-gray-600">نشط</span>
                </label>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('hotel.cars.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">
                    إلغاء
                </a>
                <button type="submit" class="bg-blue-600 px-4 py-2 rounded-lg hover:bg-blue-700">
                    حفظ السيارة
                </button>
            </div>
        </form>
    </div>
</div>
@endsection