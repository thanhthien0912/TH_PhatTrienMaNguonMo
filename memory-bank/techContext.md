# Tech Context: Project_4

## Công nghệ sử dụng
* **Ngôn ngữ lập trình chính**: PHP thuần (không sử dụng framework lớn).
* **Web Server**: Apache với mod_rewrite được kích hoạt (dựa trên tệp `.htaccess`).
* **Cơ sở dữ liệu**: MySQL (được xác định từ tệp `app/config/database.php` và `database_setup.sql`).
* **Frontend**:
  * HTML5
  * CSS (sử dụng Bootstrap 5 dựa trên các class như `btn-primary`, `card`, `alert`)
  * JavaScript (có thể có jQuery)
  * Icons: Bootstrap Icons và Font Awesome (dựa trên các class như `bi-truck`, `bi-shield-check`, `fas fa-user`)
* **Kết nối cơ sở dữ liệu**: PDO (PHP Data Objects) được sử dụng để kết nối và tương tác với MySQL.
* **Quản lý phiên**: PHP Session được sử dụng cho đăng nhập và giỏ hàng.
* **Upload hình ảnh**: Xử lý upload hình ảnh sản phẩm và avatar người dùng.
* **Responsive Design**: Sử dụng Bootstrap 5 để tạo giao diện thích ứng với nhiều thiết bị.
* **Quản lý tồn kho**: Theo dõi và kiểm tra số lượng tồn kho khi thêm vào giỏ hàng.

## Thiết lập môi trường phát triển (Development Setup)
* **Yêu cầu cơ bản**: Môi trường LAMP/WAMP/MAMP/Laragon (Linux/Windows/MacOS, Apache, MySQL, PHP).
* **PHP**: Phiên bản 7.4 trở lên.
* **MySQL**: Phiên bản 5.7 trở lên.
* **Apache**: Với mod_rewrite được kích hoạt.
* **Cài đặt dự án**:
  1. Clone repository hoặc giải nén vào thư mục web server.
  2. Import file `database_setup.sql` vào MySQL.
  3. Cấu hình kết nối database trong `app/config/database.php`.
  4. Đảm bảo thư mục `public/uploads` và các thư mục con có quyền ghi.

## Ràng buộc kỹ thuật
* **Bảo mật**: 
  * Sử dụng PDO với prepared statements để ngăn chặn SQL injection.
  * Xác thực CSRF cho các form.
  * Mã hóa mật khẩu với password_hash().
  * Kiểm tra quyền truy cập cho các trang admin.
* **Hiệu suất**:
  * Tối ưu hóa truy vấn cơ sở dữ liệu.
  * Sử dụng phân trang cho danh sách sản phẩm.
* **Giao diện người dùng**:
  * Sử dụng layout với khoảng trắng 2 bên và nội dung ở giữa.
  * Giới hạn chiều rộng tối đa của nội dung để đảm bảo khả năng đọc.
  * Sử dụng các thành phần có border-radius và box-shadow để tạo cảm giác hiện đại.

## Phụ thuộc
* **Bootstrap 5**: Framework CSS cho giao diện người dùng.
* **Bootstrap Icons**: Thư viện icon.
* **Font Awesome**: Thư viện icon bổ sung.
* **CKEditor**: Trình soạn thảo văn bản phong phú cho mô tả sản phẩm.

## Cấu trúc dự án
* **app/**: Chứa mã nguồn chính của ứng dụng.
  * **config/**: Cấu hình cơ sở dữ liệu và ứng dụng.
  * **controllers/**: Các controller xử lý logic nghiệp vụ.
  * **models/**: Các model tương tác với cơ sở dữ liệu.
  * **views/**: Các template hiển thị giao diện người dùng.
  * **helpers/**: Các hàm tiện ích và lớp hỗ trợ.
* **public/**: Chứa tài nguyên công khai.
  * **uploads/**: Chứa hình ảnh sản phẩm và avatar người dùng.
* **index.php**: Điểm vào của ứng dụng, xử lý routing.
* **.htaccess**: Cấu hình URL rewriting cho Apache. 