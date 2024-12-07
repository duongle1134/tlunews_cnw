<?php
// Bao gồm file db_connect.php sử dụng đường dẫn tuyệt đối
include($_SERVER['DOCUMENT_ROOT'] . '/tlunews/config/db_connect.php');

// Kết nối cơ sở dữ liệu
$conn = db_connect();

if (!$conn) {
    die("Kết nối cơ sở dữ liệu thất bại");
}

// Kiểm tra và lấy ID bài viết cần xóa
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Câu lệnh SQL để xóa bài viết
    $sql = "DELETE FROM tintuc WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Bài viết đã được xóa thành công!";
        header("Location: ds_tintuc.php");
        exit();
    } else {
        echo "Lỗi khi xóa bài viết!";
    }
} else {
    echo "Không có ID bài viết!";
    exit();
}

$conn->close();
