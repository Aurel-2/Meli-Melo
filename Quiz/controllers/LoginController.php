<?php

namespace controllers;

use PDO;

class LoginController
{
    private PDO $database;

    public function __construct($database)
    {
        $this->database = $database;
    }


    public function login($username, $password): bool
    {
        $sql = "SELECT * FROM users WHERE username = :username AND password = :password";
        $stmt = $this->database->prepare($sql);
        $stmt->execute([
            ':username' => $username,
            ':password' => $password
        ]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            return true;
        }
        return false;
    }

    public function isConnected(): bool
    {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    public function logout()
    {
        session_destroy();
        header("Location: QuizView.php");
        exit;
    }

}