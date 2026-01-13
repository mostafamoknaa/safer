@extends('layouts.admin')

@section('title', 'إرسال إشعار جديد')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    إرسال إشعار جديد
                </h1>
            </div>
            <p class="text-sm text-gray-600 mr-14">قم بإنشاء وإرسال إشعار للمستخدمين</p>
        </div>

        <!-- Main Form Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <form action="{{ route('admin.notifications.store') }}" method="POST">
                @csrf
                
                <div class="p-8 space-y-6">
                    <!-- Send Type Toggle -->
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-6 border border-indigo-100">
                        <div class="flex items-start gap-4">
                            <div class="flex items-center h-6">
                                <input type="checkbox" 
                                       id="send_to_all" 
                                       name="send_to_all" 
                                       value="1"
                                       class="w-5 h-5 text-indigo-600 bg-white border-gray-300 rounded focus:ring-indigo-500 focus:ring-2 cursor-pointer transition-all">
                            </div>
                            <div class="flex-1">
                                <label for="send_to_all" class="block font-semibold text-gray-900 mb-1 cursor-pointer">
                                    إرسال لجميع المستخدمين
                                </label>
                                <p class="text-sm text-gray-600">عند تفعيل هذا الخيار، سيتم إرسال الإشعار لجميع المستخدمين المسجلين في النظام</p>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-white rounded-lg border border-indigo-200">
                                    <svg class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                    </svg>
                                    <span class="text-xs font-medium text-indigo-700">بث جماعي</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Selection -->
                    <div id="user_select" class="transition-all duration-300">
                        <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            اختيار المستخدم
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <select id="user_id" 
                                    name="user_id"
                                    class="block w-full pr-10 pl-4 py-3 text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all @error('user_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                <option value="">اختر المستخدم</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('user_id')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Notification Type -->
                    <div>
                        <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">
                            نوع الإشعار
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                            </div>
                            <select id="type" 
                                    name="type" 
                                    required
                                    class="block w-full pr-10 pl-4 py-3 text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all @error('type') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                <option value="">اختر النوع</option>
                                <option value="general" {{ old('type') == 'general' ? 'selected' : '' }}>📢 عام</option>
                                <option value="booking" {{ old('type') == 'booking' ? 'selected' : '' }}>📅 حجز</option>
                                <option value="payment" {{ old('type') == 'payment' ? 'selected' : '' }}>💳 دفع</option>
                                <option value="promotion" {{ old('type') == 'promotion' ? 'selected' : '' }}>🎁 عرض</option>
                                <option value="reminder" {{ old('type') == 'reminder' ? 'selected' : '' }}>⏰ تذكير</option>
                            </select>
                        </div>
                        @error('type')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                            عنوان الإشعار
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <input type="text" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}" 
                                   required
                                   placeholder="أدخل عنوان الإشعار"
                                   class="block w-full pr-10 pl-4 py-3 text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all @error('title') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                        </div>
                        @error('title')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Message -->
                    <div>
                        <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">
                            محتوى الرسالة
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <textarea id="message" 
                                      name="message" 
                                      rows="5" 
                                      required
                                      placeholder="اكتب محتوى الإشعار هنا..."
                                      class="block w-full px-4 py-3 text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-none @error('message') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">{{ old('message') }}</textarea>
                            <div class="absolute bottom-3 left-3 text-xs text-gray-400 select-none pointer-events-none">
                                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                الحد الأدنى 10 أحرف
                            </div>
                        </div>
                        @error('message')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Preview Card -->
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <h3 class="text-sm font-semibold text-gray-700">معاينة الإشعار</h3>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900" id="preview-title">عنوان الإشعار</p>
                                    <p class="text-sm text-gray-600 mt-1" id="preview-message">محتوى الإشعار سيظهر هنا</p>
                                    <p class="text-xs text-gray-400 mt-2">الآن</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="bg-gray-50 px-8 py-6 border-t border-gray-200 flex items-center justify-between gap-4">
                    <a href="{{ route('admin.notifications.index') }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-white text-gray-700 font-medium rounded-lg border border-gray-300 hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm hover:shadow">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        إلغاء
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 font-medium rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        إرسال الإشعار
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Toggle user selection based on send_to_all checkbox
document.getElementById('send_to_all').addEventListener('change', function() {
    const userSelect = document.getElementById('user_select');
    const userField = document.getElementById('user_id');
    
    if (this.checked) {
        userSelect.style.opacity = '0.5';
        userSelect.style.pointerEvents = 'none';
        userField.required = false;
        userField.disabled = true;
    } else {
        userSelect.style.opacity = '1';
        userSelect.style.pointerEvents = 'auto';
        userField.required = true;
        userField.disabled = false;
    }
});

// Live preview functionality
document.getElementById('title').addEventListener('input', function() {
    const previewTitle = document.getElementById('preview-title');
    previewTitle.textContent = this.value || 'عنوان الإشعار';
});

document.getElementById('message').addEventListener('input', function() {
    const previewMessage = document.getElementById('preview-message');
    previewMessage.textContent = this.value || 'محتوى الإشعار سيظهر هنا';
});
</script>
@endsection