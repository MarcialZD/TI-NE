<?php
if (isset($_POST["Registrar"]))
{
    // Conectar a la base de datos (reemplaza 'usuario', 'contraseña' y 'nombre_base_de_datos' con tus propios valores)
    $conexion = new mysqli("localhost", "root", "123456", "nutricode");

    if ($conexion->connect_error)
    {
        die("La conexión a la base de datos falló: " . $conexion->connect_error);
    }

    $nombres = $_POST["nombres"];
    $apellidos = $_POST["apellidos"];
    $fecha_nacimiento = $_POST["fecha_nacimiento"];
    $username = $_POST["username"];
    $correo = $_POST["correo"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Verificar si las contraseñas coinciden
    $confirmar_password = $_POST["confirmar_password"];
    if ($_POST["password"] != $confirmar_password)
    {
        $destino = "login.php?registro.php";
        echo '<script>alert("Las contraseñas no coinciden"); window.location.href = "' . $destino . '";</script>';
    }

    // Insertar el nuevo usuario en la tabla
    $sql = "INSERT INTO usuarios (nombres, apellidos, fecha_nacimiento, username, correo, password) 
            VALUES ('$nombres', '$apellidos', '$fecha_nacimiento', '$username', '$correo', '$password')";

    if ($conexion->query($sql) === TRUE)
    {
        $destino = "login.php?login.php";
        echo '<script>alert("Registro exitoso"); window.location.href = "' . $destino . '";</script>';
    exit();
        
    }
    else
    {
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
        <title>Inicio</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <script src="js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="estilos/Stylle_registro.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>

    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top shadow-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="Principal.php">
                    <img src="img/NutriCode_logo_sin_fondo.png" width="30" height="30" alt="">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="Principal.php" style="color: #000000;">
                                Inicio
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="servicios.php" style="color: #000000;">Servicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="quienesSomos.php" style="color: #000000;">¿Quienes somos?</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="productos.php" style="color: #000000;"></i>Productos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="carrito.php" style="color: #000000;"><i class="fa-solid fa-user"></i>Cuenta</a>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>


        <form action="registro.php" method="post">
            <h1 class="title">Regístrate!</h1>
            <label for="nombres">
                <input placeholder="Nombres" type="text" name="nombres" id="nombres" autocomplete="off" required>
            </label>
            <label for="apellidos">
                <input placeholder="apellidos" type="text" name="apellidos" id="apellidos" autocomplete="off" required>
            </label>
            <label for="fecha_nacimiento">
                <p>Fecha de nacimiento</p>
                <input placeholder="Fecha de nacimiento" type="date" name="fecha_nacimiento" id="fecha_nac" autocomplete="off" required>
            </label>
            <label for="username">
                <input placeholder="nombre de usuario" type="text" name="username" id="username1" autocomplete="off" required>
            </label>
            <label for="correo">
                <input placeholder="correo electrónico" type="text" name="correo" id="username2" autocomplete="off" required>
            </label>
            <label for="password">
                <input placeholder="contraseña" type="password" name="password" id="password3" autocomplete="off" required>
            </label>
            <label for="confirmar_password">
                <input placeholder="confirmar contraseña" type="password" name="confirmar_password" id="password4" autocomplete="off" required>
            </label>
            <a href="login.php" style="margin: 5px;">Regresar</a>
            <input type="submit" name="Registrar" value="Registrarme" id="enviar">
        </form>


        <script>

        </script>



    </body>

</html>


