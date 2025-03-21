<?php
session_start();

// Verificar si el usuario está autenticado y es administrador
if (!isset($_SESSION["username"]) || !isset($_SESSION["es_admin"]) || $_SESSION["es_admin"] != 1) {
    header("location: login.php");
    exit();
}

// Conectar a la base de datos
require 'db_connect.php';


// Obtener todos los usuarios de la base de datos
$sqlUsuarios = "SELECT * FROM usuarios";
$resultadoUsuarios = $conexion->query($sqlUsuarios);

// Obtener todos los artículos de la base de datos
$sqlArticulos = "SELECT * FROM articulos";
$resultadoArticulos = $conexion->query($sqlArticulos);

// Cerrar la conexión a la base de datos
$conexion->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interfaz de Administrador</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="estilos/Style_admin.css">
    <script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-VX18B9GBD3');
</script>
<style>
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

        /* CSS */
        .custom-toggler {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3E%3Cpath stroke='black' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
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






    <br><br>
    <br><br>


    <div class="container mt-5" style="align-items:center">
        <div class="card">
            <div class="card-header text-center">
                <h1 class="mb-4">Usuarios Registrados</h1>
            </div>
            <div class="card-body text-center">
                <div id="contenidoUsuarios">
                    <a href="agregarUsuario.php" class="btn btn-secondary mt-3">Agregar Usuario</a>
                    <table class="table mt-3">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombres</th>
                                <th>Apellidos</th>
                                <th>Fecha de Nacimiento</th>
                                <th>Username</th>
                                <th>Correo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            
                            while ($row = $resultadoUsuarios->fetch_assoc()) {
                            ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['nombres']; ?></td>
                                    <td><?php echo $row['apellidos']; ?></td>
                                    <td><?php echo $row['fecha_nacimiento']; ?></td>
                                    <td><?php echo $row['username']; ?></td>
                                    <td><?php echo $row['correo']; ?></td>
                                    <td>
                                        <a href='verCarritoAdmin.php?id=<?php echo $row['id']; ?>' class='btn btn-dark'>Ver Carrito</a>
                                        <form action='eliminar_usuario.php' method='post' style="display: inline;">
                                            <input type='hidden' name='user_id' value=<?php echo $row['id']; ?>>
                                            <button type='submit' class='btn btn-danger'>Eliminar</button>
                                        </form>
                                        <a href='editar_usuario.php?id=<?php echo $row['id']; ?>' class='btn btn-primary'>Editar</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>




    <




    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</html>