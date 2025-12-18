<!doctype html>
<html lang="vi">
<head>
    <meta charset="UTF-8">

    <style>
        @page { margin: 18px; }

        @font-face {
            font-family: "NotoSans";
            font-style: normal;
            font-weight: 400;
            src: url("{{ public_path('fonts/NotoSans-Regular.ttf') }}") format("truetype");
        }
        @font-face {
            font-family: "NotoSans";
            font-style: normal;
            font-weight: 700;
            src: url("{{ public_path('fonts/NotoSans-Bold.ttf') }}") format("truetype");
        }

        * { box-sizing: border-box; }
        body {
            font-family: "NotoSans", sans-serif;
            color: #111827;
            font-size: 12px;
        }

        .wrap { padding: 0; }

        .header { margin-bottom: 12px; }
        .brand { font-size: 18px; font-weight: 700; letter-spacing: .2px; margin:0 0 6px; }
        .muted { color:#6B7280; font-size: 11px; line-height: 1.45; }

        /* Cards - dùng TABLE để DomPDF render chuẩn */
        .cards-table { width:100%; margin: 14px 0 12px; border-collapse: separate; border-spacing: 10px 0; }
        .card {
            padding: 12px;
            border-radius: 12px;
            border: 1px solid #E5E7EB;
        }
        .card .label { font-weight: 700; font-size: 12px; margin-bottom: 6px; color:#374151; }
        .card .value { font-size: 18px; font-weight: 700; }

        .income { background:#ECFDF5; border-color:#A7F3D0; }
        .income .value { color:#059669; }
        .expense { background:#FFF7ED; border-color:#FDBA74; }
        .expense .value { color:#EA580C; }
        .net { background:#EEF2FF; border-color:#C7D2FE; }
        .net .value { color:#4338CA; }

        table.list { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .list th, .list td { padding: 10px 8px; border-bottom: 1px solid #E5E7EB; vertical-align: middle; }
        .list th { text-align:left; background:#F9FAFB; font-weight: 700; color:#374151; font-size: 11px; }
        .right { text-align:right; }

        .badge { display:inline-block; padding: 4px 8px; border-radius: 999px; font-weight: 700; font-size: 10px; }
        .b-income { background:#D1FAE5; color:#065F46; }
        .b-expense { background:#FFEDD5; color:#9A3412; }

        .money-income { color:#059669; font-weight: 700; }
        .money-expense { color:#EA580C; font-weight: 700; }

        .foot { margin-top: 12px; color:#6B7280; font-size: 10.5px; }
    </style>
</head>

<body>
@php
    $fmt = function($v) {
        return number_format((int)$v, 0, ',', '.') . ' ₫';
    };
@endphp

<div class="wrap">
    <div class="header">
        <div class="brand">BÁO CÁO THU / CHI</div>
        <div class="muted">
            Tài khoản: <b>{{ $user }}</b><br>
            Từ <b>{{ $fromDate }}</b> đến <b>{{ $toDate }}</b><br>
            Ngày xuất: <b>{{ $printedAt }}</b>
        </div>
    </div>

    <table class="cards-table">
        <tr>
            <td class="card expense" width="33.33%">
                <div class="label">Tổng chi</div>
                <div class="value">- {{ $fmt($totalExpense ?? 0) }}</div>
            </td>

            <td class="card income" width="33.33%">
                <div class="label">Tổng thu</div>
                <div class="value">+ {{ $fmt($totalIncome ?? 0) }}</div>
            </td>

            <td class="card net" width="33.33%">
                <div class="label">Chênh lệch (Thu - Chi)</div>
                <div class="value">
                    {{ ($net ?? 0) >= 0 ? '+' : '-' }} {{ $fmt(abs($net ?? 0)) }}
                </div>
            </td>
        </tr>
    </table>

    <table class="list">
        <thead>
            <tr>
                <th width="14%">Ngày</th>
                <th width="10%">Loại</th>
                <th width="22%">Danh mục</th>
                <th>Mô tả</th>
                <th class="right" width="18%">Số tiền</th>
            </tr>
        </thead>
        <tbody>
        @forelse($transactions as $t)
            @php
                $isIncome = ($t->type === 'income');
                $categoryName = $isIncome ? '—' : ($t->category_name ?? '—');
            @endphp

            <tr>
                <td>{{ \Carbon\Carbon::parse($t->date)->format('d/m/Y') }}</td>
                <td>
                    <span class="badge {{ $isIncome ? 'b-income' : 'b-expense' }}">
                        {{ $isIncome ? 'THU' : 'CHI' }}
                    </span>
                </td>
                <td>{{ $categoryName }}</td>
                <td>{{ $t->expense }}</td>
                <td class="right {{ $isIncome ? 'money-income' : 'money-expense' }}">
                    {{ $isIncome ? '+' : '-' }} {{ $fmt($t->total) }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="muted">Không có giao dịch trong khoảng thời gian đã chọn.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="foot">
        Ghi chú: Báo cáo được tạo tự động từ hệ thống thuộc về nhóm 2.
    </div>
</div>
</body>
</html>
