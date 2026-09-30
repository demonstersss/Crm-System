<?php
class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function authenticate($email, $password) {
        $stmt = $this->db->prepare("SELECT id, name, password_hash, role, status FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            if ($user['status'] === 'inactive') {
                return 'inactive'; 
            }
            return $user;
        }
        return false;
    }

    public function getAll() {
        return $this->db->query("SELECT id, name, email, role, status, created_at FROM users ORDER BY created_at DESC")->fetchAll();    
    }

    public static function isAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    public function getAllManagers() {
        return $this->db->query("SELECT id, name FROM users WHERE role = 'manager'")->fetchAll();
    }

    public function createTestAdmin() {
        $email = 'qwe@qwe';
        $password = 'qwe';
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if (!$stmt->fetch()) {
            $stmt = $this->db->prepare("INSERT INTO users (name, email, password_hash, role) VALUES ('Главный Админ', :email, :pass, 'admin')");
            $stmt->execute(['email' => $email, 'pass' => $hash]);
        }
    }

    public function create($name, $email, $password, $role = 'manager') {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (:name, :email, :hash, :role)");
        return $stmt->execute([
            'name' => $name, 
            'email' => $email, 
            'hash' => $hash, 
            'role' => $role
        ]);
    }
    public function updateRole($id, $role) {
        $stmt = $this->db->prepare("UPDATE users SET role = :role WHERE id = :id");
        return $stmt->execute(['role' => $role, 'id' => $id]);
    }

    public function toggleStatus($id) {
        $stmt = $this->db->prepare("SELECT status FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $current = $stmt->fetchColumn();
        
        $newStatus = ($current === 'active') ? 'inactive' : 'active';
        $stmt = $this->db->prepare("UPDATE users SET status = :status WHERE id = :id");
        return $stmt->execute(['status' => $newStatus, 'id' => $id]);
    }
}

?>  