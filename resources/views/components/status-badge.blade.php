@props(['status'])

@php
$statusClasses = [
    'pending' => 'bg-yellow-100 text-yellow-800',
    'shortlisted' => 'bg-green-100 text-green-800',
    'rejected' => 'bg-red-100 text-red-800',
];

$class = $statusClasses[$status] ?? 'bg-gray-100 text-gray-800';
@endphp

<span {{ $attributes->merge(['class' => "status-badge px-3 py-1 rounded-full text-sm font-medium {$class}"]) }}>
    {{ ucfirst($status) }}
</span>
