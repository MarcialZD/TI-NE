<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["username"])) {
    header("location: login.php");
    exit();
}

// Conectar a la base de datos
require 'db_connect.php';

// Obtener todos los artículos de la tabla 'articulos'
$articulos_sql = "SELECT * FROM articulos";
$articulos_resultado = $conexion->query($articulos_sql);

// Verificar si se ha enviado el formulario para agregar al carrito
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["articulo_id"]) && isset($_POST["cantidad"])) {

    $articulo_id = $_POST["articulo_id"];
    $cantidad = $_POST["cantidad"];

    // Obtener el ID del usuario actual
    $usuario_id = $_SESSION["user_id"];

    // Usar una consulta preparada para verificar si el artículo ya está en el carrito
    $stmt = $conexion->prepare("SELECT * FROM carritos WHERE usuario_id = ? AND articulo_id = ?");
    $stmt->bind_param("ii", $usuario_id, $articulo_id);
    $stmt->execute();
    $verificar_resultado = $stmt->get_result();

    // Consultar el stock disponible del artículo
    $stmt = $conexion->prepare("SELECT stock FROM articulos WHERE id = ?");
    $stmt->bind_param("i", $articulo_id);
    $stmt->execute();
    $stock_resultado = $stmt->get_result();
    $stock_row = $stock_resultado->fetch_assoc();
    $stock_disponible = $stock_row['stock'];

    if ($stock_disponible < $cantidad) {
        echo '<script>alert("No hay suficiente stock disponible.");</script>';
    } else {
        if ($verificar_resultado->num_rows > 0) {
            // Si el artículo ya está en el carrito, actualizar la cantidad
            $stmt = $conexion->prepare("UPDATE carritos SET cantidad = cantidad + ? WHERE usuario_id = ? AND articulo_id = ?");
            $stmt->bind_param("iii", $cantidad, $usuario_id, $articulo_id);
            $stmt->execute();
            echo '<script>alert("Artículo acumulado");</script>';
        } else {
            // Si el artículo no está en el carrito, insertarlo
            $stmt = $conexion->prepare("INSERT INTO carritos (usuario_id, articulo_id, cantidad, subtotal) VALUES (?, ?, ?, 0)");
            $stmt->bind_param("iii", $usuario_id, $articulo_id, $cantidad);
            $stmt->execute();
            echo '<script>alert("Artículo agregado");</script>';
        }

        // Actualizar el stock disponible
        $nuevo_stock = $stock_disponible - $cantidad;
        $stmt = $conexion->prepare("UPDATE articulos SET stock = ? WHERE id = ?");
        $stmt->bind_param("ii", $nuevo_stock, $articulo_id);
        $stmt->execute();

        // Redirigir a carrito.php después de agregar al carrito
        header("location: carrito.php");
        exit();
    }
}

// cantidad para el carrito
$usuario_id = $_SESSION["user_id"];
$consulta = "SELECT COUNT(*) AS total_productos, SUM(cantidad) AS cantidad_total FROM carritos WHERE usuario_id = ?";
$stmt = $conexion->prepare($consulta);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

// Verifica si la consulta fue exitosa
if ($resultado) {
    $fila = $resultado->fetch_assoc();
    $cant_total_productos = $fila['cantidad_total'] ?? 0;
} else {
    $cant_total_productos = 0;
}

$conexion->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Artículo</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="estilos/Style_agregararticulo.css">
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
                <a class="navbar-brand" href="index.php">
                    <img src="img/NutriCode_logo_sin_fondo.png" width="30" height="30" alt="">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="index.php" style="color: #000000;">
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
                            <a class="nav-link" href="carrito.php" style="color: #000000;"> <?php echo $_SESSION["username"]; ?> &nbsp <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                                </svg>(<?php echo $cant_total_productos; ?>)</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

    <br><br><br><br>

    <h1 class="display-8">Agregar Artículo</h1>
    <br>
    <a class="btn btn-outline-dark" href="carrito.php">Regresar al Carrito</a>

    <br><br>

    <div class="container">
        <div class="row">
            <?php
            while ($articulo = $articulos_resultado->fetch_assoc()) {
                echo "<div class='col-md-4 mb-4'>";
                echo "<div class='card'>";
                echo "<img src='{$articulo['imagen']}' class='card-img-top' alt='{$articulo['nombre']}'>";
                echo "<div class='card-body'>";
                echo "<h5 class='card-title'>{$articulo['nombre']}</h5>";
                echo "<p class='card-text'>Stock: {$articulo['stock']} unidades</p>";
                echo "<p class='card-text'>Precio: {$articulo['precio']} USD</p>";

                echo "<form action='agregararticulo.php' method='post'>";
                echo "<input type='hidden' name='articulo_id' value='{$articulo['id']}'>";
                echo "<div class='mb-3'>";
                echo "<label for='cantidad{$articulo['id']}' class='form-label'>Cantidad:</label>";
                echo "<input type='number' name='cantidad' id='cantidad{$articulo['id']}' value='1' min='1' class='form-control' required autocomplete='off'>";
                echo "</div>";
                echo "<button type='submit' class='btn btn-success'>Agregar al Carrito</button>";
                echo "</form>";

                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
            ?>
        </div>
    </div>
    <footer style="background-color: #333; color: #ffffff; text-align: center; padding: 20px;">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <h4 style="color: #ffffff;">Contacto</h4>
                            <p style="color: #ffffff;">Correo electrónico: dargel@gmail.com</p>
                            <p style="color: #ffffff;">Teléfono: +51 984-153-862</p>

                            <p style="color: #ffffff;">&copy Derechos reservados Dargel</p>

                        </div>
                    </div>
                </div>
            </footer>
</body>

</html>
