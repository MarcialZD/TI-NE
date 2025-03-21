<?php

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["articulo_id"]))
{
    // Obtener el ID del usuario a eliminar
    $articulo_id = $_POST["articulo_id"];

    // Conectar a la base de datos (reemplazar con tus propios valores)
require 'db_connect.php';
  

    // Consultar la base de datos para eliminar el usuario
    $sql = "DELETE FROM articulos WHERE id=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $articulo_id);

    // Ejecutar la consulta
    if ($stmt->execute())
    {
        echo "<script>alert('Articulo eliminado'); window.location.href = 'admin_interface.php';</script>";

        exit(); // Agrega esto para asegurarte de que el script se detenga después de redirigir
    }
    else
    {
        echo "Error al eliminar el articulo: " . $stmt->error;
    }

    // Cerrar el statement y la conexión a la base de datos
    $stmt->close();
    $conexion->close();
}
else
{
    // Si no se ha enviado el formulario, redirigir a la página anterior
    header("location: admin_interface_productos.php");
    exit(); // Agrega esto para asegurarte de que el script se detenga después de redirigir
}

