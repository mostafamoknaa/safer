@extends('layouts.hotel')

@section('title', 'الحافلات')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">الحافلات</h1>
        <a href="{{ route('hotel.buses.create') }}" class="bg-blue-600  px-4 py-2 rounded-lg hover:bg-blue-700">
            إضافة حافلة
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الاسم</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">النوع</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">عدد المقاعد</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">عدد الرحلات</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($buses as $bus)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $bus->name_ar }}</div>
                        <div class="text-sm text-gray-500">{{ $bus->name_en }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $bus->type }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $bus->total_seats }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $bus->trips_count }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full {{ $bus->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $bus->is_active ? 'نشط' : 'غير نشط' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('hotel.buses.edit', $bus) }}" class="text-blue-600 hover:text-blue-900 mr-3">تعديل</a>
                        <form action="{{ route('hotel.buses.destroy', $bus) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('هل أنت متأكد؟')">حذف</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">لا توجد حافلات</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $buses->links() }}
</div>
@endsection