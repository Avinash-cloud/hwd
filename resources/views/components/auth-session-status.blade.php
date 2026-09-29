@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-[#0369A1]']) }}>
        {{ $status }}
    </div>
@endif
