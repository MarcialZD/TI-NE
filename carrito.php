<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["username"]))
{
    header("location: login.php");
    exit();
}

// Conectar a la base de datos (reemplaza 'usuario', 'contraseña' y 'nombre_base_de_datos' con tus propios valores)
$conexion = new mysqli("localhost", "root", "123456", "nutricode");

if ($conexion->connect_error)
{
    die("La conexión a la base de datos falló: " . $conexion->connect_error);
}

// Obtener el ID del usuario actual
$usuario_id = $_SESSION["user_id"];

// Verificar si se ha enviado la solicitud de eliminación de artículo
// Verificar si se ha enviado la solicitud de eliminación de artículo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["eliminar_articulo"]))
{
    $articulo_id = $_POST["eliminar_articulo"];

    // Obtener la cantidad del artículo a eliminar
    $cantidad_sql = "SELECT cantidad FROM carritos WHERE usuario_id = $usuario_id AND articulo_id = $articulo_id";
    $cantidad_resultado = $conexion->query($cantidad_sql);

    if ($cantidad_resultado && $cantidad_resultado->num_rows > 0)
    {
        $cantidad_fila = $cantidad_resultado->fetch_assoc();
        $cantidad_articulo = $cantidad_fila["cantidad"];

        // Eliminar el artículo del carrito
        $eliminar_sql = "DELETE FROM carritos WHERE usuario_id = $usuario_id AND articulo_id = $articulo_id";
        $conexion->query($eliminar_sql);

        // Actualizar el stock del artículo
        $actualizar_stock_sql = "UPDATE articulos SET stock = stock + $cantidad_articulo WHERE id = $articulo_id";
        $conexion->query($actualizar_stock_sql);

        echo '<script>alert("Artículo eliminado y stock actualizado");</script>';
    }
}

// Obtener los artículos en el carrito del usuario
$carrito_sql = "SELECT c.articulo_id, a.nombre, a.precio, c.cantidad FROM carritos c
                JOIN articulos a ON c.articulo_id = a.id
                WHERE c.usuario_id = $usuario_id";
$carrito_resultado = $conexion->query($carrito_sql);

// cant articulos en carrito:
// cantidad para el carrito

$consulta = "SELECT COUNT(*) AS total_productos, SUM(cantidad) AS cantidad_total
             FROM carritos
             WHERE usuario_id = $usuario_id";

// Ejecuta la consulta
$resultado = $conexion->query($consulta);
// Verifica si la consulta fue exitosa
if ($resultado)
{
    // Obtiene el resultado como un array asociativo
    $fila = $resultado->fetch_assoc();

    // Almacena el total de productos en una variable
    $cant_total_productos = $fila['cantidad_total'];

    if ($cant_total_productos == null)
    {
        $cant_total_productos = 0;
    }
    // Cierra la conexión a la base de datos
    $conexion->close();
}
else
{
    // Si la consulta falla, muestra un mensaje de error
}
?>


<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Carrito de Compras</title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <script src="js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="estilos/style_carrito.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>

            .table-bordered th,
            .table-bordered td {
                border: 2px solid #000;
            }

        </style>
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
                            <a class="nav-link" href="carrito.php" style="color: #000000;"> <?php echo $_SESSION["username"]; ?> &nbsp <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                                </svg>(<?php echo $cant_total_productos; ?>)</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>



        <br>     <br>




        <br>     <br>
        <div class="container container-sm" style="background-color: white; ">
            <h1 class="title">Carrito de Compras de <?php echo $_SESSION["username"]; ?></h1>

            <?php
            if ($carrito_resultado->num_rows > 0)
            {
                echo "<table class='table table-bordered'>";
                echo "<thead><tr><th>Nombre</th><th>Cantidad</th><th>Precio Unitario</th><th>Subtotal</th><th>Acción</th></tr></thead>";
                echo "<tbody>";

                $total_pagar = 0;

                while ($item = $carrito_resultado->fetch_assoc())
                {
                    echo "<tr>";
                    echo "<td>{$item['nombre']}</td>";
                    echo "<td>{$item['cantidad']}</td>";
                    echo "<td>{$item['precio']} USD</td>";

                    // Calcular y mostrar el subtotal
                    $subtotal = $item['cantidad'] * $item['precio'];
                    echo "<td>{$subtotal} USD</td>";

                    // Mostrar el botón de eliminar
                    echo "<td>";
                    echo "<form action='' method='post'>";
                    echo "<input type='hidden' name='eliminar_articulo' value='{$item['articulo_id']}'>";
                    echo "<button type='submit' class='btn btn-danger'>Eliminar <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash3-fill' viewBox='0 0 16 16'>
  <path d='M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5'/>
</svg></a></td></button>";
                    echo "</form>";
                    echo "</td>";

                    echo "</tr>";

                    $total_pagar += $subtotal;
                }

                echo "</tbody>";
                echo "</table>";
                echo "<br>";

                // Mostrar el total a pagar
                echo "<div class='total-pagar'>";
                echo "<h5>Total a pagar: {$total_pagar} USD</h5>";
                echo "</div>";
                ?> 

                <div class="mb-3"> <!-- Agrega un poco de espacio en la parte inferior del contenedor -->
                    <a class="btn btn-success mt-3" href="realizar_pago.php?">
                        Ir a Realizar Pago
                        <i class="fas fa-dollar-sign"></i>
                    </a>

                    <a class="btn btn-outline-primary mt-3" href="agregararticulo.php">Agregar Artículo <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                        <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                        </svg></a>
                </div>





                <?php
            }
            else
            {
                echo "<br>";
                echo "<p>El carrito está vacío.</p>";
                    echo '<script>alert("Carrito Vacío");</script>';

                ?> <a class="btn btn-outline-primary mt-3" href="agregararticulo.php">Agregar Artículo <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                    <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                    </svg></a><?php
            }
            ?>
            <div class="container" style="margin-bottom:  100px;">
                <div class="row justify-content-center">
                    <div class="col-4 col-md-2">
                        <a class="nav-link btn btn-dark btn-sm btn-block text-white" href="cerrarsesion.php">Cerrar Sesión <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-left" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z"/>
                            <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z"/>
                            </svg></a>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <br>



</html>
