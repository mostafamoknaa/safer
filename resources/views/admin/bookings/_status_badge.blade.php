@props(['status'])

@php
    $classes = match($status) {
        'pending' => 'bg-amber-50 text-amber-700 border-amber-200/50',
        'confirmed' => 'bg-emerald-50 text-emerald-700 border-emerald-200/50',
        'completed' => 'bg-blue-50 text-blue-700 border-blue-200/50',
        'cancelled' => 'bg-rose-50 text-rose-700 border-rose-200/50',
        'checked_in' => 'bg-indigo-50 text-indigo-700 border-indigo-200/50',
        'checked_out' => 'bg-slate-50 text-slate-700 border-slate-200/50',
        default => 'bg-slate-50 text-slate-700 border-slate-200/50'
    };

    $label = match($status) {
        'pending' => __('admin.bookings.badges.pending'),
        'confirmed' => __('admin.bookings.badges.confirmed'),
        'completed' => __('admin.bookings.badges.completed'),
        'cancelled' => __('admin.bookings.badges.cancelled'),
        'checked_in' => 'تم تسجيل الوصول',
        'checked_out' => 'تم تسجيل المغادرة',
        default => $status
    };
@endphp

<span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-bold transition-colors {{ $classes }}">
    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
    {{ $label }}
</span>
