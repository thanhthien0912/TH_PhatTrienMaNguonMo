# System Patterns: Project_4

## Kiến trúc hệ thống
* **Mô hình Model-View-Controller (MVC)**: Ứng dụng được cấu trúc theo mô hình MVC rõ ràng.
  * **Model**: Nằm trong thư mục `app/models/`, chịu trách nhiệm xử lý logic nghiệp vụ và tương tác với cơ sở dữ liệu. Bao gồm ProductModel, CategoryModel, UserModel, và có thể có OrderModel.
  * **View**: Nằm trong thư mục `app/views/`, chịu trách nhiệm hiển thị dữ liệu và giao diện người dùng. Có các thư mục con cho `category`, `product`, `shares` (các thành phần dùng chung), và `user`.
  * **Controller**: Nằm trong thư mục `app/controllers/`, tiếp nhận yêu cầu từ người dùng, tương tác với Model để lấy dữ liệu và chọn View thích hợp để trả về phản hồi. Bao gồm ProductController, CategoryController, UserController.

## Quyết định kỹ thuật chính
* **Ngôn ngữ phía máy chủ**: PHP thuần, không sử dụng framework lớn.
* **Web Server và URL Rewriting**: Sử dụng Apache với `mod_rewrite` để tạo URL thân thiện và định tuyến tất cả các yêu cầu đến `index.php`.
* **Quản lý phiên và xác thực**: Sử dụng PHP Session để quản lý phiên đăng nhập và giỏ hàng.
* **Bảo mật**: 
  * Mã hóa mật khẩu với `password_hash()` và `password_verify()`.
  * Xác thực CSRF cho các form.
  * Prepared statements với PDO để ngăn chặn SQL injection.
* **Quản lý tồn kho**: Theo dõi số lượng tồn kho và kiểm tra khi thêm vào giỏ hàng.
* **Upload hình ảnh**: Xử lý upload và lưu trữ hình ảnh sản phẩm và avatar người dùng.

## Mẫu thiết kế được sử dụng
* **Front Controller**: Sử dụng `index.php` làm điểm vào chính để xử lý tất cả các yêu cầu.
* **Singleton**: Kết nối cơ sở dữ liệu được quản lý như một singleton thông qua class Database.
* **Factory Method**: Các controller tạo ra các đối tượng model khi cần thiết.
* **Helper Classes**: Các lớp tiện ích như SessionHelper để đóng gói chức năng chung.
* **Content-Wrapper Pattern**: Sử dụng wrapper để giới hạn chiều rộng tối đa và đảm bảo khoảng trắng 2 bên.

## Quan hệ giữa các thành phần
* **Routing**: Yêu cầu từ người dùng được xử lý bởi `index.php`, phân tích URL để xác định controller và action cần gọi.
* **Controller-Model**: Controller tạo và sử dụng các đối tượng model để tương tác với cơ sở dữ liệu.
* **Controller-View**: Controller chọn view thích hợp để hiển thị dữ liệu.
* **User-Role**: Hệ thống phân quyền dựa trên vai trò (role) của người dùng (admin hoặc user thường).
* **Product-Category**: Sản phẩm thuộc về một danh mục (quan hệ nhiều-một).
* **User-Product**: Người dùng có thể thêm sản phẩm vào giỏ hàng và đặt hàng.
* **Order-Product**: Đơn hàng chứa nhiều sản phẩm với số lượng cụ thể.

## Cơ chế xử lý lỗi
* **Hiển thị thông báo lỗi**: Sử dụng SessionHelper để lưu trữ và hiển thị thông báo lỗi.
* **Xác thực đầu vào**: Kiểm tra và xác thực dữ liệu người dùng nhập vào.
* **Xử lý ngoại lệ**: Sử dụng try-catch để xử lý ngoại lệ khi tương tác với cơ sở dữ liệu.
* **Kiểm tra quyền truy cập**: Kiểm tra quyền trước khi cho phép truy cập vào các chức năng quản trị.
* **Kiểm tra tồn kho**: Kiểm tra số lượng tồn kho trước khi thêm vào giỏ hàng. 