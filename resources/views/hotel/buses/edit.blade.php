@extends('layouts.hotel')

@section('title', 'تعديل حافلة')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">تعديل حافلة</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('hotel.buses.update', $bus) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name_ar" class="block text-sm font-medium text-gray-700">الاسم (عربي)</label>
                    <input type="text" name="name_ar" id="name_ar" value="{{ old('name_ar', $bus->name_ar) }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @error('name_ar')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="name_en" class="block text-sm font-medium text-gray-700">الاسم (إنجليزي)</label>
                    <input type="text" name="name_en" id="name_en" value="{{ old('name_en', $bus->name_en) }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @error('name_en')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">النوع</label>
                    <input type="text" name="type" id="type" value="{{ old('type', $bus->type) }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="total_seats" class="block text-sm font-medium text-gray-700">عدد المقاعد</label>
                    <input type="number" name="total_seats" id="total_seats" value="{{ old('total_seats', $bus->total_seats) }}" min="1"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @error('total_seats')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $bus->is_active) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="mr-2 text-sm text-gray-600">نشط</span>
                </label>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('hotel.buses.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">
                    إلغاء
                </a>
                <button type="submit" class="bg-blue-600  px-4 py-2 rounded-lg hover:bg-blue-700">
                    تحديث الحافلة
                </button>
            </div>
        </form>
    </div>
</div>
@endsection