Nhật ký Phát triển (Reports)

> **Dự án:** Spendly – Hệ thống quản lý chi tiêu cá nhân (Laravel)  
> **Mục tiêu:** Ghi lại quá trình làm việc 1–2 tuần/lần, làm bằng chứng tiến độ và tư duy kỹ thuật của nhóm.  
> **Phạm vi:** Thu/Chi, Danh mục + ảnh, Hạn mức & cảnh báo, Dashboard + biểu đồ, Analytics + tìm kiếm, Xuất báo cáo PDF, Seeder dữ liệu demo, Deploy hosting.

---

## Giai đoạn 1 (Tuần 1–2): Khởi động dự án & chốt phạm vi

### 1) Tuần qua nhóm đã làm được gì?
- **Thống nhất đề tài & phạm vi chức năng**
  - Chốt dự án “Spendly” tập trung quản lý thu/chi cá nhân.
  - Xác định 2 **logic nghiệp vụ chuyên biệt** cần thể hiện rõ trong báo cáo:
    1) **Cảnh báo hạn mức theo danh mục** (tính chi theo tháng và so với hạn mức).
    2) **Xuất báo cáo PDF** theo khoảng ngày (tổng hợp thu/chi và xuất file đẹp, đúng tiếng Việt).
- **Khởi tạo project & môi trường**
  - Tạo dự án Laravel + cấu hình Vite/Tailwind.
  - Chuẩn hóa cách chạy local: `composer install`, `npm install`, `php artisan serve`, `npm run dev`.
- **Thiết kế dữ liệu sơ bộ (định hướng ERD)**
  - Xác định các bảng nghiệp vụ:
    - `transactions` (thu/chi theo ngày)
    - `categories` (danh mục chi + icon)
    - `category_budgets` (hạn mức theo danh mục theo tháng)
    - `users/sessions` (đăng nhập, quản lý phiên)
  - Xác định quan hệ: `Transaction belongsTo Category`.

### 2) Gặp những khó khăn/vấn đề gì?
- Khó chốt phạm vi để “đủ lớn” cho bài cuối kỳ: CRUD thu/chi đơn thuần dễ bị đánh giá là đơn giản.
- Chưa rõ cách triển khai **hạn mức** (tính theo tháng, theo danh mục) sao cho đúng và dễ hiển thị trạng thái.
- Chưa thống nhất cách lưu ảnh danh mục & ảnh profile để deploy lên hosting không bị lỗi mất ảnh.

### 3) Nhóm đã giải quyết vấn đề đó như thế nào?
- Chốt phạm vi theo hướng “vừa đủ lớn – dễ chứng minh nghiệp vụ”:
  - Thu/chi + danh mục + ảnh
  - Dashboard có tổng quan và biểu đồ
  - Analytics có tìm kiếm
  - **Hạn mức & cảnh báo**
  - **PDF report**
- Quyết định kỹ thuật:
  - Ảnh dùng `Storage` (lưu `storage/app/public/...`) và truy cập bằng `Storage::url()`.
  - Khi deploy: cần `storage:link` hoặc chuyển ảnh về `public/` nếu hosting không hỗ trợ symlink.

### 4) Kế hoạch cho tuần tới là gì?
- Hoàn thiện migrations + models.
- Dựng giao diện layout chính (sidebar + main container).
- Làm form thêm giao dịch (thu/chi) + validate cơ bản + lưu DB.

---

## Giai đoạn 2 (Tuần 3–4): Hoàn thiện Thu/Chi + Danh mục + UI nền tảng

### 1) Tuần qua nhóm đã làm được gì?
- **Xây form “Thêm chi/thu”**
  - Tạo trường: mô tả, số tiền, ngày, loại giao dịch (`type`).
  - Logic UI: nếu chọn **Chi** thì hiện danh mục; nếu **Thu** thì không bắt danh mục.
- **Làm danh mục + icon**
  - CRUD danh mục chi tiêu.
  - Upload ảnh danh mục (icon) và hiển thị trên dashboard, analytics.
- **Hiển thị “Thu/Chi gần nhất”**
  - List giao dịch hiển thị theo màu:
    - **Thu**: xanh (nền xanh nhạt, số tiền xanh, dấu “+”).
    - **Chi**: cam/đỏ (nền cam nhạt, số tiền cam, dấu “-”).
  - Bổ sung icon:
    - Giao dịch **Chi**: lấy icon theo danh mục.
    - Giao dịch **Thu**: icon mặc định (mũi tên lên).

### 2) Gặp những khó khăn/vấn đề gì?
- Nhiều lỗi dạng “Undefined variable” trong Blade do component/slot chưa truyền đủ props (ví dụ: `$label`, `$total`, `$category`...).
- Lỗi DB do migration chưa đồng bộ với query:
  - thiếu cột `category_id`, `type`, hoặc sai kiểu khóa ngoại.
- UI bị “tràn” khi zoom 100% (phải zoom 75% mới đẹp); scroll xuất hiện sai nơi (scroll cả trang thay vì scroll trong list).

### 3) Nhóm đã giải quyết vấn đề đó như thế nào?
- Chuẩn hóa cách truyền dữ liệu vào view:
  - Controller/route trả đủ biến cần dùng.
  - Blade dùng `@isset`, `??` để tránh lỗi khi dữ liệu null.
  - Component khai báo `@props` rõ ràng.
- Sửa migrations theo logic nghiệp vụ:
  - `transactions` cần có: `type`, `category_id` (nullable), `total`, `date`, `expense`.
  - Đảm bảo thứ tự migration: bảng `categories` tạo trước khi tạo khóa ngoại ở `transactions`.
- Sửa layout/scroll:
  - Chỉ cho scroll bên trong container list (`overflow-y-auto`) thay vì ngoài content.
  - Dùng `min-h-screen`, giới hạn chiều cao list để không phá layout tổng.

### 4) Kế hoạch cho tuần tới là gì?
- Triển khai hạn mức theo danh mục theo tháng + cảnh báo trạng thái.
- Hoàn thiện dashboard: 2 card “Bạn đã chi / Bạn đã thu”, cân đối kích thước.
- Làm biểu đồ thu/chi theo tháng (theo ngày trong tháng hiện tại).

---

## Giai đoạn 3 (Tuần 5–6): Hạn mức & Cảnh báo + Dashboard + Analytics Search

### 1) Tuần qua nhóm đã làm được gì?
- **Triển khai hạn mức theo danh mục**
  - Tạo bảng `category_budgets` với:
    - `category_id`, `month` (lưu ngày 01 của tháng, VD: `2025-12-01`), `amount`.
  - Logic tính tổng chi theo danh mục trong tháng:
    - lọc theo `user`, `type='expense'`, `date between monthStart-monthEnd`, `category_id`.
- **Hiển thị trạng thái cảnh báo hạn mức**
  - Nếu chưa đặt hạn mức: hiển thị “Chưa đặt”.
  - Nếu có hạn mức:
    - **An toàn**: chi dưới ngưỡng (ví dụ < 70%) → chữ xanh.
    - **Cảnh báo**: tiến gần hạn mức (70%–100%) → chữ cam.
    - **Vượt hạn mức**: > 100% → chữ đỏ.
  - UI: tiến trình (progress bar) thể hiện % đã dùng.
- **Trang Analytics + tìm kiếm**
  - Làm danh sách lịch sử thu/chi.
  - Thêm ô tìm kiếm (GET), dự kiến tìm theo mô tả, danh mục, loại thu/chi.

### 2) Gặp những khó khăn/vấn đề gì?
- Logic hạn mức ban đầu đặt ngay trong `routes/web.php` → code dài, khó bảo trì, khó debug.
- Tìm kiếm “Enter không submit” và “không có /analysis?search=...” do:
  - sai route name, sai view name (nhầm `analysis` vs `analytics`),
  - hoặc form action chưa đúng.
- Trên UI biểu đồ/tiêu đề bị xuống dòng, không nằm ngang như mong muốn.

### 3) Nhóm đã giải quyết vấn đề đó như thế nào?
- **Tách dần logic**:
  - Những phần nặng (analytics search, PDF) đưa vào controller.
  - Với hạn mức, nếu vẫn đặt tại routes để nhanh, cần comment rõ và giữ gọn; nếu có thời gian thì refactor sang controller/service.
- **Fix tìm kiếm**
  - Dùng route `/analytics` + method GET.
  - Form action trỏ đúng `/analytics` (hoặc `route('analytics')` nếu có đặt name).
  - Trong query backend: nếu `request('search')` có giá trị → thêm `where`/`orWhere` để lọc.
- **Fix UI**
  - Bọc tiêu đề và chart trong layout flex hợp lý, tránh chiều rộng quá nhỏ gây wrap.
  - Áp dụng `whitespace-nowrap` hoặc tăng width cho tiêu đề.

### 4) Kế hoạch cho tuần tới là gì?
- Hoàn thiện PDF report (có cả thu và chi).
- Fix font tiếng Việt trong PDF (NotoSans/DejaVu).
- Viết seeder dữ liệu demo: có admin, có danh mục + icon + hạn mức + thu/chi trải đều ngày để biểu đồ đẹp.
- Bắt đầu deploy hosting và xử lý lỗi thiếu ảnh.

---

## Giai đoạn 4 (Tuần 7–8): PDF Report + Seeder Demo + Deploy Hosting

### 1) Tuần qua nhóm đã làm được gì?
- **Xuất báo cáo PDF theo khoảng ngày**
  - Validate đầu vào `fromDate`, `toDate`.
  - Lấy danh sách giao dịch trong khoảng (thu + chi), join bảng categories để lấy `category_name`.
  - Tính:
    - `totalExpense`: tổng chi
    - `totalIncome`: tổng thu
    - `net = totalIncome - totalExpense`
  - Render view `pdf.document` và xuất PDF.
- **Thiết kế giao diện PDF chuyên nghiệp**
  - Header: thông tin tài khoản, khoảng ngày, thời điểm in.
  - Cards tổng quan: Tổng chi (cam), Tổng thu (xanh), Chênh lệch (tím/xanh dương).
  - Bảng chi tiết: phân loại thu/chi với badge và màu tiền.
  - Định dạng tiền theo `₫` và `number_format`.
- **Fix font tiếng Việt**
  - Nhúng font `NotoSans` hoặc dùng `DejaVu Sans`.
  - Điều chỉnh CSS để DomPDF render đúng dấu.
- **Seeder demo**
  - Tạo user demo + categories + budgets + transactions đa dạng, trải đều nhiều ngày để biểu đồ thể hiện rõ.
- **Deploy hosting & kiểm thử**
  - Kiểm tra route, static assets, hiển thị ảnh.

### 2) Gặp những khó khăn/vấn đề gì?
- PDF lỗi `Undefined variable $fmt` do view dùng helper format nhưng chưa khai báo.
- Deploy bị mất ảnh danh mục/icon và ảnh profile:
  - Hosting không có symlink `public/storage` hoặc không cấp quyền ghi.
- Một số chức năng (ví dụ edit expense) chạy local OK nhưng lên hosting không hoạt động:
  - khác cấu hình rewrite, CSRF, method/route, hoặc đường dẫn action tương đối.

### 3) Nhóm đã giải quyết vấn đề đó như thế nào?
- **PDF**
  - Đưa format tiền về controller (hoặc khai báo `$fmt` trong view bằng closure).
  - Thống nhất đơn vị: luôn hiển thị `₫`.
- **Ảnh hosting**
  - Ưu tiên cách chuẩn Laravel:
    - lưu ảnh trong `storage/app/public`
    - dùng `Storage::url()`
    - chạy `php artisan storage:link` trên server
  - Nếu hosting không hỗ trợ symlink:
    - chuyển ảnh sang `public/images/...` và dùng `asset()` để gọi trực tiếp.
- **Edit trên hosting**
  - Kiểm tra method đúng (`POST/PUT`), route tồn tại, và `@csrf`.
  - Dùng URL tuyệt đối `url('/edit-expense/'.$id)` tránh sai đường dẫn.

### 4) Kế hoạch cho tuần tới là gì?
- Chốt toàn bộ lỗi UI 100% zoom (responsive) để đúng như bản thiết kế.
- Hoàn thiện README (tính năng, cài đặt, link public, tài khoản demo).
- Tổng hợp báo cáo cuối kỳ + chụp hình minh họa các màn hình và luồng nghiệp vụ.

---

## Tổng kết giá trị học thuật & kỹ thuật (rút ra từ nhật ký)
- Nhóm không chỉ làm CRUD, mà triển khai 2 logic nghiệp vụ rõ ràng:
  1) **Hạn mức & cảnh báo**: mô hình hóa dữ liệu theo tháng/danh mục, tính toán tổng chi theo tháng và phân loại trạng thái hiển thị trực quan.
  2) **Báo cáo PDF**: validate khoảng ngày, truy vấn + tổng hợp số liệu, thiết kế layout PDF, xử lý font tiếng Việt và tiền tệ `₫`.
- Nhóm cải thiện UX:
  - Phân biệt thu/chi bằng màu sắc + dấu + icon danh mục.
  - Sắp xếp giao dịch mới nhất lên đầu.
  - Tối ưu scroll đúng vùng hiển thị, tránh phá layout.
- Nhóm xử lý nhiều lỗi thực tế khi deploy:
  - Mất ảnh do storage link
  - Khác biệt local/hosting do route/method/config
  - Fix conflict git và chuẩn hóa build assets.

---
