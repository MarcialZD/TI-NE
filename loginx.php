<?php

session_start();
include 'db_connect.php';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"]) && isset($_POST["password"]))
{
    $username = $_POST["username"];
    $password = $_POST["password"];

  

    $sql = "SELECT * FROM usuarios WHERE username=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0)
    {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"]))
        {
            $_SESSION["username"] = $username;
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["es_admin"] = $row["es_admin"];

            $destino = ($_SESSION["es_admin"] == 1) ? "admin_interface.php" : "carrito.php";
            echo "<script>alert('Bienvenido, $username'); window.location.href = '$destino';</script>";
        }
        else
        {

            $destino = "login.php?Error-al-Ingresar";
            echo '<script>alert("Error al ingresar"); window.location.href = "' . $destino . '";</script>';
        }
    }
    else
    {
        $destino = "login.php?Error-al-Ingresar";
        echo '<script>alert("Error al ingresar"); window.location.href = "' . $destino . '";</script>';
    }

    $stmt->close();
    $conexion->close();
}
else
{
    header("location:login.php");
}

