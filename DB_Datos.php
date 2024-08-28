<?php
$user='root';
$password='123456';
try{    
    $dsn = "mysql:host=localhost;dbname=nutricode";
    $dbh = new PDO($dsn, $user,$password);
    //echo 'Conexion satisfactoria';
} catch (Exception $ex) {
    echo $ex->getMessage();
}
