<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
require 'db_connect.php';
   

    $nombres = $_POST["txtNombres"];
    $apellidos = $_POST["txtApellidos"];
    $fecha_nacimiento = $_POST["txtFechaNacimiento"];
    $username = $_POST["txtUsername"];
    $correo = $_POST["txtCorreo"];
    $password = password_hash($_POST["txtPassword"], PASSWORD_DEFAULT);
    $es_admin = $_POST["txtEsAdmin"];

    $sql = "INSERT INTO usuarios (nombres, apellidos, fecha_nacimiento, username, correo, password, es_admin) 
            VALUES ('$nombres', '$apellidos', '$fecha_nacimiento', '$username', '$correo', '$password', '$es_admin')";

    if ($conexion->query($sql) === TRUE) {
        $destino = "admin_interface.php";
        echo '<script>alert("Usuario Añadido"); window.location.href = "' . $destino . '";</script>';
        exit();
    } else {
        echo "Error al registrar el usuario: " . $conexion->error;
    }

    $conexion->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Usuario</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-VX18B9GBD3');
</script>
<style>
    
    form {
    display: flex;
    flex-direction: column;
    background-color: #faeae5;
    padding: 20px 5%; 
    box-shadow: 0px 5px 10px rgba(255, 255, 255, 0.7);
    width: 30%; 
    margin-left: 35%;
    margin-top: 15%;
  
}

form .title {
    color: #020202;
    font-size: 2rem; 
    font-weight: 800;
    margin-bottom: 30px;
    margin-top: 1%;
}

form label {
    display: flex; 
    align-items: center; 
    margin-bottom: 25px; 
}

form label .fa-solid {
    font-size: 1.5rem; 
    color: rgb(7, 7, 7);
    margin-right: 10px; 
}

form label input {
    outline: none;
    border: none;
    color: #070707;
    border-bottom: solid 1px rgb(255, 255, 255);
    padding: 5px; 
    font-size: 1.2rem; 
    flex: 1; 
}

form label input::placeholder {
    color: rgba(37, 37, 37, 0.5);
}

form .link {
    color: rgba(37, 37, 37, 0.5);
    margin-bottom: 15px;
    font-size: 1rem; 
}

form #enviar {
    border: none;
    padding: 10px 15px; 
    cursor: pointer;
    font-size: 1.2rem;
    background-color: #e881a2;
    color: #faeae5;
}
nav {
            background-color: #faeae5;
            text-align: center;
        }

        .navbar-custom {
            background-color: #faeae5
        }

        .navbar-custom .navbar-nav {
            background-color: #faeae5
        }
</style>
</head>

<body>
<nav class="navbar navbar-expand-lg fixed-top shadow-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin_interface.php">
                <img src="img/NutriCode_logo_sin_fondo.png" width="100" height="90" alt="Log Dargel Repsoteria">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="admin_interface.php" style="color: #000000;">Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_interface_productos.php" style="color: #000000;">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_interface_ventas.php" style="color: #000000;">Ventas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cerrarsesion.php" style="color: #000000;">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <form action="agregarUsuario.php" method="post" class="mx-auto" style="max-width: 400px;">
            <h1 class="h3 mb-3 fw-normal text-center">Añadir Usuario</h1>
            <div class="mb-3">
                <label for="txtApellidos" class="form-label">Apellidos:</label>
                <input type="text" name="txtApellidos" class="form-control" required value="">
            </div>
            <div class="mb-3">
                <label for="txtNombres" class="form-label">Nombres:</label>
                <input type="text" name="txtNombres" class="form-control" required value="">
            </div>
            <div class="mb-3">
                <label for="txtFechaNacimiento" class="form-label">Fecha de Nacimiento:</label>
                <input type="date" name="txtFechaNacimiento" class="form-control" required value="">
            </div>
            <div class="mb-3">
                <label for="txtUsername" class="form-label">Username:</label>
                <input type="text" name="txtUsername" class="form-control" required value="">
            </div>
            <div class="mb-3">
                <label for="txtCorreo" class="form-label">Correo:</label>
                <input type="text" name="txtCorreo" class="form-control" required value="">
            </div>
            <div class="mb-3">
                <label for="txtPassword" class="form-label">Contraseña:</label>
                <input type="password" name="txtPassword" class="form-control" required value="">
            </div>
            <div class="mb-3">
                <label for="txtEsAdmin" class="form-label">Es Admin:</label>
                <input type="text" name="txtEsAdmin" class="form-control" required value="">
            </div>
            <button class="btn btn-primary" type="submit">Agregar usuario</button>
            <a href="admin_interface.php" class="btn btn-secondary">Regresar</a>
        </form>
    </div>

</body>

</html>
