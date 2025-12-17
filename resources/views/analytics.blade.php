<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="favicon.svg" type="image/x-icon">
    @vite('resources/css/app.css')
    <title>Spendly</title>
</head>

<body class="min-h-screen text-[#222831] bg-[#e8a29b]">
    {{-- container --}}
    <div class="flex min-h-screen">
        {{-- sidebar --}}
        <x-sidebar.nav/>

        {{-- main --}}
        <div class="w-full md:pl-0 md:pr-6 md:py-10 overflow-hidden">
            <div class="flex flex-col bg-neutral-50 min-h-[calc(100vh-0px)] md:min-h-full md:rounded-3xl px-6 md:px-10 py-8 overflow-y-auto lg:overflow-hidden no-scrollbar">
                {{-- headings --}}
                <div class="flex items-center justify-between bg-neutral-50" id="heading">
                    <div class="bg-neutral-50 fixed md:static w-full z-0">
                        <h1 class="text-2xl md:text-3xl font-extrabold py-5 md:py-0">{{ $page }}</h1>
                    </div>

                    {{-- search --}}
                    <div class="w-full md:w-auto">
                        <form action="" method="">
                            <div class="inline-flex items-center gap-2 bg-[#EEEEEE] px-4 py-2 rounded-3xl w-4/5 md:w-auto">
                                <label for="search" class="bg-transparent">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="size-5 text-[#222831]">
                                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m21 21l-4.343-4.343m0 0A8 8 0 1 0 5.343 5.343a8 8 0 0 0 11.314 11.314"/>
                                    </svg>
                                </label>
                                <input type="text" name="search" id="search" placeholder="Search" class="bg-transparent focus:outline-none text-md">
                            </div>
                        </form>
                    </div>
                </div>

                {{-- content --}}
                <h1 class="mt-10 text-lg md:text-xl font-semibold">Lịch sử thu/chi</h1>

                <div class="mt-6 flex flex-col gap-4 overflow-y-auto no-scrollbar transaction-container">
                    @if (count($transactions) == 0)
                        <span class="mt-10 text-md font-semibold text-center text-gray-500 w-full">No transactions yet</span>
                    @else
                        @foreach ($transactions as $t)
                            @php
                                $isIncome = ($t->type ?? 'expense') === 'income';

                                $rowBg   = $isIncome ? 'bg-emerald-50 border-emerald-100' : 'bg-orange-50 border-orange-100';
                                $iconBg  = $isIncome ? 'bg-emerald-100 text-emerald-700' : 'bg-orange-100 text-orange-700';
                                $moneyCl = $isIncome ? 'text-emerald-600' : 'text-orange-600';
                                $sign    = $isIncome ? '+' : '-';

                                // icon: chi -> lấy category icon; thu -> icon mặc định
                                $iconUrl = (!$isIncome && !empty($t->category?->icon_path)) ? Storage::url($t->category->icon_path) : null;

                                // link: thu/chi đều dùng chung trang detail "expense/{id}" nếu bạn chưa tách route
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

    @vite('resources/js/jquery-3.7.1.min.js')
    @vite('resources/js/app.js')
    @vite('resources/js/analytics.js')
</body>
</html>
