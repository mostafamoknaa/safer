@extends('layouts.hotel')

@section('title', 'إدارة التقويم - ' . (app()->getLocale() === 'ar' ? $hotel->name_ar : $hotel->name_en))
@section('page-title', 'إدارة التقويم')
@section('page-subtitle', 'مزامنة الحجوزات مع منصات أخرى')

@section('content')
    <div class="grid gap-6">
        {{-- Hotel Info --}}
        <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">{{ app()->getLocale() === 'ar' ? $hotel->name_ar : $hotel->name_en }}</h3>
                    <p class="text-sm text-slate-500">منشأة رقم: {{ $hotel->id }}</p>
                </div>
                <a href="{{ route('hotel.hotels.index') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                    <i class="fas fa-arrow-right"></i>
                    رجوع
                </a>
            </div>
        </div>

        {{-- Export Section --}}
        <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
            <h3 class="text-lg font-bold text-slate-900 mb-4">
                <i class="fas fa-upload text-indigo-600"></i>
                تصدير التقويم
            </h3>
            <p class="text-sm text-slate-600 mb-4">استخدم هذا الرابط لمزامنة جميع حجوزات الفندق مع Airbnb أو Booking.com أو أي منصة أخرى</p>
            
            <div class="flex items-center gap-3">
                <input type="text" readonly
                       value="{{ route('hotel.ical.export', $hotel) }}"
                       id="exportUrl"
                       class="flex-1 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-800 font-mono">
                <button onclick="copyExportUrl()"
                        class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700">
                    <i class="fas fa-copy"></i>
                    نسخ
                </button>
            </div>
        </div>

        {{-- Import Section --}}
        <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
            <h3 class="text-lg font-bold text-slate-900 mb-4">
                <i class="fas fa-download text-emerald-600"></i>
                استيراد التقويم
            </h3>
            <p class="text-sm text-slate-600 mb-4">أضف روابط التقويم من المنصات الأخرى لمنع الحجز المزدوج للمنشأة بالكامل</p>

            {{-- Add New URL Form --}}
            <form method="POST" action="{{ route('hotel.ical.store', $hotel) }}" class="mb-6">
                @csrf
                <div class="grid gap-4 sm:grid-cols-3">
                    <div>
                        <input type="text" name="name" placeholder="اسم المنصة (مثال: Airbnb)"
                               value="{{ old('name') }}"
                               class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                        @error('name')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <input type="url" name="url" placeholder="رابط التقويم (.ics)"
                               value="{{ old('url') }}"
                               required
                               class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                        @error('url')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">
                        <i class="fas fa-plus"></i>
                        إضافة
                    </button>
                </div>
            </form>

            {{-- URLs List --}}
            @if($hotel->icalUrls->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-right text-sm">
                        <thead class="bg-slate-50 text-xs font-medium uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-4 py-3">المنصة</th>
                                <th class="px-4 py-3">الرابط</th>
                                <th class="px-4 py-3 text-center">آخر مزامنة</th>
                                <th class="px-4 py-3 text-center">الحالة</th>
                                <th class="px-4 py-3 text-center">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach($hotel->icalUrls as $icalUrl)
                                <tr>
                                    <td class="px-4 py-4 font-semibold text-slate-800">
                                        {{ $icalUrl->name ?: 'غير محدد' }}
                                    </td>
                                    <td class="px-4 py-4 text-slate-600 font-mono text-xs truncate max-w-xs">
                                        {{ $icalUrl->url }}
                                    </td>
                                    <td class="px-4 py-4 text-center text-slate-600">
                                        {{ $icalUrl->last_sync_at ? $icalUrl->last_sync_at->diffForHumans() : 'لم تتم بعد' }}
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        @php
                                            $statusClasses = [
                                                'pending' => 'bg-yellow-50 text-yellow-600',
                                                'success' => 'bg-emerald-50 text-emerald-600',
                                                'failed' => 'bg-rose-50 text-rose-600',
                                            ];
                                            $statusClass = $statusClasses[$icalUrl->sync_status] ?? 'bg-slate-100 text-slate-600';
                                            $statusLabels = [
                                                'pending' => 'قيد الانتظار',
                                                'success' => 'نجحت',
                                                'failed' => 'فشلت',
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                            {{ $statusLabels[$icalUrl->sync_status] ?? $icalUrl->sync_status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <form method="POST" action="{{ route('hotel.ical.destroy', [$hotel, $icalUrl]) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('هل أنت متأكد من حذف هذا الرابط؟')"
                                                    class="inline-flex items-center gap-1 rounded-lg bg-rose-500/10 px-3 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-500/20">
                                                <i class="fas fa-trash"></i>
                                                حذف
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12 text-slate-500">
                    <i class="fas fa-calendar-xmark text-4xl mb-3 opacity-50"></i>
                    <p>لا توجد روابط تقويم مضافة للمنشأة</p>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        function copyExportUrl() {
            const input = document.getElementById('exportUrl');
            input.select();
            document.execCommand('copy');
            alert('تم نسخ الرابط!');
        }
    </script>
    @endpush
@endsection
