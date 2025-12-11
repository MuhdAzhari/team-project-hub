@props(['status'])

@php
    $value = strtolower($status ?? '');

    // Default styles
    $bg   = 'bg-gray-100';
    $text = 'text-gray-800';

    switch ($value) {
        case 'planned':
            $bg   = 'bg-slate-100';
            $text = 'text-slate-800';
            break;

        case 'active':
            $bg   = 'bg-blue-100';
            $text = 'text-blue-800';
            break;

        case 'on_hold':
            $bg   = 'bg-amber-100';
            $text = 'text-amber-800';
            break;

        case 'completed':
            $bg   = 'bg-emerald-100';
            $text = 'text-emerald-800';
            break;

        case 'todo':
            $bg   = 'bg-sky-100';
            $text = 'text-sky-800';
            break;

        case 'in_progress':
            $bg   = 'bg-indigo-100';
            $text = 'text-indigo-800';
            break;

        case 'done':
            $bg   = 'bg-green-100';
            $text = 'text-green-800';
            break;
    }

    $label = ucfirst(str_replace('_', ' ', $value));
@endphp

<span {{ $attributes->merge([
    'class' => "inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium $bg $text",
]) }}>
    {{ $label ?: '-' }}
</span>
