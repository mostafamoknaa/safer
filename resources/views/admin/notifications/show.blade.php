@extends('layouts.admin')

@section('title', 'تفاصيل الإشعار')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('admin.notifications.index') }}" 
                   class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-white shadow-md hover:shadow-lg transition-all duration-200 hover:-translate-x-1">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    تفاصيل الإشعار #{{ $notification->id }}
                </h1>
            </div>
            <p class="text-sm text-gray-600 mr-14">عرض جميع معلومات وتفاصيل الإشعار</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Notification Card -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                                محتوى الإشعار
                            </h2>
                            @if($notification->read_at)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-white/20 text-white">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    مقروء
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-yellow-400 text-yellow-900">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    غير مقروء
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <!-- Title -->
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">العنوان</label>
                            <p class="mt-2 text-xl font-bold text-gray-900">{{ $notification->title }}</p>
                        </div>

                        <!-- Message -->
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">الرسالة</label>
                            <div class="mt-2 p-4 bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                                <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $notification->message }}</p>
                            </div>
                        </div>

                        <!-- Additional Data -->
                        @if($notification->data)
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                بيانات إضافية
                            </label>
                            <div class="mt-2 p-4 bg-gray-900 rounded-lg border border-gray-700 overflow-x-auto">
                                <pre class="text-sm text-green-400 font-mono">{{ json_encode($notification->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- User Info Card -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            معلومات المستخدم
                        </h3>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-400 to-pink-600 flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                                {{ substr($notification->user->name ?? 'غ', 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <p class="text-lg font-bold text-gray-900">{{ $notification->user->name ?? 'غير محدد' }}</p>
                                <p class="text-sm text-gray-500">{{ $notification->user->email ?? 'غير محدد' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Details Card -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-cyan-600 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            تفاصيل إضافية
                        </h3>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <!-- Type -->
                        <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                            <span class="text-sm font-medium text-gray-600">نوع الإشعار</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $notification->type }}
                            </span>
                        </div>

                        <!-- Sent Date -->
                        <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                            <span class="text-sm font-medium text-gray-600">تاريخ الإرسال</span>
                            <div class="text-left">
                                <p class="text-sm font-semibold text-gray-900">{{ $notification->created_at->format('Y-m-d') }}</p>
                                <p class="text-xs text-gray-500">{{ $notification->created_at}}</p>
                            </div>
                        </div>

                        <!-- Read Date -->
                        @if($notification->read_at)
                        <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                            <span class="text-sm font-medium text-gray-600">تاريخ القراءة</span>
                            <div class="text-left">
                                <p class="text-sm font-semibold text-gray-900">{{ $notification->read_at }}</p>
                                <p class="text-xs text-gray-500">{{ $notification->read_at }}</p>
                            </div>
                        </div>
                        @endif

                        <!-- Status -->
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600">حالة الإشعار</span>
                            @if($notification->read_at)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    تمت القراءة
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    في الانتظار
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Timeline Card -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            المخطط الزمني
                        </h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="relative space-y-4">
                            <!-- Created -->
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                                        </svg>
                                    </div>
                                    @if($notification->read_at)
                                    <div class="w-0.5 h-12 bg-gradient-to-b from-blue-500 to-green-500"></div>
                                    @endif
                                </div>
                                <div class="flex-1 pt-2">
                                    <p class="text-sm font-semibold text-gray-900">تم الإرسال</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                            </div>

                            <!-- Read -->
                            @if($notification->read_at)
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 pt-2">
                                    <p class="text-sm font-semibold text-gray-900">تمت القراءة</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $notification->read_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 bg-white rounded-2xl shadow-xl border border-gray-200 px-8 py-6 flex items-center justify-between gap-4">
            <a href="{{ route('admin.notifications.index') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                العودة للقائمة
            </a>
            
            <form action="{{ route('admin.notifications.destroy', $notification) }}" 
                  method="POST" 
                  class="inline"
                  onsubmit="return confirm('هل أنت متأكد من حذف هذا الإشعار؟ هذا الإجراء لا يمكن التراجع عنه.');">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-red-600 to-pink-600 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    حذف الإشعار
                </button>
            </form>
        </div>
    </div>
</div>
@endsection