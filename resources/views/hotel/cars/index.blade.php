@extends('layouts.hotel')

@section('title', 'السيارات')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">السيارات</h1>
        <a href="{{ route('hotel.cars.create') }}" class="bg-blue-600 px-4 py-2 rounded-lg hover:bg-blue-700">
            إضافة سيارة
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الاسم</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الموديل</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">السعر/يوم</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">عدد المقاعد</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">عدد الطلبات</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($cars as $car)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $car->name_ar }}</div>
                        <div class="text-sm text-gray-500">{{ $car->name_en }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $car->car_model }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ $car->price_per_day }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $car->seats_count }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $car->service_requests_count }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full {{ $car->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $car->is_active ? 'نشط' : 'غير نشط' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('hotel.cars.edit', $car) }}" class="text-blue-600 hover:text-blue-900 mr-3">تعديل</a>
                        <form action="{{ route('hotel.cars.destroy', $car) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('هل أنت متأكد؟')">حذف</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">لا توجد سيارات</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $cars->links() }}
</div>
@endsection