<?php
class Database {
    private static $instance = null;
    private $pdo;

    private $host = '127.0.0.1';
    private $db = 'crm_system';
    private $user = 'root';
    private $pass = ''; 
    private $port = 3307;

    private function __construct() {
        try {
            $this->pdo = new PDO(
                "mysql:host={$this->host};port={$this->port};dbname={$this->db};charset=utf8mb4", 
                $this->user, 
                $this->pass
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Ошибка подключения к БД: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }   
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}

?>