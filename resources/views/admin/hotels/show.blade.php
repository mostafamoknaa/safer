@extends('layouts.admin')

@section('title', 'تفاصيل الفندق')
@section('page-title', 'تفاصيل الفندق')
@section('page-subtitle', 'عرض معلومات الفندق والوثائق')

@section('content')
    <div class="space-y-6">
        <!-- Hotel Basic Info -->
        <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-slate-900">معلومات الفندق الأساسية</h2>
                <div class="flex gap-2">
                    <a href="{{ route('admin.hotels.edit', $hotel) }}" 
                       class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                        <i class="fas fa-edit"></i>
                        تعديل
                    </a>
                    <a href="{{ route('admin.hotels.index') }}" 
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100">
                        <i class="fas fa-arrow-right"></i>
                        العودة
                    </a>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">اسم الفندق (عربي)</label>
                    <p class="text-slate-900 font-semibold">{{ $hotel->name_ar }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">اسم الفندق (إنجليزي)</label>
                    <p class="text-slate-900 font-semibold">{{ $hotel->name_en }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">النوع</label>
                    <p class="text-slate-900">{{ $hotel->type }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">السعر</label>
                    <p class="text-slate-900">{{ $hotel->price ? number_format($hotel->price, 2) . ' ر.س' : 'غير محدد' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">الهاتف</label>
                    <p class="text-slate-900">{{ $hotel->phone ?? 'غير محدد' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">الهاتف الثاني</label>
                    <p class="text-slate-900">{{ $hotel->phone_2 ?? 'غير محدد' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">المالك</label>
                    <p class="text-slate-900">{{ $hotel->user ? $hotel->user->name : 'إدارة النظام' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">المنطقة</label>
                    <p class="text-slate-900">{{ $hotel->province ? $hotel->province->name_ar : 'غير محدد' }}</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-600 mb-2">العنوان (عربي)</label>
                    <p class="text-slate-900">{{ $hotel->address_ar }}</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-600 mb-2">العنوان (إنجليزي)</label>
                    <p class="text-slate-900">{{ $hotel->address_en }}</p>
                </div>
                @if($hotel->description_ar)
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-600 mb-2">الوصف (عربي)</label>
                    <p class="text-slate-900">{{ $hotel->description_ar }}</p>
                </div>
                @endif
                @if($hotel->description_en)
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-600 mb-2">الوصف (إنجليزي)</label>
                    <p class="text-slate-900">{{ $hotel->description_en }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Hotel Images -->
        @if($hotel->media->count() > 0)
        <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
            <h2 class="text-xl font-semibold text-slate-900 mb-6">صور الفندق</h2>
            <div class="grid gap-4 grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                @foreach($hotel->media as $media)
                    <div class="relative group">
                        <img src="{{ asset('storage/' . $media->file_path) }}" 
                             alt="صورة الفندق" 
                             class="w-full h-32 object-cover rounded-lg">
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                            <a href="{{ asset('storage/' . $media->file_path) }}" 
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

        <!-- Documents -->
        @if($hotel->user_id && ($hotel->identity_images || $hotel->lease_agreement))
        <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
            <h2 class="text-xl font-semibold text-slate-900 mb-6">الوثائق المرفقة</h2>
            <div class="grid gap-6 md:grid-cols-2">
                @if($hotel->identity_images)
                    <div>
                        <h3 class="text-lg font-medium text-slate-800 mb-4">صور الهوية</h3>
                        <div class="space-y-4">
                            @if(isset($hotel->identity_images['front']))
                                <div>
                                    <label class="block text-sm font-medium text-slate-600 mb-2">الوجه الأمامي</label>
                                    <img src="{{ asset('storage/' . $hotel->identity_images['front']) }}" 
                                         alt="الوجه الأمامي للهوية" 
                                         class="w-full max-w-sm h-32 object-cover rounded-lg border">
                                </div>
                            @endif
                            @if(isset($hotel->identity_images['back']))
                                <div>
                                    <label class="block text-sm font-medium text-slate-600 mb-2">الوجه الخلفي</label>
                                    <img src="{{ asset('storage/' . $hotel->identity_images['back']) }}" 
                                         alt="الوجه الخلفي للهوية" 
                                         class="w-full max-w-sm h-32 object-cover rounded-lg border">
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
                
                @if($hotel->lease_agreement)
                    <div>
                        <h3 class="text-lg font-medium text-slate-800 mb-4">عقد الإيجار</h3>
                        <img src="{{ asset('storage/' . $hotel->lease_agreement) }}" 
                             alt="عقد الإيجار" 
                             class="w-full max-w-sm h-32 object-cover rounded-lg border">
                    </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Status Actions -->
        @if($hotel->user_id)
        <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
            <h2 class="text-xl font-semibold text-slate-900 mb-6">إجراءات الموافقة</h2>
            <div class="flex gap-4">
                @if(!$hotel->is_active)
                    <form method="POST" action="{{ route('admin.hotels.update', $hotel) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="is_active" value="1">
                        <button type="submit" 
                                class="inline-flex items-center gap-2 rounded-xl bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">
                            <i class="fas fa-check"></i>
                            الموافقة على الفندق
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.hotels.update', $hotel) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="is_active" value="0">
                        <button type="submit" 
                                class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                            <i class="fas fa-times"></i>
                            إلغاء تفعيل الفندق
                        </button>
                    </form>
                @endif
            </div>
        </div>
        @endif
    </div>
@endsection