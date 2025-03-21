<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION["username"])) {
    header("location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("ID de venta no proporcionado.");
}

$venta_id = intval($_GET['id']);

$venta_sql = "SELECT * FROM ventas v JOIN venta_articulo va on v.id = va.venta_id
    JOIN usuarios u on v.usuario_id = u.id WHERE v.id = ? ";
$stmt = $conexion->prepare($venta_sql);
$stmt->bind_param("i", $venta_id);
$stmt->execute();
$venta_resultado = $stmt->get_result();
$venta = $venta_resultado->fetch_assoc();

if (!$venta) {
    die("Venta no encontrada.");
}

$articulos_sql = "
    SELECT va.cantidad, va.subtotal, a.nombre, a.precio ,u.nombres , va.mensaje
    FROM venta_articulo va
        JOIN ventas v on v.id = va.venta_id
    JOIN usuarios u on v.usuario_id = u.id
    JOIN articulos a ON va.articulo_id = a.id
    WHERE va.venta_id = ?
";
$stmt = $conexion->prepare($articulos_sql);
$stmt->bind_param("i", $venta_id);
$stmt->execute();
$articulos_resultado = $stmt->get_result();
$consulta = "
    SELECT COUNT(*) AS total_productos, SUM(cantidad) AS cantidad_total
    FROM carritos
    WHERE usuario_id = ?
";

$stmt = $conexion->prepare($consulta);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado) {
    $fila = $resultado->fetch_assoc();
    $cant_total_productos = $fila['cantidad_total'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Venta</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
    <style>
        .table thead th {
            background-color: #343a40;
            color: white;
            text-align: center;
        }
        .table td {
            text-align: center;
            vertical-align: middle;
        }
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
        .container-sm {
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .table-responsive {
            margin-top: 20px;
        }
        .mb-3 {
            margin-bottom: 30px;
        }
        body {
            align-items: center;
            justify-content: center;
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
        @media (max-width: 768px) {
            .row {
                display: flex;
                flex-direction: column;
            }
            .col-md-6 {
                width: 100%;
                margin-bottom: 20px;
            }
        }
        .navbar-custom {
            background-color: #faeae5;
        }
        .navbar-custom .navbar-nav {
            background-color: #faeae5;
        }
        #finalizar-compra-form {
            display: <?php echo !empty($selected_district) ? 'block' : 'none'; ?>;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="img/NutriCode_logo_sin_fondo.png" width="100" height="90" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="blog.php" style="color: #f10f5a;">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="servicios.php" style="color: #f10f5a">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="productos.php" style="color: #f10f5a">Pasteles</a>
                    </li>
                    <?php
                    if (!isset($_SESSION["username"])) {
                        echo '<li class="nav-item"><a class="nav-link" href="carrito.php" style="color: #f10f5a"><i class="fa-solid fa-user active"></i> Cuenta</a></li>';
                    } else {
                        echo '<li class="nav-item"><a class="nav-link" href="carrito.php" style="color: #f10f5a;">' . $_SESSION["username"] . ' <i class="fa-solid fa-cart-shopping"></i>(' . $cant_total_productos . ')</a></li>';
                    }
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="cerrarsesion.php" style="color: #000000;">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h2>Detalle de Compra </h2>
        <p><strong>Cliente:</strong> <?php echo $venta['nombres']; ?></p>
        <p><strong>Fecha y Hora:</strong> <?php echo $venta['fecha_hora']; ?></p>
        <p><strong>Transacción ID:</strong> <?php echo $venta['transaccion_id']; ?></p>
        <p><strong>Monto Total:</strong> $<?php echo number_format($venta['monto_total'], 2); ?></p>
        <p><strong>Dirección:</strong> <?php echo htmlspecialchars($venta['direccion']); ?></p>
        <p><strong>Estado:</strong> <?php echo htmlspecialchars($venta['estado']); ?></p>

        <h3>Artículos Comprados</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Precio Unitario</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Mensaje</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($articulo = $articulos_resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($articulo['nombre']); ?></td>
                        <td>$<?php echo number_format($articulo['precio'], 2); ?></td>
                        <td><?php echo $articulo['cantidad']; ?></td>
                        <td>$<?php echo number_format($articulo['precio']*$articulo['cantidad'], 2); ?></td>
                        <td><?php echo ($articulo['mensaje']); ?></td>

                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="carrito.php" class="btn btn-primary">Volver</a>
    </div>
</body>
</html>
