@props([
    'isIncome' => false,
    'iconPath' => null,   // đường dẫn trong storage (VD: categories/xxx.png)
])

@php
    $bg      = $isIncome ? 'bg-emerald-50' : 'bg-orange-50';
    $border  = $isIncome ? 'border-emerald-100' : 'border-orange-100';
    $amount  = $isIncome ? 'text-emerald-600' : 'text-orange-600';
    $iconBg  = $isIncome ? 'bg-emerald-100/70' : 'bg-orange-100/70';
@endphp

<div class="w-full flex items-center justify-between rounded-2xl p-4 border {{ $bg }} {{ $border }}">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl overflow-hidden flex items-center justify-center {{ $iconBg }} shrink-0">
            @if(!empty($iconPath))
                <img
                    src="{{ Storage::url($iconPath) }}"
                    class="w-full h-full object-cover"
                    alt="icon"
                    loading="lazy"
                >
            @else
                {{-- icon fallback: thu = mũi tên lên, chi = mũi tên xuống --}}
                @if($isIncome)
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l10-10m0 0H9m8 0v8"/>
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7l10 10m0 0h-8m8 0V9"/>
                    </svg>
                @endif
            @endif
        </div>

        <div>
            <div class="font-semibold">
                {{ $expense }}
            </div>
            <div class="text-sm text-gray-500">
                {{ $date }}
            </div>
        </div>
    </div>

    <div class="font-bold text-lg {{ $amount }}">
        {{ $isIncome ? '+' : '-' }} {{ $total }}
    </div>
</div>
