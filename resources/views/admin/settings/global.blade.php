@extends('layouts.admin')

@section('title', 'إعدادات المنصة')
@section('page-title', 'إعدادات العمولة')
@section('page-subtitle', 'إدارة نسب العمولة للخدمات المختلفة')

@section('content')
    <div class="max-w-5xl mx-auto">
        <form action="{{ route('admin.settings.global.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                {{-- Hotel & Accommodation Section --}}
                <div class="lg:col-span-3">
                    <h3 class="flex items-center gap-2 mb-4 text-sm font-bold uppercase tracking-wider text-slate-400">
                        <i class="fas fa-hotel text-xs"></i>
                        قطاع الإقامة والفنادق
                    </h3>
                </div>

                {{-- Hotel --}}
                <div class="group rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm transition-all hover:shadow-md hover:border-indigo-200 backdrop-blur">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            <i class="fas fa-building text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800">عمولة الفنادق</h4>
                            <p class="text-xs text-slate-500">الفنادق، المنتجعات، والبيوت</p>
                        </div>
                    </div>
                    <div class="relative">
                        <input type="number" name="hotel_commission" step="0.01" value="{{ $settings->hotel_commission }}"
                               class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 px-4 py-3.5 text-lg font-bold text-slate-900 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-100 transition-all">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">%</div>
                    </div>
                </div>

                {{-- Apartment --}}
                <div class="group rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm transition-all hover:shadow-md hover:border-sky-200 backdrop-blur">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-50 text-sky-600 group-hover:bg-sky-600 group-hover:text-white transition-colors">
                            <i class="fas fa-house-user text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800">عمولة الشقق</h4>
                            <p class="text-xs text-slate-500">الشقق الفندقية والسكنية</p>
                        </div>
                    </div>
                    <div class="relative">
                        <input type="number" name="apartment_commission" step="0.01" value="{{ $settings->apartment_commission }}"
                               class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 px-4 py-3.5 text-lg font-bold text-slate-900 focus:border-sky-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-sky-100 transition-all">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">%</div>
                    </div>
                </div>

                {{-- Transport Section --}}
                <div class="lg:col-span-3 mt-4">
                    <h3 class="flex items-center gap-2 mb-4 text-sm font-bold uppercase tracking-wider text-slate-400">
                        <i class="fas fa-car text-xs"></i>
                        قطاع النقل والمواصلات
                    </h3>
                </div>

                {{-- Car Hour --}}
                <div class="group rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm transition-all hover:shadow-md hover:border-emerald-200 backdrop-blur">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800">سيارات (ساعة)</h4>
                            <p class="text-xs text-slate-500">خدمة النقل بنظام الساعات</p>
                        </div>
                    </div>
                    <div class="relative">
                        <input type="number" name="car_hour_commission" step="0.01" value="{{ $settings->car_hour_commission }}"
                               class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 px-4 py-3.5 text-lg font-bold text-slate-900 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-100 transition-all">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">%</div>
                    </div>
                </div>

                {{-- Car Day --}}
                <div class="group rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm transition-all hover:shadow-md hover:border-amber-200 backdrop-blur">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                            <i class="fas fa-calendar-day text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800">سيارات (يوم)</h4>
                            <p class="text-xs text-slate-500">خدمة النقل بنظام الأيام</p>
                        </div>
                    </div>
                    <div class="relative">
                        <input type="number" name="car_day_commission" step="0.01" value="{{ $settings->car_day_commission }}"
                               class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 px-4 py-3.5 text-lg font-bold text-slate-900 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-amber-100 transition-all">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">%</div>
                    </div>
                </div>

               {{-- Activities Section --}}
                <div class="lg:col-span-3 mt-4">
                    <h3 class="flex items-center gap-2 mb-4 text-sm font-bold uppercase tracking-wider text-slate-400">
                        <i class="fas fa-star text-xs"></i>
                        قطاع الأنشطة والفعاليات
                    </h3>
                </div>

                
                {{-- Bus --}}
                <div class="group rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm transition-all hover:shadow-md hover:border-blue-200 backdrop-blur">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <i class="fas fa-bus text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800">عمولة الحافلات</h4>
                            <p class="text-xs text-slate-500">تذاكر وطلبات الحافلات</p>
                        </div>
                    </div>
                    <div class="relative">
                        <input type="number" name="bus_commission" step="0.01" value="{{ $settings->bus_commission }}"
                               class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 px-4 py-3.5 text-lg font-bold text-slate-900 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-100 transition-all">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">%</div>
                    </div>
                </div>


                {{-- Activity --}}
                <div class="group rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm transition-all hover:shadow-md hover:border-rose-200 backdrop-blur">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 group-hover:bg-rose-600 group-hover:text-white transition-colors">
                            <i class="fas fa-ticket text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800">الأنشطة والفعاليات</h4>
                            <p class="text-xs text-slate-500">تذاكر الفعاليات والسياحة</p>
                        </div>
                    </div>
                    <div class="relative">
                        <input type="number" name="activity_commission" step="0.01" value="{{ $settings->activity_commission }}"
                               class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 px-4 py-3.5 text-lg font-bold text-slate-900 focus:border-rose-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-rose-100 transition-all">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">%</div>
                    </div>
                </div>
            </div>

            <div class="mt-10 flex justify-end gap-3 sticky bottom-4 z-10">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-2xl bg-slate-900 px-10 py-4 text-sm font-bold text-white shadow-2xl shadow-slate-900/40 transition-all hover:bg-slate-800 hover:scale-105 active:scale-95 focus:ring-4 focus:ring-slate-900/10">
                    <i class="fas fa-save opacity-50"></i>
                    حفظ كافة الإعدادات
                </button>
            </div>
        </form>
    </div>
@endsection
