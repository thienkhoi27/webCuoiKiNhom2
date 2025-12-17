<div>
    <div {{ $attributes->class(['bg-pinkSoft text-[#EEEEEE] rounded-2xl']) }}>
        <span class="{{ $spanClass }}">{{ $title }}</span>

        <h1 class="mt-2 lg:mt-4 {{ $h1Class }} font-bold">
            {{ number_format($total, 0, ',', '.') }}
        </h1>

        <div class="flex justify-between items-center mt-4 lg:mt-6">
            <span class="{{ $spanClass }}">{{ $subtitle }}</span>
            <span class="{{ $spanClass }}">{{ $date }}</span>
        </div>
    </div>
</div>
