<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["username"])) {
    header("location: login.php");
    exit();
}

require 'db_connect.php';

// Obtener el ID del usuario actual
$usuario_id = $_SESSION["user_id"];

// Verificar si se recibió el ID del artículo a eliminar
if (isset($_POST["articulo_id"])) {
    $articulo_id_eliminar = $_POST["articulo_id"];

    // Eliminar el artículo del carrito
    $eliminar_sql = "DELETE FROM carritos WHERE usuario_id = $usuario_id AND articulo_id = $articulo_id_eliminar";
    $conexion->query($eliminar_sql);
}

// Cerrar la conexión después de eliminar el artículo
$conexion->close();

// Redirigir de nuevo a carrito.php
header("location: carrito.php");
exit();
