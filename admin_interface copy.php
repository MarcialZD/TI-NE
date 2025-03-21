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
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="estilos/Style_admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="estilos/Style_admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"><script>
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
    <br><br>


    <div class="container mt-5">
        <div class="card">
            <div class="card-header text-center">
                <h1 class="mb-4">Usuarios Registrados</h1>
            </div>
            <div class="card-body text-center">
                <button id="mostrarUsuarios" class="btn btn-warning">Mostrar Usuarios</button>
                <div id="contenidoUsuarios" style="display: none;">
                    <a href="agregarUsuario.php" class="btn btn-success mt-3">Agregar Usuario</a>
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
                            // Aquí deberías tener el código PHP que proporcionaste en tu consulta original
                            // Recuerda que esto es un ejemplo y necesitas tener $resultadoUsuarios definido antes de usarlo en el while loop.
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

    <script>
        document.getElementById("mostrarUsuarios").addEventListener("click", function() {
            var contenido = document.getElementById("contenidoUsuarios");
            if (contenido.style.display === "none") {
                contenido.style.display = "block";
            } else {
                contenido.style.display = "none";
            }
        });
    </script>



    <div class="container mt-5">
        <div class="card">
            <div class="card-header text-center">
                <h1 class="mt-5 mb-4">Artículos Registrados</h1>
            </div>
            <div class="card-body text-center">
                <button id="mostrarArticulos" class="btn btn-warning">Mostrar Artículos</button>
                <div id="contenidoArticulos" style="display: none;">
                    <a href="agregararticuloAdmin.php" class="btn btn-success mt-3">Agregar Artículo</a>
                    <table class="table mt-3">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Imagen</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Aquí deberías tener el código PHP que proporcionaste en tu consulta original
                            // Recuerda que esto es un ejemplo y necesitas tener $resultadoArticulos definido antes de usarlo en el while loop.
                            while ($row = $resultadoArticulos->fetch_assoc()) {
                            ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['nombre']; ?></td>
                                    <td><?php echo $row['precio']; ?></td>
                                    <td><img src='<?php echo $row['imagen']; ?>' alt='<?php echo $row['nombre']; ?>' width='50'></td>
                                    <td>
                                        <form action='eliminar_articulo.php' method='post' style="display: inline;">
                                            <input type='hidden' name='articulo_id' value='<?php echo $row['id']; ?>'>
                                            <button type='submit' class='btn btn-danger'>Eliminar</button>
                                        </form>
                                        <a href='editar_articulo.php?id=<?php echo $row['id']; ?>' class='btn btn-primary'>Editar</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById("mostrarArticulos").addEventListener("click", function() {
            var contenido = document.getElementById("contenidoArticulos");
            if (contenido.style.display === "none") {
                contenido.style.display = "block";
            } else {
                contenido.style.display = "none";
            }
        });
    </script>


    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</html>