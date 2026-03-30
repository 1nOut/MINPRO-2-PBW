<?php

define('DB_HOST',   'localhost');
define('DB_USER',   'root');        
define('DB_PASS',   '');            
define('DB_NAME',   'db_web_porto');
define('DB_CHARSET','utf8mb4');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$conn->set_charset(DB_CHARSET);

if ($conn->connect_error) {
    die('<div style="font-family:sans-serif;color:red;padding:20px;">
            <strong>Koneksi Database Gagal:</strong> ' . $conn->connect_error . '
        </div>');
}
?>
