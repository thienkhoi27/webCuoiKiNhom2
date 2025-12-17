<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DemoSpendlySeeder extends Seeder
{
    public function run(): void
    {
        // ====== CONFIG ======
        $adminEmail = 'admin@example.com';
        $adminPasswordPlain = 'password';

        // ====== USERS ======
        $usersTable = 'users';
        if (!Schema::hasTable($usersTable)) {
            $this->command?->warn("Không tìm thấy bảng `users`. Bỏ qua seed user.");
            return;
        }

        $userCols = Schema::getColumnListing($usersTable);

        $colEmail   = $this->pickCol($userCols, ['email', 'username', 'user', 'name']);
        $colPass    = $this->pickCol($userCols, ['password', 'pass']);
        $colProfile = $this->pickCol($userCols, ['profilePicture', 'profile_picture', 'avatar', 'avatar_path']);
        $colFull    = $this->pickCol($userCols, ['full_name', 'fullname', 'name', 'display_name']);
        $colRole    = $this->pickCol($userCols, ['role', 'is_admin']);

        $userData = [];
        if ($colEmail)   $userData[$colEmail] = $adminEmail;
        if ($colPass)    $userData[$colPass]  = Hash::make($adminPasswordPlain);
        if ($colProfile) $userData[$colProfile] = 'profiles/admin.png';
        if ($colFull)    $userData[$colFull] = 'Admin';
        if ($colRole) {
            // nếu role là string -> 'admin', nếu boolean -> 1
            $userData[$colRole] = in_array($colRole, ['is_admin'], true) ? 1 : 'admin';
        }

        $this->fillTimestampsIfExist($usersTable, $userData);

        // updateOrInsert theo email/username
        if (!$colEmail) {
            $this->command?->warn("Không dò được cột email/username trong users. Bỏ qua seed user.");
            return;
        }

        DB::table($usersTable)->updateOrInsert(
            [$colEmail => $adminEmail],
            $userData
        );

        $adminId = $this->getIdBy($usersTable, [$colEmail => $adminEmail]);

        // ====== CATEGORIES ======
        $categoriesTable = 'categories';
        if (!Schema::hasTable($categoriesTable)) {
            $this->command?->warn("Không tìm thấy bảng `categories`. Bỏ qua seed categories/transactions/budgets.");
            return;
        }

        $catCols = Schema::getColumnListing($categoriesTable);
        $colCatUserStr = $this->pickCol($catCols, ['user', 'username', 'email']);
        $colCatUserId  = $this->pickCol($catCols, ['user_id', 'owner_id']);
        $colCatName    = $this->pickCol($catCols, ['name', 'category', 'title']);
        $colCatIcon    = $this->pickCol($catCols, ['icon_path', 'icon', 'image', 'image_path']);

        if (!$colCatName) {
            $this->command?->warn("Không dò được cột tên danh mục (name/category/title) trong categories.");
            return;
        }

        $categories = [
            ['key' => 'food',          'label' => 'Ăn/Uống',        'icon' => 'categories/food.png'],
            ['key' => 'transport',     'label' => 'Di chuyển',      'icon' => 'categories/transport.png'],
            ['key' => 'travel',        'label' => 'Du lịch',        'icon' => 'categories/travel.png'],
            ['key' => 'shopping',      'label' => 'Mua sắm',        'icon' => 'categories/shopping.png'],
            ['key' => 'education',     'label' => 'Giáo dục',       'icon' => 'categories/education.png'],
            ['key' => 'bills',         'label' => 'Hóa đơn',        'icon' => 'categories/bills.png'],
            ['key' => 'health',        'label' => 'Sức khỏe',       'icon' => 'categories/health.png'],
            ['key' => 'entertainment', 'label' => 'Giải trí',       'icon' => 'categories/entertainment.png'],
            ['key' => 'home',          'label' => 'Nhà cửa',        'icon' => 'categories/home.png'],
            ['key' => 'other',         'label' => 'Khác',           'icon' => 'categories/other.png'],
        ];

        $categoryIds = [];
        foreach ($categories as $c) {
            $row = [
                $colCatName => $c['label'],
            ];

            if ($colCatIcon) {
                $row[$colCatIcon] = $c['icon'];
            }

            if ($colCatUserStr) {
                $row[$colCatUserStr] = $adminEmail; // đúng với code bạn đang where('user', session('username'))
            } elseif ($colCatUserId && $adminId) {
                $row[$colCatUserId] = $adminId;
            }

            $this->fillTimestampsIfExist($categoriesTable, $row);

            // unique key: theo (user,name) nếu có user, còn không thì theo name
            $unique = [$colCatName => $c['label']];
            if ($colCatUserStr) $unique = [$colCatUserStr => $adminEmail, $colCatName => $c['label']];
            if ($colCatUserId && $adminId) $unique = [$colCatUserId => $adminId, $colCatName => $c['label']];

            DB::table($categoriesTable)->updateOrInsert($unique, $row);

            $categoryIds[$c['key']] = $this->getIdBy($categoriesTable, $unique);
        }

        // ====== CATEGORY BUDGETS ======
        $budgetsTable = 'category_budgets';
        $monthKey = Carbon::now()->startOfMonth()->toDateString(); // YYYY-mm-01

        if (Schema::hasTable($budgetsTable)) {
            $bCols = Schema::getColumnListing($budgetsTable);
            $colBCategory = $this->pickCol($bCols, ['category_id']);
            $colBMonth    = $this->pickCol($bCols, ['month']);
            $colBAmount   = $this->pickCol($bCols, ['amount', 'budget', 'limit']);

            if ($colBCategory && $colBMonth && $colBAmount) {
                // Cố tình set để có đủ trạng thái: An toàn / Cảnh báo / Vượt hạn mức / Chưa đặt
                $budgetPlan = [
                    'food'      => 2500000,  // sẽ vượt
                    'transport' => 1500000,  // an toàn
                    'shopping'  => 2000000,  // cảnh báo
                    'travel'    => 5000000,  // an toàn
                    'bills'     => 3000000,  // an toàn
                    'health'    => 1000000,  // sẽ vượt
                    // education, entertainment, home, other: bỏ trống => "Chưa đặt"
                ];

                foreach ($budgetPlan as $key => $amount) {
                    if (empty($categoryIds[$key])) continue;

                    $row = [
                        $colBCategory => $categoryIds[$key],
                        $colBMonth    => $monthKey,
                        $colBAmount   => $amount,
                    ];

                    $this->fillTimestampsIfExist($budgetsTable, $row);

                    DB::table($budgetsTable)->updateOrInsert(
                        [$colBCategory => $categoryIds[$key], $colBMonth => $monthKey],
                        $row
                    );
                }
            }
        }

        // ====== TRANSACTIONS ======
        $transactionsTable = 'transactions';
        if (!Schema::hasTable($transactionsTable)) {
            $this->command?->warn("Không tìm thấy bảng `transactions`. Kết thúc seed.");
            return;
        }

        $tCols = Schema::getColumnListing($transactionsTable);

        $colTUserStr  = $this->pickCol($tCols, ['user', 'username', 'email']);
        $colTUserId   = $this->pickCol($tCols, ['user_id', 'owner_id']);
        $colTType     = $this->pickCol($tCols, ['type']);
        $colTDesc     = $this->pickCol($tCols, ['expense', 'description', 'title', 'note']);
        $colTTotal    = $this->pickCol($tCols, ['total', 'amount', 'money']);
        $colTDate     = $this->pickCol($tCols, ['date', 'spent_at', 'transacted_at']);
        $colTCategory = $this->pickCol($tCols, ['category_id']);

        foreach ([$colTType, $colTDesc, $colTTotal, $colTDate] as $must) {
            if (!$must) {
                $this->command?->warn("Transactions thiếu cột bắt buộc (type/desc/total/date). Vui lòng kiểm tra schema.");
                return;
            }
        }

        // Xóa dữ liệu cũ của admin để seed lại “đẹp”
        $q = DB::table($transactionsTable);
        if ($colTUserStr) $q->where($colTUserStr, $adminEmail);
        elseif ($colTUserId && $adminId) $q->where($colTUserId, $adminId);
        $q->delete();

        // tạo data trải đều trong tháng
        mt_srand(278);

        $today = Carbon::now()->startOfDay();
        $start = $today->copy()->startOfMonth();
        $days  = min($today->day, (int)$today->copy()->endOfMonth()->format('d'));

        $insertTx = function (string $type, Carbon $date, int $amount, string $desc, ?int $categoryId) use (
            $transactionsTable, $colTUserStr, $colTUserId, $adminEmail, $adminId,
            $colTType, $colTDesc, $colTTotal, $colTDate, $colTCategory
        ) {
            $row = [
                $colTType  => $type,              // 'income' | 'expense'
                $colTDesc  => $desc,
                $colTTotal => $amount,
                $colTDate  => $date->toDateString(),
            ];

            if ($colTUserStr) $row[$colTUserStr] = $adminEmail;
            if ($colTUserId && $adminId) $row[$colTUserId] = $adminId;

            if ($colTCategory) {
                $row[$colTCategory] = ($type === 'expense') ? $categoryId : null;
            }

            $this->fillTimestampsIfExist($transactionsTable, $row);

            DB::table($transactionsTable)->insert($row);
        };

        // Income: lương + freelance + thưởng (tạo spike trên biểu đồ)
        $insertTx('income', $start->copy()->addDays(2), 25000000, 'Lương tháng', null);
        if ($days >= 12) $insertTx('income', $start->copy()->addDays(11), 3500000, 'Freelance', null);
        if ($days >= 20) $insertTx('income', $start->copy()->addDays(19), 1200000, 'Thưởng', null);

        // Expense: ăn/uống rải đều (để vượt budget), di chuyển đều, shopping vài lần, bills 1 lần, health vài lần...
        for ($i = 0; $i < $days; $i++) {
            $d = $start->copy()->addDays($i);

            // ăn/uống: gần như mỗi ngày
            $food = mt_rand(70000, 180000);
            $insertTx('expense', $d, $food, 'Ăn uống', $categoryIds['food'] ?? null);

            // di chuyển: cách 2 ngày
            if ($i % 2 === 0) {
                $move = mt_rand(30000, 90000);
                $insertTx('expense', $d, $move, 'Di chuyển', $categoryIds['transport'] ?? null);
            }

            // giải trí: cuối tuần
            if (in_array($d->dayOfWeekIso, [6,7], true)) {
                $fun = mt_rand(50000, 250000);
                $insertTx('expense', $d, $fun, 'Giải trí', $categoryIds['entertainment'] ?? null);
            }

            // mua sắm: 3 lần/tháng
            if (in_array($i, [5, 14, 23], true)) {
                $shop = mt_rand(400000, 900000);
                $insertTx('expense', $d, $shop, 'Mua sắm', $categoryIds['shopping'] ?? null);
            }

            // sức khỏe: 2 lần
            if (in_array($i, [9, 21], true)) {
                $health = mt_rand(450000, 850000);
                $insertTx('expense', $d, $health, 'Sức khỏe', $categoryIds['health'] ?? null);
            }

            // du lịch: 1 lần (đẹp chart)
            if ($i === 16) {
                $insertTx('expense', $d, 1800000, 'Du lịch', $categoryIds['travel'] ?? null);
            }

            // hóa đơn: 1 lần
            if ($i === 7) {
                $insertTx('expense', $d, 2200000, 'Hóa đơn tháng', $categoryIds['bills'] ?? null);
            }

            // giáo dục: 1 lần (không set budget để hiện "Chưa đặt")
            if ($i === 12) {
                $insertTx('expense', $d, 650000, 'Khóa học', $categoryIds['education'] ?? null);
            }
        }

        // thêm vài giao dịch tháng trước để Analytics/PDF nhìn “có lịch sử”
        $prev = $start->copy()->subMonth();
        $insertTx('income', $prev->copy()->addDays(3), 24000000, 'Lương tháng trước', null);
        $insertTx('expense', $prev->copy()->addDays(10), 1200000, 'Mua sắm', $categoryIds['shopping'] ?? null);
        $insertTx('expense', $prev->copy()->addDays(18), 950000, 'Sức khỏe', $categoryIds['health'] ?? null);

        $this->command?->info("DemoSpendlySeeder: OK (admin + categories + budgets + transactions).");
    }

    private function pickCol(array $cols, array $candidates): ?string
    {
        foreach ($candidates as $c) {
            if (in_array($c, $cols, true)) return $c;
        }
        return null;
    }

    private function fillTimestampsIfExist(string $table, array &$row): void
    {
        $cols = Schema::getColumnListing($table);
        $now = now();

        if (in_array('created_at', $cols, true) && !array_key_exists('created_at', $row)) {
            $row['created_at'] = $now;
        }
        if (in_array('updated_at', $cols, true) && !array_key_exists('updated_at', $row)) {
            $row['updated_at'] = $now;
        }
    }

    private function getIdBy(string $table, array $where): ?int
    {
        if (!Schema::hasColumn($table, 'id')) return null;

        $q = DB::table($table);
        foreach ($where as $k => $v) $q->where($k, $v);

        $id = $q->value('id');
        return $id ? (int)$id : null;
    }
}
