<?php
class Task {
    private $db;
    public function __construct() { $this->db = Database::getInstance()->getConnection(); }

    public function getAll($userId = null) {
        $sql = "SELECT t.*, u.name as assigned_name FROM tasks t JOIN users u ON t.assigned_to = u.id";
        if ($userId) {
            $sql .= " WHERE t.assigned_to = :uid";
            $stmt = $this->db->prepare($sql . " ORDER BY t.due_date ASC");
            $stmt->execute(['uid' => $userId]);
            return $stmt->fetchAll();
        }
        return $this->db->query($sql . " ORDER BY t.due_date ASC")->fetchAll();
    }

    public function create($assignedTo, $title, $description, $dueDate) {
        $stmt = $this->db->prepare("INSERT INTO tasks (assigned_to, title, description, due_date) VALUES (:uid, :title, :desc, :due)");
        return $stmt->execute(['uid' => $assignedTo, 'title' => $title, 'desc' => $description, 'due' => $dueDate]);
    }

    public function complete($id) {
        $stmt = $this->db->prepare("UPDATE tasks SET status = 'completed' WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}