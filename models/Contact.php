<?php
class Contact {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByCompanyId($companyId) {
        $stmt = $this->db->prepare("SELECT * FROM contacts WHERE company_id = :cid");
        $stmt->execute(['cid' => $companyId]);
        return $stmt->fetchAll();
    }

    public function create($companyId, $firstName, $lastName, $phone, $email) {
        $stmt = $this->db->prepare("INSERT INTO contacts (company_id, first_name, last_name, phone, email) VALUES (:cid, :fname, :lname, :phone, :email)");
        return $stmt->execute([
            'cid' => $companyId, 
            'fname' => $firstName, 
            'lname' => $lastName, 
            'phone' => $phone, 
            'email' => $email
        ]);
    }
    public function delete($id) {
        $this->db->prepare("UPDATE deals SET contact_id = NULL WHERE contact_id = :id")->execute(['id' => $id]);
        $this->db->prepare("UPDATE meetings SET contact_id = NULL WHERE contact_id = :id")->execute(['id' => $id]);

        $stmt = $this->db->prepare("DELETE FROM contacts WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    
}
?>