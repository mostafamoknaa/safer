@extends('layouts.admin')

@section('title', 'تفاصيل الفعالية/النشاط')
@section('page-title', 'تفاصيل الفعالية/النشاط')
@section('page-subtitle', 'عرض معلومات الفعالية والوثائق')

@section('content')
    <div class="space-y-6">
        <!-- Event Basic Info -->
        <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-slate-900">معلومات الفعالية الأساسية</h2>
                <div class="flex gap-2">
                    <a href="{{ route('admin.events.edit', $event) }}" 
                       class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                        <i class="fas fa-edit"></i>
                        تعديل
                    </a>
                    <a href="{{ route('admin.events.index') }}" 
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100">
                        <i class="fas fa-arrow-right"></i>
                        العودة
                    </a>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">اسم الفعالية (عربي)</label>
                    <p class="text-slate-900 font-semibold">{{ $event->name_ar }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">اسم الفعالية (إنجليزي)</label>
                    <p class="text-slate-900 font-semibold">{{ $event->name_en }}</p>
                </div>
                
                @if($event->activity_type)
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">نوع النشاط</label>
                    <p class="text-slate-900">{{ $event->activity_type }}</p>
                </div>
                @endif
                
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">السعر</label>
                    <p class="text-slate-900">
                        @if($event->price_per_person)
                            {{ number_format($event->price_per_person, 2) }} ر.س للفرد
                        @else
                            {{ number_format($event->price, 2) }} ر.س
                        @endif
                    </p>
                </div>
                
                @if($event->duration)
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">المدة</label>
                    <p class="text-slate-900">{{ $event->duration }}</p>
                </div>
                @endif
                
                @if($event->phone)
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">الهاتف</label>
                    <p class="text-slate-900">{{ $event->phone }}</p>
                </div>
                @endif
                
                @if($event->phone_secondary)
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">الهاتف الثاني</label>
                    <p class="text-slate-900">{{ $event->phone_secondary }}</p>
                </div>
                @endif
                
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">المنظم</label>
                    <p class="text-slate-900">{{ $event->user ? $event->user->name : 'إدارة النظام' }}</p>
                </div>
                
                @if($event->event_date)
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">تاريخ الفعالية</label>
                    <p class="text-slate-900">{{ $event->event_date->format('Y-m-d H:i') }}</p>
                </div>
                @endif
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-600 mb-2">الموقع (عربي)</label>
                    <p class="text-slate-900">{{ $event->location_ar }}</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-600 mb-2">الموقع (إنجليزي)</label>
                    <p class="text-slate-900">{{ $event->location_en }}</p>
                </div>
                
                @if($event->description_ar)
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-600 mb-2">الوصف (عربي)</label>
                    <p class="text-slate-900">{{ $event->description_ar }}</p>
                </div>
                @endif
                
                @if($event->description_en)
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-600 mb-2">الوصف (إنجليزي)</label>
                    <p class="text-slate-900">{{ $event->description_en }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Activity Images -->
        @if($event->activity_images && count($event->activity_images) > 0)
        <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
            <h2 class="text-xl font-semibold text-slate-900 mb-6">صور النشاط</h2>
            <div class="grid gap-4 grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                @foreach($event->activity_images as $image)
                    <div class="relative group">
                        <img src="{{ asset('storage/' . $image) }}" 
                             alt="صورة النشاط" 
                             class="w-full h-32 object-cover rounded-lg">
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                            <a href="{{ asset('storage/' . $image) }}" 
                               target="_blank" 
                               class="text-white hover:text-blue-300">
                                <i class="fas fa-expand text-xl"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- ID Documents -->
        @if($event->id_images && count($event->id_images) > 0)
        <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
            <h2 class="text-xl font-semibold text-slate-900 mb-6">وثائق الهوية</h2>
            <div class="grid gap-6 md:grid-cols-2">
                @if(isset($event->id_images['front']))
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-2">الوجه الأمامي</label>
                        <img src="{{ asset('storage/' . $event->id_images['front']) }}" 
                             alt="الوجه الأمامي للهوية" 
                             class="w-full max-w-sm h-32 object-cover rounded-lg border">
                    </div>
                @endif
                @if(isset($event->id_images['back']))
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-2">الوجه الخلفي</label>
                        <img src="{{ asset('storage/' . $event->id_images['back']) }}" 
                             alt="الوجه الخلفي للهوية" 
                             class="w-full max-w-sm h-32 object-cover rounded-lg border">
                    </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Booking Settings -->
        @if($event->activity_type)
        <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
            <h2 class="text-xl font-semibold text-slate-900 mb-6">إعدادات الحجز</h2>
            <div class="grid gap-6 md:grid-cols-2">
                @if($event->max_participants)
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">الحد الأقصى للمشاركين</label>
                    <p class="text-slate-900">{{ $event->max_participants }} شخص</p>
                </div>
                @endif
                
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">الحجز الفوري</label>
                    <p class="text-slate-900">{{ $event->instant_booking ? 'مفعل' : 'غير مفعل' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">السماح بالإلغاء</label>
                    <p class="text-slate-900">{{ $event->allow_cancellation ? 'مسموح' : 'غير مسموح' }}</p>
                </div>
                
                @if($event->cancellation_hours)
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">ساعات الإلغاء المجاني</label>
                    <p class="text-slate-900">{{ $event->cancellation_hours }} ساعة</p>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Status Actions -->
        @if($event->user_id)
        <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
            <h2 class="text-xl font-semibold text-slate-900 mb-6">إجراءات الموافقة</h2>
            <div class="flex gap-4">
                @if(!$event->is_active)
                    <form method="POST" action="{{ route('admin.events.update', $event) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="is_active" value="1">
                        <button type="submit" 
                                class="inline-flex items-center gap-2 rounded-xl bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">
                            <i class="fas fa-check"></i>
                            الموافقة على النشاط
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.events.update', $event) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="is_active" value="0">
                        <button type="submit" 
                                class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-black hover:bg-red-700">
                            <i class="fas fa-times"></i>
                            إلغاء تفعيل النشاط
                        </button>
                    </form>
                @endif
            </div>
        </div>
        @endif
    </div>
@endsection