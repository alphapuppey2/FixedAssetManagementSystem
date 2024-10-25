@props(['status'])

@if ($status)
<div {{ $attributes->merge(['class' => 'font-medium p-4 text-sm text-green-600 bg-green-200 rounded-lg border border-green-900 dark:text-green-700']) }}>
    {{ $status }}
</div>
@endif
