<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["username"])) {
    header("location: login.php");
    exit();
}

require 'db_connect.php';
$total_final = isset($_SESSION['total_final']) ? $_SESSION['total_final'] : 0;



// Obtener el ID del usuario actual
$usuario_id = $_SESSION["user_id"];

// Verificar si se ha enviado la solicitud de eliminación de artículo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["eliminar_articulo"])) {
    $carrito_id = $_POST["eliminar_articulo"];

    // Obtener la cantidad del artículo a eliminar
    $cantidad_sql = "SELECT cantidad, articulo_id FROM carritos WHERE id = ? AND usuario_id = ?";
    $stmt = $conexion->prepare($cantidad_sql);
    $stmt->bind_param("ii", $carrito_id, $usuario_id);
    $stmt->execute();
    $cantidad_resultado = $stmt->get_result();

    if ($cantidad_resultado && $cantidad_resultado->num_rows > 0) {
        $cantidad_fila = $cantidad_resultado->fetch_assoc();
        $cantidad_articulo = $cantidad_fila["cantidad"];
        $articulo_id = $cantidad_fila["articulo_id"];

        // Eliminar el artículo del carrito
        $eliminar_sql = "DELETE FROM carritos WHERE id = ?";
        $stmt = $conexion->prepare($eliminar_sql);
        $stmt->bind_param("i", $carrito_id);
        $stmt->execute();

        // Actualizar el stock del artículo
        $actualizar_stock_sql = "UPDATE articulos SET stock = stock + ? WHERE id = ?";
        $stmt = $conexion->prepare($actualizar_stock_sql);
        $stmt->bind_param("ii", $cantidad_articulo, $articulo_id);
        $stmt->execute();

        echo '<script>alert("Artículo eliminado y stock actualizado");</script>';
    }
}






// Consulta para obtener los artículos en el carrito del usuario
$carrito_sql = "SELECT c.id ,c.articulo_id, a.nombre, a.precio, c.cantidad , c.mensaje FROM carritos c
                JOIN articulos a ON c.articulo_id = a.id
                WHERE c.usuario_id = ?";
$stmt = $conexion->prepare($carrito_sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$carrito_resultado = $stmt->get_result();

// Consulta para contar artículos y obtener cantidad total
$consulta = "SELECT COUNT(*) AS total_productos, SUM(cantidad) AS cantidad_total
             FROM carritos
             WHERE usuario_id = ?";

$stmt = $conexion->prepare($consulta);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

// Verifica si la consulta fue exitosa
if ($resultado) {
    $fila = $resultado->fetch_assoc();
    $cant_total_productos = $fila['cantidad_total'];
    if ($cant_total_productos == null) {
        $cant_total_productos = 0;
    }
} else {
    // Manejo de errores
}

// Manejo del formulario de envío
$district_prices = [
    'Distrito1' => 10,
    'Distrito2' => 15,
    'Distrito3' => 20,
    // Agrega más distritos y precios según sea necesario
];

$total_pagar = 0;
$carrito_items = [];
while ($item = $carrito_resultado->fetch_assoc()) {
    $subtotal = $item['cantidad'] * $item['precio'];
    $total_pagar += $subtotal;
    $carrito_items[] = $item;
}





// Almacenar el valor en una variable de sesión

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['finalizar_compra'])) {
    // Verifica si se ha seleccionado un distrito

    $total_final = $_SESSION['total_final'];

    $_SESSION['selected_district'] = $selected_district;

    header('Location: realizar_pago_paypal.php');
}
// Inicializar variables
$selected_district = isset($_SESSION['selected_district']) ? $_SESSION['selected_district'] : "";

$selected_district = isset($_POST['district']) ? $_POST['district'] : null;
$delivery_fee = array_key_exists($selected_district, $district_prices) ? $district_prices[$selected_district] : 0;

if (isset($_POST["actualizar_distrito"])) {
    // Verificar si el distrito seleccionado existe en el array de precios
    $_SESSION["selected_district"] = $selected_district;
    $total_final = $total_pagar + $delivery_fee;
    $_SESSION['total_final'] = $total_final;
}

$selected_district = isset($_SESSION['selected_district']) ? $_SESSION['selected_district'] : '';
$total_final = isset($_SESSION['total_final']) ? $_SESSION['total_final'] : $total_pagar;
$delivery_fee = isset($district_prices[$selected_district]) ? $district_prices[$selected_district] : 0;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-VX18B9GBD3"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-VX18B9GBD3');
</script>
    <style>
        /* Estilo para la tabla */
        .table thead th {
            background-color: #343a40;
            color: white;
            text-align: center;
        }

        .table td {
            text-align: center;
            vertical-align: middle;
        }

        /* Estilo para los botones */
        .btn {
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 5px;
        }

        .btn-danger {
            background-color: #e3342f;
            border-color: #e3342f;
        }

        .btn-success {
            background-color: #38c172;
            border-color: #38c172;
        }

        .btn-outline-primary {
            border-color: #3490dc;
            color: #3490dc;
        }

        .btn-outline-primary:hover {
            background-color: #3490dc;
            color: white;
        }

        /* Contenedor */
        .container-sm {
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .total-pagar {
            font-size: 1.5rem;
            font-weight: bold;
            text-align: right;
        }

        .table-responsive {
            margin-top: 20px;
        }

        /* Añadir espacio inferior */
        .mb-3 {
            margin-bottom: 30px;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            margin-top: 5%;
        }

        .btn-group {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .summary-table {
            margin-top: 20px;
            font-size: 1.2rem;
        }

        .summary-table th,
        .summary-table td {
            text-align: right;
            padding: 10px;
        }

        /* Estilos responsivos */
        @media (max-width: 768px) {
            .row {
                display: flex;
                flex-direction: column;
            }

            .col-md-6 {
                width: 100%;
                margin-bottom: 20px;
            }

            .summary-table {
                margin-top: 10px;
            }
        }
    </style>
    <script>

    </script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top shadow-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="img/NutriCode_logo_sin_fondo.png" width="60" height="60" alt="">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="servicios.php">Servicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="quienesSomos.php">¿Quiénes somos?</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="productos.php">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="carrito.php">
                            <?php echo $_SESSION["username"]; ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                            </svg>(<?php echo $cant_total_productos; ?>)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="cerrarsesion.php">Cerrar sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container container-sm">
        <h1 class="title">Carrito de Compras de <?php echo $_SESSION["username"]; ?></h1>

        <?php if ($carrito_resultado->num_rows > 0): ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Subtotal</th>´
                                    <th>Personalización</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($carrito_items as $item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                                        <td><?php echo number_format($item['precio'], 2); ?></td>
                                        <td><?php echo htmlspecialchars($item['cantidad']); ?></td>
                                        <td><?php echo number_format($item['cantidad'] * $item['precio'], 2); ?></td>
                                        <td><?php echo htmlspecialchars($item['mensaje']); ?></td>
                                        
                                        <td>
                                        <form method="post">
                                        <button type="submit" name="eliminar_articulo" value="<?php echo($item['id']); ?>" class="btn btn-danger">Eliminar</button>
                                    </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="summary-table">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Precio total de los artículos:</th>
                                    <td>S/. <?php echo number_format($total_pagar, 2); ?></td>
                                </tr>
                                <tr>
                                    <th>Distrito seleccionado:</th>
                                    <td><?php echo ($selected_district); ?></td>
                                </tr>
                                <tr>
                                    <th>Precio de entrega por distrito:</th>
                                    <td>S/. <?php echo number_format($delivery_fee, 2); ?></td>
                                </tr>
                                <tr>
                                    <th>Total final:</th>
                                    <td>S/. <?php echo number_format($total_final, 2); ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <form action="carrito.php" method="post">
                            <div class="form-group">
                                <label for="district">Seleccionar Distrito:</label>
                                <select id="district" name="district" class="form-select">
                                    <option value="" selected>Selecciona un distrito</option>
                                    <!-- Aquí puedes agregar las opciones del distrito -->
                                    <option value="Distrito1" <?php echo $selected_district === 'Distrito1' ? 'selected' : ''; ?>>Distrito 1</option>
                                    <option value="Distrito2" <?php echo $selected_district === 'Distrito2' ? 'selected' : ''; ?>>Distrito 2</option>
                                    <!-- Más opciones -->
                                </select>
                            </div><br><br>
                            <button type="submit" name="actualizar_distrito" class="btn btn-primary">Actualizar Distrito</button>
                            <a href="productos.php" class="btn btn-success">Añadir más productos</a>

                        </form>
                        <?php if (!empty($selected_district)): ?>
                            <form action="carrito.php" method="post">
                                <button type="submit" name="finalizar_compra" class="btn btn-danger">Finalizar Compra</button>
                            </form>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

            <div class="mt-3 mb-3">



            </div>



        <?php else: ?>
            <p class="lead">Tu carrito está vacío.</p>
        <?php endif; ?>
    </div>
</body>

</html>