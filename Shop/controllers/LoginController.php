<?php

namespace controllers;

use config\Database;
use PDO;

class LoginController
{
    private Database $database;

    public function __construct()
    {
        $this->database = new Database();
    }

    public function login($username, $password): bool
    {
        $sql = "SELECT * FROM users WHERE username = :username AND password = :password";
        $stmt = $this->database->connect()->prepare($sql);
        $stmt->execute([
            ':username' => $username,
            ':password' => $password
        ]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['username'] = $user['username'];
            return true;
        }
        return false;
    }

    public function isConnected(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public function logout()
    {
        $_SESSION = array();
        session_destroy();
        header("Location: ../views/index.php");
        exit();
    }

}