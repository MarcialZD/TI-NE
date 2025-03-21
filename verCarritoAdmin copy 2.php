<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito del Usuario</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilos/Style_admin.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 50px;
        }

        .card {
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: white;
            border-radius: 10px;
        }

        .table {
            margin-top: 20px;
        }

        .btn-container {
            margin-top: 20px;
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

    <?php
    session_start();

    // Verificar si el usuario está autenticado y es administrador
    if (!isset($_SESSION["username"]) || !isset($_SESSION["es_admin"]) || $_SESSION["es_admin"] != 1) {
        header("location: login.php");
        exit();
    }

    // Incluir la conexión a la base de datos
    require_once 'db_connect.php';

    $usuario_id = $_GET["id"]; // Cambia esto según el ID del usuario que estás consultando

    // Consulta SQL para obtener el nombre de usuario
    $sqlUsuario = "SELECT username FROM usuarios WHERE id = $usuario_id";
    $resultUsuario = $conexion->query($sqlUsuario);

    if ($resultUsuario->num_rows > 0) {
        $rowUsuario = $resultUsuario->fetch_assoc();
        $username = $rowUsuario["username"];

        // Consulta SQL para obtener detalles del carrito
        $sqlCarrito = "SELECT a.id, a.nombre, a.precio, c.cantidad, (c.cantidad * a.precio) AS subtotal
                       FROM carritos c
                       JOIN articulos a ON c.articulo_id = a.id
                       WHERE c.usuario_id = $usuario_id";
        $resultCarrito = $conexion->query($sqlCarrito);

        // Consulta SQL para obtener las ventas y sus detalles, incluyendo el mensaje
        $sqlVentas = "SELECT v.id AS venta_id, v.fecha_hora, v.monto_total, 
                             va.mensaje, a.nombre AS articulo_nombre, a.precio AS articulo_precio, va.cantidad, (va.cantidad * a.precio) AS subtotal
                      FROM ventas v
                      JOIN venta_articulo va ON v.id = va.venta_id
                      JOIN articulos a ON va.articulo_id = a.id
                      WHERE v.usuario_id = $usuario_id
                      ORDER BY v.fecha_hora DESC"; // Ordenar por fecha
        $resultVentas = $conexion->query($sqlVentas);

        echo '<div class="container">';
        echo '<div class="card">';
        echo '<h2 class="mb-4">Carrito de ' . $username . '</h2>';

        // Tabla del carrito
        echo '<div class="table-responsive">';
        echo '<table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID del Artículo</th>
                        <th>Nombre del Artículo</th>
                        <th>Precio Unitario</th>
                        <th>Cantidad en el Carrito</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>';

        if ($resultCarrito->num_rows > 0) {
            $montoTotal = 0;

            while ($rowCarrito = $resultCarrito->fetch_assoc()) {
                echo '<tr>
                        <td>' . $rowCarrito["id"] . '</td>
                        <td>' . $rowCarrito["nombre"] . '</td>
                        <td>$' . number_format($rowCarrito["precio"], 2) . '</td>
                        <td>' . $rowCarrito["cantidad"] . '</td>
                        <td>$' . number_format($rowCarrito["subtotal"], 2) . '</td>
                      </tr>';

                $montoTotal += $rowCarrito["subtotal"];
            }

            echo '<tr><td colspan="4"><b>Monto Total</b></td><td><b>$' . number_format($montoTotal, 2) . '</b></td></tr>';
        } else {
            echo '<tr><td colspan="5">No se encontraron resultados en el carrito.</td></tr>';
        }

        echo '</tbody></table>';
        echo '</div>';

        // Tabla de ventas
        echo '<h3 class="mt-5">Historial de Ventas de ' . $username . '</h3>';
        echo '<div class="table-responsive">';
        echo '<table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID de Venta</th>
                        <th>Fecha y Hora</th>
                        <th>Nombre del Artículo</th>
                        <th>Precio Unitario</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Monto Total</th>
                        <th>Mensaje</th>
                    </tr>
                </thead>
                <tbody>';

        if ($resultVentas->num_rows > 0) {
            $ventasAgrupadas = [];
            while ($rowVentas = $resultVentas->fetch_assoc()) {
                $ventaId = $rowVentas["venta_id"];
                if (!isset($ventasAgrupadas[$ventaId])) {
                    $ventasAgrupadas[$ventaId] = [
                        'fecha_hora' => $rowVentas["fecha_hora"],
                        'monto_total' => $rowVentas["monto_total"],
                        'mensaje' => $rowVentas["mensaje"],
                        'articulos' => []
                    ];
                }
                $ventasAgrupadas[$ventaId]['articulos'][] = [
                    'nombre' => $rowVentas["articulo_nombre"],
                    'precio' => $rowVentas["articulo_precio"],
                    'cantidad' => $rowVentas["cantidad"],
                    'subtotal' => $rowVentas["subtotal"]
                ];
            }

            foreach ($ventasAgrupadas as $ventaId => $venta) {
                $articulos = $venta['articulos'];
                $numArticulos = count($articulos);
                foreach ($articulos as $index => $articulo) {
                    if ($index == 0) {
                        echo '<tr>
                                <td>' . $ventaId . '</td>
                                <td>' . $venta['fecha_hora'] . '</td>
                                <td>' . $articulo['nombre'] . '</td>
                                <td>$' . number_format($articulo['precio'], 2) . '</td>
                                <td>' . $articulo['cantidad'] . '</td>
                                <td>$' . number_format($articulo['subtotal'], 2) . '</td>
                                <td rowspan="' . $numArticulos . '">$' . number_format($venta['monto_total'], 2) . '</td>
                                <td rowspan="' . $numArticulos . '">' . $venta['mensaje'] . '</td>
                              </tr>';
                    } else {
                        echo '<tr>
                                <td></td>
                                <td></td>
                                <td>' . $articulo['nombre'] . '</td>
                                <td>$' . number_format($articulo['precio'], 2) . '</td>
                                <td>' . $articulo['cantidad'] . '</td>
                                <td>$' . number_format($articulo['subtotal'], 2) . '</td>
                                <td></td>
                                <td></td>
                              </tr>';
                    }
                }
            }
        } else {
            echo '<tr><td colspan="8">No se encontraron ventas para este usuario.</td></tr>';
        }

        echo '</tbody></table>';
        echo '</div>';
        echo '<div class="btn-container">';
        echo '<button type="button" class="btn btn-outline-primary" onclick="window.history.back()">Regresar</button>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    } else {
        echo '<div class="container">';
        echo '<div class="card">';
        echo '<h2 class="mb-4">Carrito</h2>';
        echo "No se encontraron resultados para el usuario.";
        echo '<div class="btn-container">';
        echo '<button type="button" class="btn btn-outline-primary" onclick="window.history.back()">Regresar</button>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    $conexion->close();
    ?>
</body>
</html>
