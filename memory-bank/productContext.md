# Product Context: Project_4

## Tại sao dự án này tồn tại?
* Để cung cấp một nền tảng thương mại điện tử hoàn chỉnh cho doanh nghiệp vừa và nhỏ.
* Để đáp ứng nhu cầu quản lý hiệu quả các sản phẩm, danh mục và đơn hàng trực tuyến.
* Để tạo ra một giao diện mua sắm trực tuyến thân thiện với người dùng Việt Nam.

## Vấn đề dự án giải quyết
* Khó khăn trong việc quản lý kho hàng và sản phẩm theo cách thủ công.
* Nhu cầu bán hàng trực tuyến với quy trình đơn giản và hiệu quả.
* Thiếu một hệ thống quản lý đơn hàng và theo dõi trạng thái đơn hàng.
* Khó khăn trong việc quản lý người dùng và phân quyền.

## Cách nó hoạt động (Ý tưởng chung)
* **Người dùng thông thường:**
  * Xem danh sách sản phẩm, chi tiết sản phẩm, và lọc/tìm kiếm sản phẩm.
  * Đăng ký tài khoản, đăng nhập và quản lý thông tin cá nhân.
  * Thêm sản phẩm vào giỏ hàng, cập nhật số lượng, và xóa sản phẩm khỏi giỏ hàng.
  * Thanh toán đơn hàng với các phương thức thanh toán khác nhau.
  * Xem lịch sử đơn hàng và trạng thái đơn hàng.

* **Quản trị viên:**
  * Đăng nhập vào khu vực quản trị (qua `admin.php`).
  * Quản lý (thêm, sửa, xóa) danh mục sản phẩm.
  * Quản lý (thêm, sửa, xóa) sản phẩm.
  * Quản lý người dùng và phân quyền.
  * Xem và cập nhật trạng thái đơn hàng.
  * Xem báo cáo và thống kê (nếu có).

## Mục tiêu trải nghiệm người dùng (UX Goals)
* **Giao diện thân thiện:** Thiết kế trực quan, dễ sử dụng cho cả người dùng cuối và quản trị viên.
* **Tốc độ và hiệu suất:** Trang web tải nhanh, phản hồi nhanh với người dùng.
* **Tính nhất quán:** Giao diện và trải nghiệm người dùng nhất quán trên tất cả các trang.
* **Thông báo rõ ràng:** Cung cấp thông báo và phản hồi rõ ràng cho các hành động của người dùng.
* **Tính tiện lợi:** Giảm thiểu số bước cần thiết để hoàn thành một nhiệm vụ (đặc biệt là quá trình thanh toán). 