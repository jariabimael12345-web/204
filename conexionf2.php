<?php

class Conexion extends PDO
{
    public function __construct()
    {
        $host = '127.0.0.1';
        $database = 'inventario';
        $username = 'root';
        $password = '';

        $dsn = "mysql:host={$host};dbname={$database};charset=utf8mb4";

        parent::__construct($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
}