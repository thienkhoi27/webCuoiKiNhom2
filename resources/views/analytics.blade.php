<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="favicon.svg" type="image/x-icon">
    @vite('resources/css/app.css')
    <title>Phân tích</title>
</head>

<body class="h-full overflow-hidden text-[#222831] bg-[#e8a29b]">
    {{-- container --}}
    <div class="flex h-full overflow-hidden">

        {{-- sidebar --}}
        <x-sidebar.nav/>

        {{-- main --}}
        <div class="w-full md:pl-0 md:pr-6 md:py-10 overflow-hidden h-full">
            {{-- NOTE: đổi overflow-y-auto -> overflow-hidden --}}
            <div class="flex flex-col bg-neutral-50 h-full md:rounded-3xl px-6 md:px-10 py-8 overflow-hidden no-scrollbar">

                {{-- headings --}}
                <div class="flex items-center justify-between bg-neutral-50" id="heading">
                    <div class="bg-neutral-50 fixed md:static w-full z-10">
                        <h1 class="text-2xl md:text-3xl font-extrabold py-5 md:py-0">{{ $page }}</h1>
                    </div>

                    {{-- search --}}
                    <div class="w-full md:w-auto">
                        <form action="{{ route('analytics') }}" method="GET">
                            <div class="inline-flex items-center gap-2 bg-[#EEEEEE] px-4 py-2 rounded-3xl w-4/5 md:w-auto">
                                <label for="search" class="bg-transparent">
                                    <!-- icon -->
                                </label>

                                <input
                                    type="text"
                                    name="search"
                                    id="search"
                                    value="{{ request('search') }}"
                                    placeholder="Tìm kiếm"
                                    class="bg-transparent focus:outline-none text-md"
                                >

                                <button
                                    type="submit"
                                    class="absolute w-px h-px p-0 -m-px overflow-hidden whitespace-nowrap border-0"
                                >
                                </button>
                            </div>
                        </form>

                    </div>
                </div>

                {{-- content wrapper: phải có flex-1 + min-h-0 để list scroll đúng --}}
                <div class="flex-1 min-h-0 flex flex-col">
                    <h1 class="mt-24 md:mt-10 text-lg md:text-xl font-semibold">Lịch sử thu/chi</h1>

                    {{-- CHỈ KHUNG NÀY ĐƯỢC SCROLL --}}
                    <div class="mt-6 flex-1 min-h-0 flex flex-col gap-4 overflow-y-auto no-scrollbar transaction-container">
                        @if (count($transactions) == 0)
                            <span class="mt-10 text-md font-semibold text-center text-gray-500 w-full">Chưa có giao dịch nào</span>
                        @else
                            @foreach ($transactions as $t)
                                @php
                                    $isIncome = ($t->type ?? 'expense') === 'income';

                                    $rowBg   = $isIncome ? 'bg-emerald-50 border-emerald-100' : 'bg-orange-50 border-orange-100';
                                    $iconBg  = $isIncome ? 'bg-emerald-100 text-emerald-700' : 'bg-orange-100 text-orange-700';
                                    $moneyCl = $isIncome ? 'text-emerald-600' : 'text-orange-600';
                                    $sign    = $isIncome ? '+' : '-';

                                    $iconUrl = (!$isIncome && !empty($t->category?->icon_path)) ? Storage::url($t->category->icon_path) : null;
                                    $href = url('expense/' . $t->id);
                                @endphp

                                <a href="{{ $href }}" class="block">
                                    <div class="w-full flex items-center justify-between rounded-2xl p-5 border {{ $rowBg }}">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-2xl overflow-hidden flex items-center justify-center {{ $iconBg }}">
                                                @if($iconUrl)
                                                    <img src="{{ $iconUrl }}" class="w-full h-full object-cover" alt="">
                                                @else
                                                    <span class="text-lg font-bold">{{ $isIncome ? '↑' : '↓' }}</span>
                                                @endif
                                            </div>

                                            <div>
                                                <div class="font-semibold">{{ $t->expense }}</div>
                                                <div class="text-sm text-gray-500">
                                                    {{ \Carbon\Carbon::parse($t->date)->format('d M Y') }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="font-bold text-lg {{ $moneyCl }}">
                                            {{ $sign }} {{ number_format($t->total, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>

            </div>
        </div>

    </div>

    @vite('resources/js/jquery-3.7.1.min.js')
    @vite('resources/js/app.js')
    @vite('resources/js/analytics.js')
    <script>
    document.addEventListener('DOMContentLoaded', () => {
    const el = document.getElementById('search');
    if (!el) return;

    el.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
        e.preventDefault();
        el.form?.submit();
        }
    });
    });
    </script>

</body>
</html>
