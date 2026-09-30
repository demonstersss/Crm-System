<?php
session_start();

require_once 'autoload.php';


$action = $_GET['action'] ?? 'dashboard';
$authController = new AuthController();

switch ($action) {
    case 'login':
        $authController->login();
        break;
    case 'logout':
        $authController->logout();
        break;
    case 'setup':
        $userModel = new User();
        $userModel->createTestAdmin();
        echo "Тестовый администратор создан! <a href='index.php?action=login'>Войти</a>";
        break;
    case 'companies':
        $crmController = new CRMController();
        $crmController->companies();
        break;
    case 'deals':
        $crmController = new CRMController();
        $crmController->deals();
        break;
    case 'tasks':
        $crmController = new CRMController();
        $crmController->tasks();
        break;
    case 'users':
        $crmController = new CRMController();
        $crmController->users();
        break;
    case 'dashboard':
    default:
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }
        $crmController = new CRMController();
        $crmController->dashboard();
        break;
}

?>