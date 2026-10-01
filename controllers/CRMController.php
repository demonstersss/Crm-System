<?php
class CRMController {
    private $db;
    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }
        $this->db = Database::getInstance()->getConnection();
    }

    public function companies() {
        $companyModel = new Company();
        $contactModel = new Contact();
        
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['add_company'])) {
                $companyModel->create($_POST['name'], $_POST['address']);
                $success = 'Компания успешно добавлена.';
            } 
            elseif (isset($_POST['add_contact'])) {
                $contactModel->create($_POST['company_id'], $_POST['first_name'], $_POST['last_name'], $_POST['phone'], $_POST['email']);
                $success = 'Контакт привязан к компании.';
            } 
            elseif (isset($_POST['delete_company'])) {
                if (User::isAdmin()) {
                    $companyModel->delete($_POST['company_id']);
                    $success = 'Компания удалена. Связанные сделки отвязаны и сохранены.';
                } else {
                    $error = 'Ошибка доступа: Удаление разрешено только администратору!';
                }
            } 
            elseif (isset($_POST['delete_contact'])) {
                if (User::isAdmin()) {
                    $contactModel->delete($_POST['contact_id']);
                    $success = 'Контакт удален. Связанные с ним сделки остались в системе.';
                } else {
                    $error = 'Удалять контакты может только администратор!';
                }
            }
        }

        $companies = $companyModel->getAll();
        
        foreach ($companies as &$company) {
            $company['contacts'] = $contactModel->getByCompanyId($company['id']);
        }

        $title = 'Компании и Контакты';
        $content = __DIR__ . '/../views/companies.php'; 
        
        require_once __DIR__ . '/../views/layout.php';
    }
    public function deals() {
        $dealModel = new Deal();
        $noteModel = new Note();
        $error = ''; $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['add_deal'])) {
                $dealId = $dealModel->create($_POST['contact_id'], $_SESSION['user_id'], $_POST['title'], $_POST['amount']);
                $noteModel->add($dealId, $_SESSION['user_id'], "Сделка создана", "history_log");
                $success = 'Сделка успешно создана.';
            } elseif (isset($_POST['change_status'])) {
                $dealModel->updateStatus($_POST['deal_id'], $_POST['status']);
                $noteModel->add($_POST['deal_id'], $_SESSION['user_id'], "Статус изменен на: " . $_POST['status'], "history_log");
                $success = 'Статус обновлен.';
            } elseif (isset($_POST['add_comment'])) {
                $noteModel->add($_POST['deal_id'], $_SESSION['user_id'], $_POST['content'], "comment");
                $success = 'Комментарий добавлен.';
            } elseif (isset($_POST['delete_deal']) && User::isAdmin()) {
                $dealModel->delete($_POST['deal_id']);
                $success = 'Сделка удалена.';
            }
        }

        $contactModel = new Contact();
        $allContacts = $this->db->query("SELECT id, first_name, last_name FROM contacts")->fetchAll();

        $filterId = User::isAdmin() ? null : $_SESSION['user_id'];
        $deals = $dealModel->getAll($filterId);

        $deals = $dealModel->getAll($filterId);

        foreach ($deals as &$deal) {
            $deal['notes'] = $noteModel->getByDeal($deal['id']);
        }
        unset($deal); 

        $title = 'Управление сделками';
        $content = __DIR__ . '/../views/deals.php';
        require_once __DIR__ . '/../views/layout.php';
    }

    public function tasks() {
        $taskModel = new Task();
        $meetingModel = new Meeting();
        $error = ''; $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['add_task'])) {
                $taskModel->create($_POST['assigned_to'], $_POST['title'], $_POST['description'], $_POST['due_date']);
                $success = 'Задача добавлена.';
            } elseif (isset($_POST['complete_task'])) {
                $taskModel->complete($_POST['task_id']);
                $success = 'Задача выполнена!';
            } elseif (isset($_POST['add_meeting'])) {
                $meetingModel->create($_SESSION['user_id'], $_POST['contact_id'], $_POST['title'], $_POST['meeting_date']);
                $success = 'Встреча успешно назначена.';
            } elseif (isset($_POST['delete_meeting'])) {
                $meeting = $meetingModel->getById($_POST['meeting_id']);
                
                if (strtotime($meeting['meeting_date']) < time()) {
                    $error = 'Ошибка: Невозможно отменить уже прошедшую встречу!';
                } else {
                    if (User::isAdmin() || $meeting['user_id'] == $_SESSION['user_id']) {
                        $meetingModel->delete($_POST['meeting_id']);
                        $success = 'Встреча отменена.';
                    } else {
                        $error = 'Ошибка: Вы не можете отменять встречи других менеджеров!';
                    }
                }
            }
            
        }

        $filterId = User::isAdmin() ? null : $_SESSION['user_id'];
        $tasks = $taskModel->getAll($filterId);
        
        $meetings = $meetingModel->getAll();
        
        $allContacts = $this->db->query("SELECT id, first_name, last_name FROM contacts")->fetchAll();

        $usersForAssign = [];
        if (User::isAdmin()) {
            $usersForAssign = $this->db->query("SELECT id, name FROM users")->fetchAll();
        }

        $title = 'Задачи и Встречи';
        $content = __DIR__ . '/../views/tasks.php';
        require_once __DIR__ . '/../views/layout.php';
    }
    public function dashboard() {
        $reportModel = new Report();
        
        $filterId = User::isAdmin() ? null : $_SESSION['user_id'];
        $stats = $reportModel->getStats($filterId);

        $totalDeals = $stats['total_deals'] ?: 0;
        $closedDeals = $stats['closed_deals'] ?: 0;
        
        $conversion = 0;
        if ($totalDeals > 0) {
            $conversion = round(($closedDeals / $totalDeals) * 100, 1);
        }
        $dealModel = new Deal();
        $meetingModel = new Meeting();

        $activeDeals = $dealModel->getActiveDeals($filterId, 5);
        
        $upcomingMeetings = $meetingModel->getUpcoming(5);

        $title = 'Дашборд и Аналитика';
        $content = __DIR__ . '/../views/dashboard.php';
        require_once __DIR__ . '/../views/layout.php';
    }
    public function users() {
        if (!User::isAdmin()) {
            die('<div style="background:#111; color:#ff6b6b; padding:30px; text-align:center; font-family:sans-serif;">
                    <h2>Доступ запрещен</h2><p>Управление сотрудниками доступно только Администратору.</p>
                    <a href="index.php?action=dashboard" style="color:#d4af37;">Вернуться на главную</a>
                 </div>');
        }

        $userModel = new User();
        $error = ''; $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['add_user'])) {
                try {
                    $userModel->create($_POST['name'], $_POST['email'], $_POST['password'], $_POST['role']);
                    $success = 'Сотрудник успешно добавлен.';
                } catch (PDOException $e) {
                    $error = 'Ошибка: Пользователь с таким Email уже существует.';
                }
            } elseif (isset($_POST['change_role'])) {
                if ($_POST['user_id'] != $_SESSION['user_id']) { 
                    $userModel->updateRole($_POST['user_id'], $_POST['role']);
                    $success = 'Роль сотрудника обновлена.';
                } else {
                    $error = 'Нельзя изменить роль самому себе!';
                }
            } elseif (isset($_POST['toggle_status'])) {
                if ($_POST['user_id'] != $_SESSION['user_id']) {
                    $userModel->toggleStatus($_POST['user_id']);
                    $success = 'Статус аккаунта изменен.';
                } else {
                    $error = 'Вы не можете деактивировать собственный аккаунт!';
                }
            }
        }

        $usersList = $userModel->getAll();

        $title = 'Управление персоналом';
        $content = __DIR__ . '/../views/users.php';
        require_once __DIR__ . '/../views/layout.php';
    }
}