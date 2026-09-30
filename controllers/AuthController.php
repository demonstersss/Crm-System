<?php
class AuthController {
    public function login() {
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php?action=dashboard");
            exit;
        }

        
        
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $userModel = new User();
            $user = $userModel->authenticate($email, $password);

            if ($user === 'inactive') {
                $error = "Ваш аккаунт деактивирован. Обратитесь к администратору.";
            } elseif ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['role'] = $user['role'];
                
                header("Location: index.php?action=dashboard");
                exit;
            } else {
                $error = "Неверный Email или пароль";
            }
        }
        require_once __DIR__ . '/../views/login.php';
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?action=login");
        exit;
    }
}