<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="favicon.svg" type="image/x-icon">
    @vite('resources/css/app.css')
    <title>Bảng điều khiển</title>
</head>
<body class="h-screen text-[#222831] bg-[#e8a29b]">

    {{-- container --}}
    <div class="flex h-full">

        {{-- sidebar --}}
        <x-sidebar.nav/>

        {{-- main --}}
        <div class="h-full w-full md:pl-0 md:pr-6 md:py-10 overflow-hidden">
            <div class="flex flex-col bg-neutral-50 h-full md:rounded-3xl px-6 md:px-10 py-8 overflow-y-auto lg:overflow-hidden no-scrollbar">
                {{-- headings --}}
                <div class="flex items-center justify-between " id="heading">
                    <div class="bg-neutral-50 fixed md:static w-full pt-10 md:pt-0 z-0">
                        <h1 class="text-2xl md:text-3xl font-extrabold py-5 md:py-0">{{ $page }}</h1>
                    </div>
                    <div class="hidden md:block">
                        <x-profile-picture.profile-picture>
                            <x-slot:src>
                                {{ Storage::url(session('profilePicture')) }}
                            </x-slot:src>
                        </x-profile-picture.profile-picture>
                    </div>
                </div>

                {{-- alert --}}
                @if (session('success'))
                    <x-alerts.success-alert class="mt-4 {{ session('success') == 'Expense deleted successfully!' ? 'bg-red-500' : 'bg-teal-500' }}">
                        {{ session('success') }}
                    </x-alerts.success-alert>
                @endif

                {{-- content --}}
                <div class="mt-20 lg:mt-10 min-h-[150vh] lg:min-h-0 h-full flex flex-col lg:flex-row gap-6 overflow-hidden">
                    {{-- last transactions --}}
                    <div class="flex flex-col w-full lg:w-[40%] min-h-1/2 lg:min-h-0 h-full border-solid border-2 border-[#EEEEEE] rounded-2xl overflow-hidden">
                        {{-- card --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-6">
                        <x-cards.expense-card
                            class="p-6 bg-gradient-to-br from-amber-400 to-amber-500"
                            h1Class="text-2xl lg:text-3xl"
                            spanClass="text-sm lg:text-base"
                            title="Bạn đã chi"
                            subtitle="Tháng này"
                            :total="$spentThisMonth"
                            date="{{ date('M Y') }}"
                        />

                        <x-cards.expense-card
                            class="p-6 bg-gradient-to-br from-orange-400 to-orange-500"
                            h1Class="text-2xl lg:text-3xl"
                            spanClass="text-sm lg:text-base"
                            title="Bạn đã thu"
                            subtitle="Tháng này"
                            :total="$incomeThisMonth"
                            date="{{ date('M Y') }}"
                        />

                        </div>


                        
                        {{-- transactions --}}
                        <h2 class="mt-4 px-6 text-lg font-bold">Thu/Chi gần nhất</h2>
                        <div class="mt-4 flex flex-col gap-4 px-6 pb-6 overflow-y-auto">
                            @if (count($transactions) == 0)
                                <span class="mt-10 text-md font-semibold text-center text-gray-500">No transactions yet</span>
                            @else
                                @foreach ($transactions as $transaction)
                                    @php
                                        $isIncome = ($transaction->type ?? 'expense') === 'income';
                                        $iconPath = $isIncome ? null : ($transaction->category->icon_path ?? null);
                                    @endphp

                                    <a href="expense/{{ $transaction['id'] }}">
                                        <x-cards.transaction-card :isIncome="$isIncome" :iconPath="$iconPath">
                                            <x-slot:expense>{{ $transaction['expense'] }}</x-slot:expense>
                                            <x-slot:total>{{ number_format($transaction['total'], 0, ',', '.') }}</x-slot:total>
                                            <x-slot:date>{{ date('d M Y', strtotime($transaction['date'])) }}</x-slot:date>
                                        </x-cards.transaction-card>
                                    </a>
                                @endforeach

                            @endif
                        </div>
                    </div>
                    
                    <div class="w-full lg:w-[60%] min-h-1/2 lg:min-h-0 h-full flex flex-col gap-6">
                        {{-- monthly spend --}}
                        <div class="p-6 {{ count($transactions) == 0 ? 'h-1/3' : 'h-max' }} border-solid border-2 border-[#EEEEEE] rounded-2xl">
                            <span class="font-bold text-lg">Các chi phí của bạn</span>
                            <div class="flex gap-6 mt-4 overflow-x-auto no-scrollbar md:pb-2">
                            @if (count($categories) == 0)
                                <span class="mt-10 text-md font-semibold text-center text-gray-500 w-full">
                                Chưa có danh mục. Hãy tạo ở mục Danh mục.
                                </span>
                            @else
                                @php
                                    $colorPalette = [
                                        'bg-gradient-to-br from-emerald-400 to-emerald-500',
                                        'bg-gradient-to-br from-sky-400 to-sky-500',
                                        'bg-gradient-to-br from-violet-400 to-violet-500',
                                        'bg-gradient-to-br from-amber-400 to-amber-500',
                                        'bg-gradient-to-br from-rose-400 to-rose-500',
                                    ];
                                @endphp

                                @foreach($categories as $i => $c)
                                    @php $bg = $colorPalette[$i % count($colorPalette)]; @endphp

                                    <div class="shrink-0 w-[240px] rounded-2xl p-5 text-white shadow-lg shadow-black/10 {{ $bg }}">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 rounded-2xl bg-white/20 overflow-hidden shrink-0 flex items-center justify-center">
                                                @if(!empty($c->icon_path))
                                                    <img src="{{ Storage::url($c->icon_path) }}" alt="{{ $c->name }}" class="w-full h-full object-cover" loading="lazy">
                                                @else
                                                    <span class="text-sm font-bold">#</span>
                                                @endif
                                            </div>

                                            <div class="flex-1">
                                                <div class="font-bold">{{ $c->name }}</div>
                                                <div class="text-xs font-semibold opacity-90">{{ $c->status }}</div>
                                            </div>
                                        </div>

                                        <div class="mt-4 text-sm font-semibold opacity-95">
                                            Đã chi: {{ number_format($c->spent, 0, ',', '.') }}
                                            @if($c->budget > 0)
                                                / {{ number_format($c->budget, 0, ',', '.') }}
                                            @endif
                                        </div>

                                        <div class="mt-3 w-full h-2 rounded-full bg-white/30 overflow-hidden">
                                            <div class="h-full bg-white/90" style="width: {{ $c->percent }}%"></div>
                                        </div>
                                    </div>
                                @endforeach

                            @endif
                            </div>

                        </div>

                        {{-- charts --}}
                        <div class="p-6 h-2/3 flex flex-col border-solid border-2 border-[#EEEEEE] rounded-2xl overflow-hidden">
<<<<<<< HEAD
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-lg">Biểu đồ Thu/Chi</span>
                                {{-- nếu cần chỗ cho legend/filter sau này --}}
                            </div>

                            <div class="mt-4 flex-1 overflow-hidden">
                                @if (count($transactions) == 0)
                                    <span class="mt-10 text-md font-semibold text-center text-gray-500 w-full block">No transactions yet</span>
                                @else
                                    <div id="chartContainer" class="w-full h-full"></div>
                                @endif
                            </div>
                        </div>

=======
    <div class="flex items-center justify-between">
        <span class="font-bold text-lg">Biểu đồ thu/chi</span>
        {{-- nếu cần chỗ cho legend/filter sau này --}}
    </div>

    <div class="mt-4 flex-1 overflow-hidden">
        @if (count($transactions) == 0)
            <span class="mt-10 text-md font-semibold text-center text-gray-500 w-full block">No transactions yet</span>
        @else
            <div id="chartContainer" class="w-full h-full"></div>
        @endif
    </div>
</div>
>>>>>>> 3a235cdf05f97dd9090886e9cad1c5158aaafa91
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    @vite('resources/js/jquery-3.7.1.min.js')
    @vite('resources/js/app.js')
    @vite('resources/js/alert.js')

    @if (count($transactions) > 0)
    <script>
    window.onload = function () {
        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            backgroundColor: "transparent",
            axisX: {
                valueFormatString: "D MMM",
                interval: 3,
                intervalType: "day",
                labelAngle: -45
            },
            axisY: { valueFormatString: "#,###" },
            legend: { verticalAlign: "top", horizontalAlign: "right" },
            data: [
                {
                    type: "spline",
                    name: "Chi",
                    showInLegend: true,
                    markerSize: 0,
                    xValueType: "dateTime",
                    dataPoints: @json($expensePoints, JSON_NUMERIC_CHECK)
                },
                {
                    type: "spline",
                    name: "Thu",
                    showInLegend: true,
                    markerSize: 0,
                    xValueType: "dateTime",
                    dataPoints: @json($incomePoints, JSON_NUMERIC_CHECK)
                }
            ]
        });
        chart.render();
    };
    </script>
    @endif

</body>
</html>
