<?php
// db_connect.php

// $host = "sql101.infinityfree.com";
// $user = "if0_37311818";
// $password = "Ql8Biwu7fymI5s";
// $database = "if0_37311818_nutricode";


$host = "localhost";
$user = "root";
$password = "123456";
$database = "nutricode";

$conexion = new mysqli($host, $user, $password, $database);

if ($conexion->connect_error) {
    die("La conexión a la base de datos falló: " . $conexion->connect_error);
}
