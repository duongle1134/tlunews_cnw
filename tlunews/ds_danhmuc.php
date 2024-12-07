<?php
include($_SERVER['DOCUMENT_ROOT'] . '/tlunews/config/db_connect.php');

$conn = db_connect();

if (!$conn) {
    die("Kết nối cơ sở dữ liệu thất bại");
}

// Lấy danh sách danh mục
$sql = "SELECT * FROM danhmuc";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách danh mục</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2>Danh sách danh mục</h2>
        <a href="add_danhmuc.php" class="btn btn-primary mb-3">Thêm danh mục</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên danh mục</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['ten_danhmuc']) ?></td>
                        <td>
                            <a href="sua_danhmuc.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                            <a href="xoa_danhmuc.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa danh mục này?')">Xóa</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="dashboard.php" class="btn btn-secondary">Quay lại Dashboard</a>
    </div>
</body>

</html>