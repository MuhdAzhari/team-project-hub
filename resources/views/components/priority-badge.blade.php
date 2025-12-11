@props(['priority'])

@php
    $value = strtolower($priority ?? '');

    // Default styles
    $bg   = 'bg-gray-100';
    $text = 'text-gray-800';

    switch ($value) {
        case 'low':
            $bg   = 'bg-emerald-100';
            $text = 'text-emerald-800';
            break;

        case 'medium':
            $bg   = 'bg-amber-100';
            $text = 'text-amber-800';
            break;

        case 'high':
            $bg   = 'bg-rose-100';
            $text = 'text-rose-800';
            break;
    }

    $label = ucfirst($value);
@endphp

<span {{ $attributes->merge([
    'class' => "inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium $bg $text",
]) }}>
    {{ $label ?: '-' }}
</span>
