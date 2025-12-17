<div>
    <div class="border-solid border-2 border-[#EEEEEE] px-4 py-2 rounded-2xl">
        <div class="flex justify-between items-center">
            <div class="">
                <h1 class="text-md lg:text-lg font-bold mb-2">{{ $expense }}</h1>
                <span class="text-sm lg:text-md font-semibold">{{ $date }}</span>
            </div>
            <span class="text-right text-md lg:text-xl font-bold {{ $isIncome ? 'text-emerald-600' : 'text-red-500' }}">
                {{ $isIncome ? '+' : '-' }} {{ $total }}
            </span>

        </div>
    </div>
</div>