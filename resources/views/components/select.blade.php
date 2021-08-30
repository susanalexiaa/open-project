@props(['disabled' => false])

@php
    $classes = 'form-input rounded-md shadow-sm';
@endphp

<select {{ $attributes->merge(['class' => $classes]) }} {{ $disabled ? 'disabled' : '' }}>
    {{ $slot }}
</select>
