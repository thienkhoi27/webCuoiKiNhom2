<div align="center">

  <img src="https://cdn-icons-png.flaticon.com/512/2344/2344132.png" alt="Logo" width="100" height="100">

  # 💰 Hệ Thống Quản Lý Chi Tiêu Cá Nhân
  
  **Giải pháp tài chính thông minh - Đơn giản hóa việc quản lý ngân sách của bạn**

  [![Laravel](https://img.shields.io/badge/Laravel-9.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
  [![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php)](https://www.php.net)
  [![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql)](https://www.mysql.com)
  [![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](./LICENSE)

</div>

---

## 📖 Giới thiệu

Ứng dụng web giúp người dùng quản lý tài chính cá nhân một cách hiệu quả và trực quan. Hệ thống không chỉ là nơi ghi chép, mà còn là trợ lý phân tích tài chính giúp bạn đưa ra quyết định chi tiêu thông minh hơn thông qua các biểu đồ và tính năng tự động hóa.

### ✨ Tính năng nổi bật

| Tính năng | Mô tả |
| :--- | :--- |
| 📝 **Ghi chép hàng ngày** | Dễ dàng thêm mới các khoản thu/chi với vài cú click. |
| 🗂️ **Phân loại thông minh** | Tự động phân loại giao dịch dựa trên từ khóa gợi ý. |
| 📊 **Thống kê trực quan** | Tích hợp **Chart.js** vẽ biểu đồ xu hướng tài chính theo thời gian thực. |
| 💸 **Quản lý ngân sách** | Thiết lập giới hạn chi tiêu cho từng danh mục theo tháng. |
| 🎨 **Giao diện hiện đại** | Thiết kế thân thiện, tương tác mượt mà với Blade & CSS3. |

---

## 🛠️ Công nghệ sử dụng

Dự án được xây dựng dựa trên mô hình **MVC** vững chắc và các công nghệ hiện đại:

* **Backend:** [Laravel 9.0](https://laravel.com/), [PHP 8.2](https://php.net/)
* **Database:** [MySQL](https://www.mysql.com/) (Eloquent ORM)
* **Frontend:** [Blade Template](https://laravel.com/docs/blade), HTML5, CSS3, JavaScript
* **Visualization:** [Chart.js](https://www.chartjs.org/)
* **Server:** Apache/Nginx

---

## 📸 Ảnh màn hình (Screenshots)

### 🖥️ Bảng điều khiển (Dashboard)
Hiển thị tổng quan tình hình tài chính, biểu đồ thu chi và danh sách giao dịch gần nhất.

![Dashboard Screenshot](screenshots/ss1.png)
*(Hình 1: Giao diện Bảng điều khiển hiển thị thống kê tổng quan)*

---

## 🚀 Hướng dẫn cài đặt

Để chạy ứng dụng trên máy cá nhân (Localhost), vui lòng thực hiện theo các bước sau:

### Yêu cầu hệ thống
* PHP >= 8.0
* Composer
* MySQL

### Các bước thực hiện

1.  **Clone source code**
    ```bash
    git clone [https://github.com/username/HeThongQuanLyChiTieu.git](https://github.com/username/HeThongQuanLyChiTieu.git)
    cd HeThongQuanLyChiTieu
    ```

2.  **Cài đặt dependencies**
    ```bash
    composer install
    ```

3.  **Cấu hình môi trường (.env)**
    ```bash
    cp .env.example .env
    ```
    *Mở file `.env` và chỉnh sửa thông tin kết nối Database:*
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=personal_expense_db
    DB_USERNAME=root
    DB_PASSWORD=
    ```

4.  **Tạo Application Key**
    ```bash
    php artisan key:generate
    ```

5.  **Khởi tạo Database & Seed dữ liệu**
    *Đảm bảo bạn đã tạo database `personal_expense_db` trong MySQL trước.*
    ```bash
    php artisan migrate
    php artisan db:seed
    ```

6.  **Khởi chạy ứng dụng**
    ```bash
    php artisan serve
    ```

🎉 Truy cập địa chỉ: `http://localhost:8000`

---

## 🔐 Tài khoản Demo

Để thuận tiện cho việc trải nghiệm (Review), hệ thống có sẵn tài khoản Admin:

> **Email:** `admin@example.com`  
> **Password:** `password`

---

## 👥 Đội ngũ phát triển

| STT | Thành viên | Vai trò | Github |
| :--: | :--- | :--- | :--- |
| 1 | **Ngyễn THu Hương** | Team Leader / Backend | [@thuhun166] |
| 2 | **Lê Thiện Khôi** | Frontend / UI-UX | [@thienkhoi27](#) |
| 3 | **Nguyễn Tuấn Kiệt** | Database / Tester | [@](#) |
| 4 | **Dương Phú Nhật** | Database / Tester | [@](#) |
| 5 | **Hoàng Thị Kiều Diễm** | Database / Tester | [@](#) |

---

## 📄 License

Dự án này được cấp phép theo giấy phép [MIT](https://opensource.org/licenses/MIT).

<div align="center">
  <sub>Được xây dựng với ❤️ bởi Nhóm phát triển.</sub>
</div>
