<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')
  <title>Spendly</title>
</head>
<body class="h-screen text-[#222831] bg-[#e8a29b]">

<div class="flex h-full">
  <x-sidebar.nav/>

  <div class="h-full w-full md:pr-6 md:py-10 overflow-hidden">
    <div class="flex flex-col bg-neutral-50 h-full md:rounded-3xl px-6 md:px-10 py-8 overflow-y-auto no-scrollbar">
      <div class="bg-neutral-50 fixed md:static w-full pt-10 md:pt-0">
        <h1 class="text-2xl md:text-3xl font-extrabold py-5 md:py-0">{{ $page }}</h1>
      </div>

      @if(session('success'))
        <x-alerts.success-alert class="mt-4 bg-teal-500" status="Success">{{ session('success') }}</x-alerts.success-alert>
      @endif

      <div class="mt-20 md:mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Create category --}}
        <div class="border-2 border-[#EEEEEE] rounded-2xl p-6">
          <h2 class="text-lg font-bold mb-4">Tạo danh mục</h2>

          <form action="/categories" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
            @csrf

            <x-forms.form type="text" required="">
                <x-slot:label>Tên danh mục</x-slot:label>
                <x-slot:id>name</x-slot:id>
            </x-forms.form>


            <div>
              <label class="text-sm font-semibold block">Ảnh danh mục</label>
              <input type="file" name="icon"
                     class="mt-2 p-2 rounded-xl bg-[#EEEEEE] w-full text-sm"
                     accept="image/png,image/jpeg,image/webp">
            </div>

            <button class="mt-2 bg-[#222831] text-white rounded-3xl px-4 py-2 font-semibold">
              Tạo danh mục
            </button>
          </form>
        </div>

        {{-- List categories + budget --}}
        <div class="border-2 border-[#EEEEEE] rounded-2xl p-6">
          <h2 class="text-lg font-bold mb-4">Danh sách danh mục & hạn mức</h2>

          @if(count($categories) == 0)
            <p class="text-gray-500 font-semibold">Chưa có danh mục.</p>
          @else
            <div class="flex flex-col gap-4">
              @foreach($categories as $c)
                <div class="border-2 border-[#EEEEEE] rounded-2xl p-4 flex gap-4 items-center">
                  <div class="w-12 h-12 rounded-xl bg-[#EEEEEE] overflow-hidden flex items-center justify-center">
                    @if($c->icon_path)
                      <img src="{{ Storage::url($c->icon_path) }}" class="w-full h-full object-cover" />
                    @else
                      <span class="text-sm font-bold">#</span>
                    @endif
                  </div>

                  <div class="flex-1">
                    <div class="font-bold">{{ $c->name }}</div>
                    <div class="text-sm text-gray-600">Hạn mức tháng</div>

                    <form action="/categories/{{ $c->id }}/budget" method="POST" class="mt-2 flex gap-2">
                      @csrf
                      <input type="month" name="month" value="{{ date('Y-m', strtotime($month)) }}"
                             class="p-2 rounded-xl bg-[#EEEEEE] text-sm w-[140px]">
                      <input type="number" name="amount" min="0" value="{{ (int)$c->budget_amount }}"
                             class="p-2 rounded-xl bg-[#EEEEEE] text-sm flex-1" placeholder="VD: 2000000">
                      <button class="px-4 py-2 rounded-xl bg-[#222831] text-white text-sm font-semibold">
                        Lưu
                      </button>
                    </form>
                  </div>
                </div>
              @endforeach
            </div>
          @endif

        </div>
      </div>

    </div>
  </div>
</div>

@vite('resources/js/app.js')
@vite('resources/js/alert.js')
</body>
</html>
