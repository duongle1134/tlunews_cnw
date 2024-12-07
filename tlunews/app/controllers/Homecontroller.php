<?php

class HomeController
{
    private $viewPath = './app/views/home/';

    public function index()
    {
        // Dữ liệu sẽ truyền vào view, có thể thêm vào khi cần thiết
        $data = [
            'title' => 'Trang chủ',
            'message' => 'Chào mừng bạn đến với trang chủ của chúng tôi!'
        ];

        // Gọi phương thức loadView để load view
        $this->loadView('index', $data);
    }

    private function loadView($view, $data = [])
    {
        $viewFile = $this->viewPath . $view . '.php';

        // Kiểm tra xem view có tồn tại hay không
        if (!file_exists($viewFile)) {
            throw new Exception("View file không tồn tại: {$viewFile}");
        }

        // Truyền dữ liệu vào view
        extract($data);

        // Buffer output để tránh lỗi khi có nhiều content
        ob_start();
        include_once($viewFile);
        $content = ob_get_clean();

        // Load layout nếu có
        include_once('./app/views/layout.php');
    }
}
