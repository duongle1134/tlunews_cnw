<?php
include($_SERVER['DOCUMENT_ROOT'] . '/tlunews/config/db_connect.php');

$conn = db_connect();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM danhmuc WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $danhmuc = $result->fetch_assoc();

    if (!$danhmuc) {
        die("Danh mục không tồn tại.");
    }
} else {
    header('Location: ds_danhmuc.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ten_danhmuc = $_POST['ten_danhmuc'];
    $sql = "UPDATE danhmuc SET ten_danhmuc = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $ten_danhmuc, $id);

    if ($stmt->execute()) {
        header('Location: ds_danhmuc.php');
        exit;
    } else {
        echo "Lỗi: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa danh mục</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2>Sửa danh mục</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="ten_danhmuc">Tên danh mục</label>
                <input type="text" class="form-control" id="ten_danhmuc" name="ten_danhmuc" value="<?= htmlspecialchars($danhmuc['ten_danhmuc']) ?>" required>
            </div>
            <button type="submit" class="btn btn-warning">Cập nhật</button>
            <a href="ds_danhmuc.php" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</body>

</html>