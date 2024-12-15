<?php

class Database {
    private string $host = 'localhost';
    private string $db   = 'your_database_name';
    private string $user = 'your_username';
    private string $pass = 'your_password';
    private string $charset = 'utf8mb4';
    private ?PDO $pdo = null; 

    public function __construct() {
        $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        
        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getPdo(): ?PDO {
        return $this->pdo;
    }
}

class User {
    private PDO $db;

    public function __construct(Database $database) {
        $this->db = $database->getPdo();
    }

    public function getAllUsers(): array {
        $stmt = $this->db->query('SELECT * FROM users');
        return $stmt->fetchAll();
    }

    public function updateUser(int $id, array $data): bool {
        
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $exists = $stmt->fetchColumn();

        if (!$exists) {
            return false; 
        }

        
        $stmt = $this->db->prepare('UPDATE users SET name = ?, email = ? WHERE id = ?');
        return $stmt->execute([$data['name'], $data['email'], $id]);
    }
}


$database = new Database();
$user = new User($database);


$users = $user->getAllUsers();
foreach ($users as $userRecord) {
    echo "ID: " . $userRecord['id'] . " | Name: " . $userRecord['name'] . " | Email: " . $userRecord['email'] . "<br>";
}


$updateData = [
    'name' => 'New Name',
    'email' => 'newemail@example.com'
];

if ($user->updateUser(1, $updateData)) {
    echo "User successfully updated.";
} else {
    echo "User with this ID not found.";
}
