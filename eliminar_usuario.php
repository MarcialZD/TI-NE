<?php

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["user_id"]))
{
    $user_id = $_POST["user_id"];

require 'db_connect.php';
   

    $sql = "DELETE FROM usuarios WHERE id=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute())
    {
        header("location: admin_interface.php");
    }
    else
    {
        echo "Error al eliminar el usuario: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close();
}
else
{
    header("location: admin_interface.php");
}
