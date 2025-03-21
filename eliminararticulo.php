<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("location: login.php");
    exit();
}

require 'db_connect.php';

$usuario_id = $_SESSION["user_id"];

if (isset($_POST["articulo_id"])) {
    $articulo_id_eliminar = $_POST["articulo_id"];

    $eliminar_sql = "DELETE FROM carritos WHERE usuario_id = $usuario_id AND articulo_id = $articulo_id_eliminar";
    $conexion->query($eliminar_sql);
}

$conexion->close();

header("location: carrito.php");
exit();
