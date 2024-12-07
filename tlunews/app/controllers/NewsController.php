<?php
class NewsController
{

    // Hiển thị danh sách tin tức
    public function list()
    {
        $newsModel = new News(); // Khởi tạo Model cho tin tức
        $newsList = $newsModel->getAllNews(); // Lấy tất cả tin tức từ database
        include_once('./app/views/news/list.php'); // Hiển thị giao diện danh sách tin tức
    }

    // Hiển thị chi tiết tin tức
    public function detail($id)
    {
        $newsModel = new News();
        $newsItem = $newsModel->getNewsById($id); // Lấy tin tức theo ID
        include_once('./app/views/news/detail.php'); // Hiển thị chi tiết tin tức
    }

    // Tạo tin tức mới (cho admin)
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = $_POST['title'];
            $content = $_POST['content'];
            $category_id = $_POST['category_id'];
            $image = $_POST['image']; // Giả sử bạn đã upload ảnh thành công

            $newsModel = new News();
            $newsModel->createNews($title, $content, $category_id, $image); // Tạo tin mới
            header('Location: index.php'); // Sau khi tạo thành công, chuyển về trang chủ
        }

        include_once('./app/views/news/create.php'); // Hiển thị giao diện tạo tin mới
    }
}
