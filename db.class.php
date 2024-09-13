<?php

class Database{
    public static function conectar(){
        $host = getenv('DB_HOST');
        $dbname = getenv('DB_NAME');
        $port = getenv('DB_PORT');
        $username = getenv('DB_USER');
        $password = getenv('DB_PWD');
        
        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
        $pdo = new PDO($dsn, $username, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        
        return $pdo;
    }
}

?>
