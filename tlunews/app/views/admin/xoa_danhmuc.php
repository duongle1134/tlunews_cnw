<?php
include($_SERVER['DOCUMENT_ROOT'] . '/tlunews/config/db_connect.php');

$conn = db_connect();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM danhmuc WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header('Location: ds_danhmuc.php');
        exit;
    } else {
        echo "Lỗi khi xóa danh mục: " . $stmt->error;
    }
} else {
    header('Location: ds_danhmuc.php');
    exit;
}
