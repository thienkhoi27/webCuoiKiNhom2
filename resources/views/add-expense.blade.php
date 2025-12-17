<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="favicon.svg" type="image/x-icon">
    @vite('resources/css/app.css')
    <title>hihi</title>
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
                    @if ($errors->any())
                        <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 p-4 text-red-700">
                            <div class="font-bold mb-2">Dữ liệu không hợp lệ:</div>
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li class="text-sm font-semibold">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="/transactions" method="POST" class="flex flex-col gap-4">
                        @csrf
                        <div>
                            <label class="font-semibold block">Loại</label>
                            <select name="type" id="type" class="mt-2 p-3 rounded-lg bg-[#EEEEEE] w-full text-sm">
                                <option value="expense" selected>Chi</option>
                                <option value="income">Thu</option>
                            </select>
                        </div>

                        <div class="mt-4" id="categoryWrap">
                            <label class="text-md font-semibold block">Danh mục</label>
                            <select name="category_id" id="category_id" class="mt-2 p-3 rounded-lg bg-[#EEEEEE] w-full text-sm">
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <x-forms.form type="text">
                            <x-slot:label>
                                Tên chi phí
                            </x-slot:label>
                            <x-slot:id>
                                expense
                            </x-slot:id>
                        </x-forms.form>

                        <x-forms.form type="number">
                            <x-slot:label>
                                Chi
                            </x-slot:label>
                            <x-slot:id>
                                total
                            </x-slot:id>
                        </x-forms.form>

                        <x-forms.form type="date" value="{{ date('Y-m-d') }}">
                            <x-slot:label>
                                Ngày
                            </x-slot:label>
                            <x-slot:id>
                                date
                            </x-slot:id>
                        </x-forms.form>

                        <button type="submit" class="mt-6 bg-[#222831] text-white rounded-3xl px-4 py-2 font-semibold">Thêm chi phí</button>
                    </form>
                </div>
            </div>

        </div>

    </div>

    @vite('resources/js/app.js')
    <script>
    const typeEl = document.getElementById('type');
    const wrap = document.getElementById('categoryWrap');
    const cat = document.getElementById('category_id');

    function sync() {
        const isIncome = typeEl.value === 'income';
        wrap.classList.toggle('hidden', isIncome);
        if (isIncome) cat.value = '';
    }
    typeEl.addEventListener('change', sync);
    sync();
    function toggleCategory() {
        const type = document.getElementById('type')?.value;
        const wrap = document.getElementById('categoryWrap');
        const sel  = document.getElementById('category_id');

        if (!wrap || !sel) return;

        if (type === 'income') {
            wrap.classList.add('hidden');
            sel.value = '';
        } else {
            wrap.classList.remove('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('type')?.addEventListener('change', toggleCategory);
        toggleCategory();
    });
    </script>
</body>
</html>