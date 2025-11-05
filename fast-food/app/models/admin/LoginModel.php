<?php

class loginModel
{
    private $conn;
    private $table = "users";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function checkLogin($user, $password)
    {
        $queryLogin = "SELECT * FROM {$this->table} WHERE phone = :user OR email = :user LIMIT 1";
        $stmt = $this->conn->prepare($queryLogin);
        $stmt->bindParam(':user', $user);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $admin['password'])) {
                return $admin;
            }
        }
        return false;
    }
}
