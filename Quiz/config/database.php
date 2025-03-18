<?php
$dsn = 'mysql:host=localhost;dbname=db_quiz;charset=utf8';
$user = 'root';
$password = '';
try {
    $database = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}