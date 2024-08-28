<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["username"])) {
    header("location: login.php");
    exit();
}

// Conectar a la base de datos (reemplaza 'usuario', 'contraseña' y 'nombre_base_de_datos' con tus propios valores)
$conexion = new mysqli("localhost", "root", "123456", "nutricode");

if ($conexion->connect_error) {
    die("La conexión a la base de datos falló: " . $conexion->connect_error);
}

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
