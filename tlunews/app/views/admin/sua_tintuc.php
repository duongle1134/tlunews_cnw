<?php
// Bao gồm file db_connect.php sử dụng đường dẫn tuyệt đối
include($_SERVER['DOCUMENT_ROOT'] . '/tlunews/config/db_connect.php');

// Kết nối cơ sở dữ liệu
$conn = db_connect();

if (!$conn) {
    die("Kết nối cơ sở dữ liệu thất bại");
}

// Lấy ID bài viết cần sửa
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Lấy dữ liệu bài viết từ cơ sở dữ liệu
    $sql = "SELECT tieu_de, noi_dung, trang_thai FROM tintuc WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $tieu_de = $row['tieu_de'];
        $noi_dung = $row['noi_dung'];
        $trang_thai = $row['trang_thai'];
    } else {
        die("Không tìm thấy bài viết!");
    }

    // Cập nhật bài viết
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $tieu_de = $_POST['tieu_de'];
        $noi_dung = $_POST['noi_dung'];
        $trang_thai = $_POST['trang_thai'];

        $sql_update = "UPDATE tintuc SET tieu_de = ?, noi_dung = ?, trang_thai = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("sssi", $tieu_de, $noi_dung, $trang_thai, $id);
        if ($stmt_update->execute()) {
            echo "Cập nhật bài viết thành công!";
            header("Location: ds_tintuc.php");
            exit();
        } else {
            echo "Lỗi khi cập nhật bài viết!";
        }
    }
} else {
    echo "Không có ID bài viết!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Tin Tức</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container">
        <h2 class="text-center">Sửa Bài Viết</h2>

        <form method="POST" action="sua_tintuc.php?id=<?php echo $id; ?>">
            <div class="mb-3">
                <label for="tieu_de" class="form-label">Tiêu Đề</label>
                <input type="text" class="form-control" id="tieu_de" name="tieu_de" value="<?php echo $tieu_de; ?>" required>
            </div>
            <div class="mb-3">
                <label for="noi_dung" class="form-label">Nội Dung</label>
                <textarea class="form-control" id="noi_dung" name="noi_dung" rows="5" required><?php echo $noi_dung; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="trang_thai" class="form-label">Trạng Thái</label>
                <select class="form-control" id="trang_thai" name="trang_thai" required>
                    <option value="active" <?php if ($trang_thai == 'active') echo 'selected'; ?>>Kích hoạt</option>
                    <option value="inactive" <?php if ($trang_thai == 'inactive') echo 'selected'; ?>>Tạm dừng</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </form>

        <div class="mt-3">
            <a href="ds_tintuc.php" class="btn btn-secondary">Quay lại danh sách tin tức</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>