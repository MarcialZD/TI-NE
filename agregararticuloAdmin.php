<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
require 'db_connect.php';
   

    $nombre = $_POST["txtNombre"];
    $precio = $_POST["txtPrecio"];
    $stock = $_POST["txtStock"];
    $descripcion = $_POST["txtDescripcion"];
    $imagen = $_POST["txtImagen"];


    $sql = "INSERT INTO articulos (nombre, precio,stock,descripcion, imagen) 
            VALUES ('$nombre','$precio','$stock','$descripcion','$imagen')";

    if ($conexion->query($sql) === TRUE) {
        $destino = "admin_interface.php";
        echo '<script>alert("Artículo Añadido"); window.location.href = "' . $destino . '";</script>';
        exit();
    } else {
        echo "Error al añadir el artículo: " . $conexion->error;
    }

    $conexion->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Artículo</title>
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
        <form action="agregararticuloAdmin.php" method="post" class="mx-auto" style="max-width: 400px;">
            <h1 class="h3 mb-3 fw-normal text-center">Añadir Artículo</h1>
            <div class="mb-3">
                <label for="txtNombre" class="form-label">Nombre Artículo:</label>
                <input type="text" name="txtNombre" class="form-control" required value="">
            </div>
            <div class="mb-3">
                <label for="txtPrecio" class="form-label">Precio:</label>
                <input type="text" name="txtPrecio" class="form-control" required value="">
            </div>
            <div class="mb-3">
                <label for="txtImagen" class="form-label">Imagen:</label>
                <input type="text" name="txtImagen" class="form-control" required value="">
            </div>
            <div class="mb-3">
                <label for="txtStock" class="form-label">Stock:</label>
                <input type="text" name="txtStock" class="form-control" required value="">
            </div>
            <div class="mb-3">
                <label for="txtDescripcion" class="form-label">Descripcion:</label>
                <input type="text" name="txtDescripcion" class="form-control" required value="">
            </div>

            <button class="btn btn-primary" type="submit">Agregar Artículo</button>
            <a href="admin_interface.php" class="btn btn-secondary">Regresar</a>
        </form>
    </div>

</body>

</html>
