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
    <link rel="stylesheet" href="estilos/Stylle_registro.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-VX18B9GBD3');
</script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top shadow-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin_interface.php">
                <img src="img/NutriCode_logo_sin_fondo.png" width="30" height="30" alt="">
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
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
