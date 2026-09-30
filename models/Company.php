<?php
class Company {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        return $this->db->query("SELECT * FROM companies ORDER BY created_at DESC")->fetchAll();
    }

    public function create($name, $address) {
        $stmt = $this->db->prepare("INSERT INTO companies (name, address) VALUES (:name, :address)");
        return $stmt->execute(['name' => $name, 'address' => $address]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("SELECT id FROM contacts WHERE company_id = :id");
        $stmt->execute(['id' => $id]);
        $contacts = $stmt->fetchAll();

        $contactModel = new Contact();
        foreach ($contacts as $contact) {
            $contactModel->delete($contact['id']);
        }

        $stmt = $this->db->prepare("DELETE FROM companies WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}