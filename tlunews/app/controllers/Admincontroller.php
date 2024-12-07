<?php

class AdminController
{
    private $isAuthenticated = false;
    private $db;
    private $viewPath = './app/views/admin/';

    public function __construct()
    {
        // Khởi tạo session nếu chưa có
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Kiểm tra trạng thái đăng nhập
        $this->checkAuth();
    }

    private function checkAuth()
    {
        $this->isAuthenticated = isset($_SESSION['admin_logged_in'])
            && $_SESSION['admin_logged_in'] === true
            && isset($_SESSION['admin_id'])
            && isset($_SESSION['last_activity']);

        // Kiểm tra thời gian timeout session (30 phút)
        if ($this->isAuthenticated && (time() - $_SESSION['last_activity'] > 1800)) {
            $this->logout();
            return false;
        }

        // Cập nhật thời gian hoạt động
        if ($this->isAuthenticated) {
            $_SESSION['last_activity'] = time();
        }

        return $this->isAuthenticated;
    }

    public function dashboard()
    {
        if (!$this->isAuthenticated) {
            $this->redirectToLogin();
            return;
        }

        try {
            $data = [
                'title' => 'Dashboard',
                'admin_name' => $_SESSION['admin_name'] ?? 'Admin'
            ];
            $this->loadView('dashboard', $data);
        } catch (Exception $e) {
            $this->handleError('Lỗi tải trang Dashboard: ' . $e->getMessage());
        }
    }

    public function manage_users()
    {
        if (!$this->isAuthenticated) {
            $this->redirectToLogin();
            return;
        }

        try {
            // Lấy danh sách người dùng từ model
            $users = UserModel::getAllUsers();

            $data = [
                'title' => 'Quản lý người dùng',
                'users' => $users
            ];
            $this->loadView('manage_users', $data);
        } catch (Exception $e) {
            $this->handleError('Lỗi tải trang Quản lý người dùng: ' . $e->getMessage());
        }
    }

    public function manage_news()
    {
        if (!$this->isAuthenticated) {
            $this->redirectToLogin();
            return;
        }

        try {
            // Lấy danh sách tin tức từ model
            $news = NewsModel::getAllNews();

            $data = [
                'title' => 'Quản lý tin tức',
                'news' => $news
            ];
            $this->loadView('manage_news', $data);
        } catch (Exception $e) {
            $this->handleError('Lỗi tải trang Quản lý tin tức: ' . $e->getMessage());
        }
    }

    public function login()
    {
        // Nếu đã đăng nhập thì chuyển về dashboard
        if ($this->isAuthenticated) {
            $this->redirect('dashboard');
            return;
        }

        try {
            $data = [
                'title' => 'Đăng nhập',
                'error' => $_SESSION['login_error'] ?? null
            ];
            unset($_SESSION['login_error']);
            $this->loadView('login', $data);
        } catch (Exception $e) {
            $this->handleError('Lỗi tải trang Đăng nhập: ' . $e->getMessage());
        }
    }

    public function logout()
    {
        // Xóa session
        session_destroy();
        $_SESSION = array();

        // Xóa cookie session nếu có
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        $this->redirectToLogin();
    }

    private function loadView($view, $data = [])
    {
        $viewFile = $this->viewPath . $view . '.php';

        if (!file_exists($viewFile)) {
            throw new Exception("View file không tồn tại: {$viewFile}");
        }

        // Extract data để sử dụng trong view
        extract($data);

        // Buffer output
        ob_start();
        include_once($viewFile);
        $content = ob_get_clean();

        // Load layout nếu cần
        include_once($this->viewPath . 'layout.php');
    }

    private function redirect($path)
    {
        header("Location: /admin/{$path}");
        exit;
    }

    private function redirectToLogin()
    {
        $_SESSION['login_error'] = 'Vui lòng đăng nhập để tiếp tục';
        $this->redirect('login');
    }

    private function handleError($message)
    {
        // Log lỗi
        error_log($message);

        // Hiển thị trang lỗi
        $data = [
            'title' => 'Lỗi',
            'message' => $message
        ];

        try {
            $this->loadView('error', $data);
        } catch (Exception $e) {
            // Fallback khi không load được trang lỗi
            die('Đã xảy ra lỗi: ' . $message);
        }
    }

    // Các phương thức bảo mật bổ sung
    private function validateCSRF()
    {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $this->handleError('CSRF token không hợp lệ');
            return false;
        }
        return true;
    }

    private function generateCSRFToken()
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}
