@extends('layouts.hotel')

@section('title', 'الرحلات')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">الرحلات</h1>
        <a href="{{ route('hotel.trips.create') }}" class="bg-blue-600 px-4 py-2 rounded-lg hover:bg-blue-700">
            إضافة رحلة
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المسار</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحافلة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">التاريخ والوقت</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">السعر</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">عدد الحجوزات</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($trips as $trip)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $trip->departure_location_ar }} ← {{ $trip->arrival_location_ar }}</div>
                        <div class="text-sm text-gray-500">{{ $trip->departure_location_en }} ← {{ $trip->arrival_location_en }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $trip->bus ? $trip->bus->name_ar : 'غير محدد' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $trip->trip_date->format('Y-m-d') }}</div>
                        <div class="text-sm text-gray-500">{{ $trip->trip_time }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ $trip->price }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $trip->service_requests_count }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full {{ $trip->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $trip->is_active ? 'نشط' : 'غير نشط' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('hotel.trips.edit', $trip) }}" class="text-blue-600 hover:text-blue-900 mr-3">تعديل</a>
                        <form action="{{ route('hotel.trips.destroy', $trip) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('هل أنت متأكد؟')">حذف</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">لا توجد رحلات</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $trips->links() }}
</div>
@endsection