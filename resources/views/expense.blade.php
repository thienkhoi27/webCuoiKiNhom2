<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{ asset('favicon.svg') }}" type="image/x-icon">
    @vite('resources/css/app.css')
    <title>Thu/Chi</title>
</head>
<body class="h-screen text-[#222831] bg-[#e8a29b]">


    {{-- container --}}
    <div class="flex h-full">

        {{-- sidebar --}}
        <x-sidebar.nav/>

        {{-- main --}}
        <div class="h-full w-full md:pl-0 md:pr-6 md:py-10 overflow-hidden">
            <div class="flex flex-col bg-neutral-50 h-full md:rounded-3xl px-6 md:px-10 py-8 overflow-y-auto lg:overflow-hidden no-scrollbar">
                <div class="flex items-center justify-between " id="heading">
                    <div class="bg-neutral-50 fixed md:static w-full pt-10 md:pt-0 z-0">
                        <h1 class="text-2xl md:text-3xl font-extrabold py-5 md:py-0">{{ $page }}</h1>
                    </div>
                    
                </div>

                {{-- content --}}
                <div class="mt-20 md:mt-6 mx-auto w-full md:w-2/3 lg:w-1/3">
                    <form action="/edit-expense/{{ $transactions['id'] }}" method="POST" class="flex flex-col gap-4">
                        @csrf
                        
                        <x-forms.form type="text">
                            <x-slot:label>
                                Tên chi phí
                            </x-slot:label>

                            <x-slot:id>
                                expense
                            </x-slot:id>

                            <x-slot:value>
                                {{ $transactions['expense'] }}
                            </x-slot:value>
                        </x-forms.form>

                        <x-forms.form type="number">
                            <x-slot:label>
                                Chi
                            </x-slot:label>

                            <x-slot:id>
                                total
                            </x-slot:id>

                            <x-slot:value>
                                {{ $transactions['total'] }}
                            </x-slot:value>
                        </x-forms.form>

                        <x-forms.form type="date" value="{{ date('Y-m-d') }}">
                            <x-slot:label>
                                Ngày
                            </x-slot:label>

                            <x-slot:id>
                                date
                            </x-slot:id>

                            <x-slot:value>
                                {{ $transactions['date'] }}
                            </x-slot:value>
                        </x-forms.form>

                        @if(($transactions['type'] ?? 'expense') === 'expense')
                            <div class="mt-2">
                                <label class="text-md font-semibold block">Danh mục</label>
                                <select name="category_id" class="mt-2 p-3 rounded-lg bg-[#EEEEEE] w-full text-sm">
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach($categories as $c)
                                        <option value="{{ $c->id }}" {{ (int)($transactions['category_id'] ?? 0) === (int)$c->id ? 'selected' : '' }}>
                                            {{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif


                        <div class="flex justify-between gap-4 items-center">
                            <a href="/delete-expense/{{ $transactions['id'] }}" class="mt-6 bg-red-600 text-white rounded-3xl px-4 py-2 font-semibold w-full text-center text-sm lg:text-base">Xóa</a>
                            <button type="submit" class="mt-6 bg-[#222831] text-white rounded-3xl px-4 py-2 font-semibold w-full text-sm lg:text-base">Chỉnh sửa</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

    </div>

    @vite('resources/js/app.js')
</body>
</html>
