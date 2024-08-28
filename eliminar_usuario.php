<?php

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["user_id"]))
{
    // Obtener el ID del usuario a eliminar
    $user_id = $_POST["user_id"];

    // Conectar a la base de datos (reemplazar con tus propios valores)
    $conexion = new mysqli("localhost", "root", "123456", "nutricode");

    // Verificar la conexión a la base de datos
    if ($conexion->connect_error)
    {
        die("La conexión a la base de datos falló: " . $conexion->connect_error);
    }

    // Consultar la base de datos para eliminar el usuario
    $sql = "DELETE FROM usuarios WHERE id=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $user_id);

    // Ejecutar la consulta
    if ($stmt->execute())
    {
        header("location: admin_interface.php");
    }
    else
    {
        echo "Error al eliminar el usuario: " . $stmt->error;
    }

    // Cerrar el statement y la conexión a la base de datos
    $stmt->close();
    $conexion->close();
}
else
{
    // Si no se ha enviado el formulario, redirigir a la página anterior
    header("location: admin_interface.php");
}
