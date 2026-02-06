@props(['status'])

@php
    $classes = match($status) {
        'pending' => 'bg-amber-50 text-amber-700 border-amber-200/50',
        'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200/50',
        'failed' => 'bg-rose-50 text-rose-700 border-rose-200/50',
        default => 'bg-slate-50 text-slate-700 border-slate-200/50'
    };

    $label = match($status) {
        'pending' => __('admin.payments.status.pending'),
        'completed' => __('admin.payments.status.completed'),
        'failed' => __('admin.payments.status.failed'),
        default => $status
    };
@endphp

<span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-bold transition-colors {{ $classes }}">
    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
    {{ $label }}
</span>
