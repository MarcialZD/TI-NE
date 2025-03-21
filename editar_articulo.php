<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Artículo</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
         
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
    <script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-VX18B9GBD3');
</script>
</head>
<body>
    <?php
    require 'db_connect.php';

    error_reporting(0);

    $id = $_GET["id"];

    // Obtener datos del artículo
    $stmt = $conexion->prepare("SELECT * FROM articulos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $articulo = $result->fetch_assoc();

    if ($articulo) {
        $nombre = $articulo["nombre"];
        $precio = $articulo["precio"];
        $imagen = $articulo["imagen"];
        $descripcion=$articulo["descripcion"];
        $stock=$articulo["stock"];
        
    ?>

    <div class="form-container">
        <h3>Editar Artículo</h3>
        <form action="" method="post">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="idArticulo">ID Artículo</span>
                </div>
                <input type="text" class="form-control" aria-label="ID Artículo" aria-describedby="idArticulo" name="txtId" disabled value="<?php echo htmlspecialchars($id); ?>">
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="nombreArticulo">Nombre Artículo</span>
                </div>
                <input type="text" class="form-control" aria-label="Nombre Artículo" aria-describedby="nombreArticulo" name="txtNombre" value="<?php echo htmlspecialchars($nombre); ?>">
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="precioArticulo">Precio</span>
                </div>
                <input type="text" class="form-control" aria-label="Precio" aria-describedby="precioArticulo" name="txtPrecio" value="<?php echo htmlspecialchars($precio); ?>">
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="stockArticulo">stock</span>
                </div>
                <input type="number" class="form-control" aria-label="stock" aria-describedby="stockArticulo" name="txtStock" value="<?php echo htmlspecialchars($stock); ?>">
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="descripcionArticulo">Descripcion</span>
                </div>
                <input type="text" class="form-control" aria-label="Descripcion" aria-describedby="descripcionArticulo" name="txtDescripcion" value="<?php echo htmlspecialchars($descripcion); ?>">
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="imagenArticulo">Imagen</span>
                </div>
                <input type="text" class="form-control" aria-label="Imagen" aria-describedby="imagenArticulo" name="txtImagen" value="<?php echo htmlspecialchars($imagen); ?>">
            </div>

            <button class="btn btn-success" type="submit">Guardar cambios</button>
            <a href="admin_interface.php" class="btn btn-secondary">Regresar</a>
        </form>
    </div>

    <?php
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (
            isset($_POST["txtNombre"]) &&
            isset($_POST["txtPrecio"]) &&
            isset($_POST["txtImagen"]) &&
            isset($_POST["txtStock"]) && 
            isset($_POST["txtDescripcion"])
        ) {
            $nombre = $_POST["txtNombre"];
            $precio = $_POST["txtPrecio"];
            $imagen = $_POST["txtImagen"];
            $stock = intval($_POST["txtStock"]);
            $descripcion = $_POST["txtDescripcion"]; 

            // Actualizar datos del artículo
            $stmt = $conexion->prepare("UPDATE articulos SET
                nombre=?, 
                precio=?,
                imagen=?,
                stock=?,
                descripcion=?
                WHERE id=?");

            $stmt->bind_param("sssssi",$nombre, $precio, $imagen,$stock,$descripcion, $id);
            $stmt->execute();
           
            echo "<script>alert('Artículo editado exitosamente'); window.location.href = 'admin_interface.php';</script>";

            exit();
        }
    }
    ?>
</body>
</html>
