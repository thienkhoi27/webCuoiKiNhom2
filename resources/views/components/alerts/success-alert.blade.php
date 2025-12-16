<div>
    <div {{ $attributes->class(['mt-2 text-sm text-white rounded-lg p-4 opacity-1 trasition duration-[5000ms] ease-in-out']) }} role="alert" id="alert">
        <span class="font-bold">{{ $status }}!</span> {{ $slot }}
    </div>
</div>

