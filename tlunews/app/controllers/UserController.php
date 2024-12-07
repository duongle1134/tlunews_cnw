<?php

class UserController
{
    private $viewPath = './app/views/user/';

    public function login()
    {
        // Kiểm tra nếu người dùng đã đăng nhập, chuyển hướng đến trang profile
        if ($this->isAuthenticated()) {
            $this->redirect('profile');
            return;
        }

        $data = [
            'title' => 'Đăng nhập',
            'error' => $_SESSION['login_error'] ?? null
        ];

        // Xóa lỗi đăng nhập sau khi đã hiển thị
        unset($_SESSION['login_error']);

        // Load view login
        $this->loadView('login', $data);
    }

    public function register()
    {
        // Kiểm tra nếu người dùng đã đăng nhập, chuyển hướng đến trang profile
        if ($this->isAuthenticated()) {
            $this->redirect('profile');
            return;
        }

        $data = [
            'title' => 'Đăng ký'
        ];

        // Load view register
        $this->loadView('register', $data);
    }

    public function profile()
    {
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (!$this->isAuthenticated()) {
            $this->redirect('login');
            return;
        }

        $data = [
            'title' => 'Trang cá nhân',
            'username' => $_SESSION['username'] ?? 'User'
        ];

        // Load view profile
        $this->loadView('profile', $data);
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

    private function isAuthenticated()
    {
        return isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
    }

    private function redirect($path)
    {
        header("Location: /{$path}");
        exit;
    }
}
