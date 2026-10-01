<?php
class Meeting {
    private $db;
    
    public function __construct() { 
        $this->db = Database::getInstance()->getConnection(); 
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM meetings WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getAll() {
        $sql = "SELECT m.*, u.name as user_name, c.first_name, c.last_name 
                FROM meetings m 
                JOIN users u ON m.user_id = u.id 
                LEFT JOIN contacts c ON m.contact_id = c.id 
                ORDER BY m.meeting_date ASC";
        return $this->db->query($sql)->fetchAll();
    }

    public function getUpcoming($limit = 5) {
        $sql = "SELECT m.*, u.name as user_name, c.first_name, c.last_name 
                FROM meetings m 
                JOIN users u ON m.user_id = u.id 
                LEFT JOIN contacts c ON m.contact_id = c.id 
                WHERE m.meeting_date >= NOW()
                ORDER BY m.meeting_date ASC LIMIT " . (int)$limit;
        return $this->db->query($sql)->fetchAll();
    }

    public function create($userId, $contactId, $title, $meetingDate) {
        $stmt = $this->db->prepare("INSERT INTO meetings (user_id, contact_id, title, meeting_date) VALUES (:uid, :cid, :title, :date)");
        return $stmt->execute(['uid' => $userId, 'cid' => $contactId, 'title' => $title, 'date' => $meetingDate]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM meetings WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}