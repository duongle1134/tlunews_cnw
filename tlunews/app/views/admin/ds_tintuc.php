<?php
// Bao gồm file db_connect.php sử dụng đường dẫn tuyệt đối
include($_SERVER['DOCUMENT_ROOT'] . '/tlunews/config/db_connect.php');

// Kết nối cơ sở dữ liệu
$conn = db_connect();

if (!$conn) {
    die("Kết nối cơ sở dữ liệu thất bại");
}

// Câu lệnh SQL để lấy danh sách tin tức
$sql = "SELECT id, tieu_de, ngay_tao, trang_thai, is_featured FROM tintuc";  // Thêm cột is_featured

// Thực thi câu lệnh SQL
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách Tin Tức</title>
    <!-- Thêm Bootstrap CSS để dễ dàng tạo giao diện đẹp -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* CSS tuỳ chỉnh cho trang danh sách tin tức */
        body {
            font-family: Arial, sans-serif;
            margin-top: 20px;
        }

        .table-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .btn-actions {
            margin: 0 5px;
        }

        .back-home-btn {
            margin-top: 20px;
            text-align: center;
        }

        .featured-icon {
            color: #ffcc00;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="table-container">
            <h2 class="text-center">Danh sách Tin Tức</h2>

            <?php
            if ($result->num_rows > 0) {
                echo "<table class='table table-bordered'>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tiêu Đề</th>
                            <th>Ngày Tạo</th>
                            <th>Trạng Thái</th>
                            <th>Chọn Nổi Bật</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>";

                // Lặp qua các dòng dữ liệu và hiển thị
                while ($row = $result->fetch_assoc()) {
                    $featured_icon = $row['is_featured'] == 1 ? "<span class='featured-icon'>★</span>" : "";
                    $toggle_text = $row['is_featured'] == 1 ? "Tắt Nổi Bật" : "Đặt Nổi Bật";
                    $toggle_class = $row['is_featured'] == 1 ? "btn-danger" : "btn-info";
                    $toggle_action = $row['is_featured'] == 1 ? "unset_featured" : "set_featured";
                    echo "<tr id='row_" . $row['id'] . "'>
                        <td>" . $row['id'] . "</td>
                        <td>" . $row['tieu_de'] . "</td>
                        <td>" . $row['ngay_tao'] . "</td>
                        <td>" . $row['trang_thai'] . "</td>
                        <td>" . $featured_icon . "</td>
                        <td>
                            <a href='#' class='btn " . $toggle_class . " btn-actions toggle-featured' data-id='" . $row['id'] . "'>" . $toggle_text . "</a>
                            <a href='sua_tintuc.php?id=" . $row['id'] . "' class='btn btn-warning btn-actions'>Sửa</a>
                            <a href='xoa_tintuc.php?id=" . $row['id'] . "' class='btn btn-danger btn-actions' onclick='return confirm(\"Bạn có chắc chắn muốn xóa bài viết này?\");'>Xóa</a>
                        </td>
                      </tr>";
                }

                echo "</tbody></table>";
            } else {
                echo "<p class='text-center'>Không có tin tức nào!</p>";
            }

            // Đóng kết nối
            $conn->close();
            ?>
        </div>

        <!-- Nút quay lại trang chủ -->
        <div class="back-home-btn">
            <a href="dashboard.php" class="btn btn-primary">Quay lại Trang Chủ</a>
        </div>
    </div>

    <!-- Thêm Bootstrap JS để các thành phần hoạt động -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Thêm jQuery và AJAX để thay đổi trạng thái trực tiếp -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Khi nhấn vào nút "Đặt/Tắt Nổi Bật"
            $('.toggle-featured').on('click', function() {
                var id = $(this).data('id');
                var $this = $(this);

                // Gửi yêu cầu AJAX để cập nhật trạng thái 'is_featured'
                $.ajax({
                    url: 'toggle_featured.php',
                    type: 'GET',
                    data: {
                        id: id
                    },
                    success: function(response) {
                        // Thay đổi nút và biểu tượng sau khi cập nhật thành công
                        if (response == 'set') {
                            $this.removeClass('btn-info').addClass('btn-danger').text('Tắt Nổi Bật');
                            $('#row_' + id + ' .featured-icon').text('★');
                        } else if (response == 'unset') {
                            $this.removeClass('btn-danger').addClass('btn-info').text('Đặt Nổi Bật');
                            $('#row_' + id + ' .featured-icon').text('');
                        }
                    }
                });
            });
        });
    </script>

</body>

</html>