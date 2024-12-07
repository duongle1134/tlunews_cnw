<?php
// Bao gồm file db_connect.php sử dụng đường dẫn tuyệt đối
include($_SERVER['DOCUMENT_ROOT'] . '/tlunews/config/db_connect.php');

// Kết nối cơ sở dữ liệu
$conn = db_connect();

if (!$conn) {
    die("Kết nối cơ sở dữ liệu thất bại");
}

// Lấy danh sách danh mục từ bảng `danhmuc`
$sql_danhmuc = "SELECT id, ten_danhmuc FROM danhmuc";
$result_danhmuc = $conn->query($sql_danhmuc);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Kiểm tra nếu có ảnh được tải lên
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Lấy thông tin ảnh
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];

        // Đường dẫn lưu ảnh
        $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/tlunews/uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true); // Tạo thư mục nếu chưa tồn tại
        }

        $image_path = $upload_dir . 'image_' . uniqid() . '.' . pathinfo($image_name, PATHINFO_EXTENSION);

        // Di chuyển file ảnh từ thư mục tạm vào thư mục uploads
        if (!move_uploaded_file($image_tmp, $image_path)) {
            die('Lỗi khi tải ảnh lên server!');
        }
    } else {
        $image_path = ''; // Nếu không có ảnh, để trống
    }

    // Các tham số bài viết từ form
    $tieu_de = $_POST['tieu_de'];
    $noi_dung = $_POST['noi_dung'];
    $danhmuc_id = $_POST['danhmuc_id'];

    // Câu lệnh SQL để thêm bài viết vào cơ sở dữ liệu
    $sql = "INSERT INTO tintuc (tieu_de, noi_dung, anh_bia, danhmuc_id) VALUES (?, ?, ?, ?)";

    // Chuẩn bị câu lệnh SQL
    $stmt = $conn->prepare($sql);

    // Kiểm tra nếu có lỗi trong việc chuẩn bị câu lệnh
    if ($stmt === false) {
        die('Lỗi SQL: ' . $conn->error);
    }

    // Liên kết tham số
    $stmt->bind_param("sssi", $tieu_de, $noi_dung, $image_path, $danhmuc_id);

    // Thực thi câu lệnh
    if ($stmt->execute()) {
        // Chuyển hướng về trang Dashboard sau khi thêm thành công
        header('Location: /tlunews/app/views/admin/dashboard.php');
        exit;
    } else {
        echo "Lỗi khi thêm bài viết: " . $stmt->error;
    }

    // Đóng kết nối
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm bài viết mới</title>
</head>

<body>
    <h2>Thêm bài viết mới</h2>

    <form action="add_post.php" method="post" enctype="multipart/form-data">
        <div>
            <label for="tieu_de">Tiêu đề bài viết:</label>
            <input type="text" name="tieu_de" required>
        </div>
        <div>
            <label for="noi_dung">Nội dung bài viết:</label>
            <textarea name="noi_dung" required></textarea>
        </div>
        <div>
            <label for="danhmuc_id">Danh mục:</label>
            <select name="danhmuc_id" required>
                <option value="" disabled selected>Chọn danh mục</option>
                <?php while ($row = $result_danhmuc->fetch_assoc()): ?>
                    <option value="<?= $row['id']; ?>"><?= $row['ten_danhmuc']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div>
            <label for="image">Chọn ảnh:</label>
            <input type="file" name="image" accept="image/*">
        </div>
        <div>
            <button type="submit">Hoàn thành</button>
        </div>
    </form>
</body>

</html>