<?php
class Report {
    private $db;
    public function __construct() { $this->db = Database::getInstance()->getConnection(); }

    public function getStats($userId = null) {
        $sql = "SELECT 
                COUNT(id) as total_deals,
                SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) as closed_deals,
                SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress_deals,
                SUM(CASE WHEN status = 'new' THEN 1 ELSE 0 END) as new_deals,
                SUM(amount) as total_revenue
                FROM deals";
                
        if ($userId) {
            $sql .= " WHERE manager_id = :uid";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['uid' => $userId]);
            return $stmt->fetch();
        }
        return $this->db->query($sql)->fetch();
    }
}