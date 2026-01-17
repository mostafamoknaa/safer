@extends('layouts.hotel')

@section('title', 'إضافة رحلة')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">إضافة رحلة</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('hotel.trips.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="departure_location_ar" class="block text-sm font-medium text-gray-700">مكان المغادرة (عربي)</label>
                    <input type="text" name="departure_location_ar" id="departure_location_ar" value="{{ old('departure_location_ar') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @error('departure_location_ar')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="departure_location_en" class="block text-sm font-medium text-gray-700">مكان المغادرة (إنجليزي)</label>
                    <input type="text" name="departure_location_en" id="departure_location_en" value="{{ old('departure_location_en') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @error('departure_location_en')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="arrival_location_ar" class="block text-sm font-medium text-gray-700">مكان الوصول (عربي)</label>
                    <input type="text" name="arrival_location_ar" id="arrival_location_ar" value="{{ old('arrival_location_ar') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @error('arrival_location_ar')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="arrival_location_en" class="block text-sm font-medium text-gray-700">مكان الوصول (إنجليزي)</label>
                    <input type="text" name="arrival_location_en" id="arrival_location_en" value="{{ old('arrival_location_en') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @error('arrival_location_en')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="bus_id" class="block text-sm font-medium text-gray-700">الحافلة</label>
                    <select name="bus_id" id="bus_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">اختر الحافلة</option>
                        @foreach($buses as $bus)
                            <option value="{{ $bus->id }}" {{ old('bus_id') == $bus->id ? 'selected' : '' }}>
                                {{ $bus->name_ar }} ({{ $bus->total_seats }} مقعد)
                            </option>
                        @endforeach
                    </select>
                    @error('bus_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700">السعر</label>
                    <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="trip_date" class="block text-sm font-medium text-gray-700">تاريخ الرحلة</label>
                    <input type="date" name="trip_date" id="trip_date" value="{{ old('trip_date') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @error('trip_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="trip_time" class="block text-sm font-medium text-gray-700">وقت الرحلة</label>
                    <input type="time" name="trip_time" id="trip_time" value="{{ old('trip_time') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @error('trip_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="duration_minutes" class="block text-sm font-medium text-gray-700">المدة (بالدقائق)</label>
                    <input type="number" name="duration_minutes" id="duration_minutes" value="{{ old('duration_minutes') }}" min="1"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @error('duration_minutes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="mr-2 text-sm text-gray-600">نشط</span>
                </label>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('hotel.trips.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">
                    إلغاء
                </a>
                <button type="submit" class="bg-blue-600  px-4 py-2 rounded-lg hover:bg-blue-700">
                    حفظ الرحلة
                </button>
            </div>
        </form>
    </div>
</div>
@endsection