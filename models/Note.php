<?php
class Note {
    private $db;
    public function __construct() { $this->db = Database::getInstance()->getConnection(); }

    public function getByDeal($dealId) {
        $stmt = $this->db->prepare("SELECT n.*, u.name as user_name FROM notes n JOIN users u ON n.user_id = u.id WHERE n.deal_id = :did ORDER BY n.created_at ASC");
        $stmt->execute(['did' => $dealId]);
        return $stmt->fetchAll();
    }

    public function add($dealId, $userId, $content, $type = 'comment') {
        $stmt = $this->db->prepare("INSERT INTO notes (deal_id, user_id, content, type) VALUES (:did, :uid, :content, :type)");
        return $stmt->execute(['did' => $dealId, 'uid' => $userId, 'content' => $content, 'type' => $type]);
    }
}