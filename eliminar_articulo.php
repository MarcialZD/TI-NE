<?php

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["articulo_id"]))
{
    $articulo_id = $_POST["articulo_id"];

require 'db_connect.php';
  

    $sql = "DELETE FROM articulos WHERE id=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $articulo_id);

    if ($stmt->execute())
    {
        echo "<script>alert('Articulo eliminado'); window.location.href = 'admin_interface.php';</script>";

        exit(); 
    }
    else
    {
        echo "Error al eliminar el articulo: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close();
}
else
{
    header("location: admin_interface_productos.php");
    exit(); 
}

