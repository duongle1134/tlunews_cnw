<?php
include($_SERVER['DOCUMENT_ROOT'] . '/tlunews/config/db_connect.php');

$conn = db_connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ten_danhmuc = $_POST['ten_danhmuc'];

    if (!empty($ten_danhmuc)) {
        $sql = "INSERT INTO danhmuc (ten_danhmuc) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $ten_danhmuc);

        if ($stmt->execute()) {
            header('Location: ds_danhmuc.php');
            exit;
        } else {
            echo "Lỗi: " . $stmt->error;
        }
    } else {
        echo "Vui lòng nhập tên danh mục.";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm danh mục</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2>Thêm danh mục mới</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="ten_danhmuc">Tên danh mục</label>
                <input type="text" class="form-control" id="ten_danhmuc" name="ten_danhmuc" required>
            </div>
            <button type="submit" class="btn btn-success">Thêm</button>
            <a href="ds_danhmuc.php" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</body>

</html>