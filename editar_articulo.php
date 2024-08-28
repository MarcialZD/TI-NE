<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Artículo</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>

        body {
            background: linear-gradient(to bottom right, #F8F8FF, #01d063);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            text-align: center;
        }

        .input-group {
            width: 300px;
            margin-bottom: 10px;
        }

        .input-group-prepend {
            min-width: 120px;
            text-align: right;
        }

        .form-control {
            width: 180px;
        }

        .btn__submit {
            margin-top: 10px;
        }
    </style>
</head>
<body style="background: linear-gradient(to bottom right, #F8F8FF, #01d063);">
    <?php
    include 'DB_Datos.php';
    error_reporting(0);

    $id = $_GET["id"];

    $stmt = $dbh->prepare("SELECT * FROM articulos WHERE id =?");
    $stmt->execute([$id]);
    $articulos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($articulos as $articulo)
    {
        $nombre = $articulo["nombre"];
        $precio = $articulo["precio"];
        $imagen = $articulo["imagen"];
        ?>

    <div class="form-container">
        <h3>Editar Artículo</h3>
        <form action="" method="post">
           <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="idArticulo">ID Artículo</span>
                </div>
                <input type="text" class="form-control" aria-label="ID Artículo" aria-describedby="idArticulo" name="txtId" disabled value="<?php echo $id; ?>">
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="nombreArticulo">Nombre Artículo</span>
                </div>
                <input type="text" class="form-control" aria-label="Nombre Artículo" aria-describedby="nombreArticulo" name="txtNombre" value="<?php echo $nombre; ?>">
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="precioArticulo">Precio</span>
                </div>
                <input type="text" class="form-control" aria-label="Precio" aria-describedby="precioArticulo" name="txtPrecio" value="<?php echo $precio; ?>">
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="imagenArticulo">Imagen</span>
                </div>
                <input type="text" class="form-control" aria-label="Imagen" aria-describedby="imagenArticulo" name="txtImagen" value="<?php echo $imagen; ?>">
            </div>

            <button class="btn btn-success" type="submit">Guardar cambios</button>
            <a href="admin_interface.php" class="btn btn-secondary">Regresar</a>
        </form>
    </div>

    <?php
   
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (
                isset($_POST["txtNombre"]) &&
                isset($_POST["txtPrecio"]) &&
                isset($_POST["txtImagen"])
        )
        {
            $nombre = $_POST["txtNombre"];
            $precio = $_POST["txtPrecio"];
            $imagen = $_POST["txtImagen"];
            $stmt = $dbh->prepare("UPDATE articulos SET
                nombre=?, 
                precio=?,
                imagen=?
                WHERE id=?");

            $stmt->bindParam(1, $nombre);
            $stmt->bindParam(2, $precio);
            $stmt->bindParam(3, $imagen);
            $stmt->bindParam(4, $id);

            $stmt->execute();
        echo "<script>alert('Artículo editado exitosamente'); window.location.href = 'admin_interface.php';</script>";

            exit();
        }
    }
    ?>
</body>
</html>