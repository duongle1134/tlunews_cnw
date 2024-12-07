<?php
// Bao gồm file kết nối cơ sở dữ liệu
include($_SERVER['DOCUMENT_ROOT'] . '/tlunews/config/db_connect.php');

// Kết nối cơ sở dữ liệu
$conn = db_connect();

// Kiểm tra nếu có tham số 'id' trong URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Kiểm tra trạng thái 'is_featured'
    $sql = "SELECT is_featured FROM tintuc WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($is_featured);
    $stmt->fetch();

    if ($is_featured == 1) {
        // Nếu là tin nổi bật, tắt nổi bật
        $sql = "UPDATE tintuc SET is_featured = 0 WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo 'unset';
        } else {
            echo 'error';
        }
    } else {
        // Nếu không phải tin nổi bật, đặt là tin nổi bật
        $sql = "UPDATE tintuc SET is_featured = 1 WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo 'set';
        } else {
            echo 'error';
        }
    }
}

// Đóng kết nối
$conn->close();
