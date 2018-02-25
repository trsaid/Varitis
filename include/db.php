<?php
//Variables DB
define('HOST', 'localhost');
define('USER', 'site');
define('PASSWORD', 'said');
define('DATABASE', 'test');

function DB()
{
    try {
        $db = new PDO('mysql:host='.HOST.';dbname='.DATABASE.'', USER, PASSWORD);
        return $db;
    } catch (PDOException $e) {
        return "Error!: " . $e->getMessage();
        die();
    }
}
?>