<?php
class Deal {
    private $db;
    public function __construct() { $this->db = Database::getInstance()->getConnection(); }

    public function getAll($userId = null) {
        $sql = "SELECT d.*, c.first_name, c.last_name, u.name as manager_name 
                FROM deals d 
                LEFT JOIN contacts c ON d.contact_id = c.id 
                JOIN users u ON d.manager_id = u.id";
        
        if ($userId) {
            $sql .= " WHERE d.manager_id = :uid";
            $stmt = $this->db->prepare($sql . " ORDER BY d.created_at DESC");
            $stmt->execute(['uid' => $userId]);
            return $stmt->fetchAll();
        }
        return $this->db->query($sql . " ORDER BY d.created_at DESC")->fetchAll();
    }

    public function create($contactId, $managerId, $title, $amount) {
        $stmt = $this->db->prepare("INSERT INTO deals (contact_id, manager_id, title, amount) VALUES (:cid, :mid, :title, :amount)");
        $stmt->execute(['cid' => $contactId, 'mid' => $managerId, 'title' => $title, 'amount' => $amount]);
        return $this->db->lastInsertId();
    }

    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE deals SET status = :status WHERE id = :id");
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }

    public function delete($id) {
        $this->db->prepare("DELETE FROM notes WHERE deal_id = :id")->execute(['id' => $id]);
        
        $this->db->prepare("DELETE FROM tasks WHERE deal_id = :id")->execute(['id' => $id]);
        
        $stmt = $this->db->prepare("DELETE FROM deals WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}