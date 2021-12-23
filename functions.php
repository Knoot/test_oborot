<?php

function getConnection(): PDO
{
    static $pdo;

    if (!$pdo) {
        $config = include('config.php');
        $pdo = new PDO("mysql:host={$config['host']};dbname={$config['dbname']}", $config['user'], $config['password']);
    }

    return $pdo;
}
