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
        
        nav {
            background-color: #faeae5;
            text-align: center;
        }

        .navbar-custom {
            background-color: #faeae5;
            padding-bottom: 10%;
            margin-bottom: 10%;

        }

        .navbar-custom .navbar-nav {
            background-color: #faeae5
        }

        /* CSS */
        .custom-toggler {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3E%3Cpath stroke='black' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
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
    <nav class="navbar navbar-expand-lg  fixed-top shadow-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin_interface.php">
                <img src="img/NutriCode_logo_sin_fondo.png" width="100" height="90" alt="">
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
<br><br><br> <br> <br>
    <?php
    session_start();

    if (!isset($_SESSION["username"]) || !isset($_SESSION["es_admin"]) || $_SESSION["es_admin"] != 1) {
        header("location: login.php");
        exit();
    }

    require_once 'db_connect.php';

    $usuario_id = $_GET["id"];

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
$sqlVentas = "SELECT v.id AS venta_id, v.fecha_hora, v.monto_total, v.transaccion_id
              
        FROM ventas v
      
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

        $sqlVentas = "SELECT v.id AS venta_id, v.fecha_hora, v.monto_total, v.direccion,v.transaccion_id
                            
                      FROM ventas v
                     
                      WHERE v.usuario_id = $usuario_id
                      ORDER BY v.fecha_hora DESC";
        $resultVentas = $conexion->query($sqlVentas);

        echo '<div class="container">';
        echo '<div class="card">';
        echo '<h3 class="mt-5">Historial de Ventas de ' . $username . '</h3>';
        echo '<div class="table-responsive">';
        echo '<table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID de Venta</th>
                        <th>Transaccion ID</th>
                        <th>Fecha y Hora</th>
                        <th>Monto Total</th>
                        <th>Dirección</th>
                        <th>Estado</th>

                    </tr>
                </thead>
                <tbody>';

        if ($resultVentas->num_rows > 0) {
            while ($rowVentas = $resultVentas->fetch_assoc()) {
                echo '<tr>
                <td>' . $rowVentas["venta_id"] . '</td>
                <td>' . $rowVentas["transaccion_id"] . '</td>
                <td>' . $rowVentas["fecha_hora"] . '</td>
                <td>$' . number_format($rowVentas["monto_total"], 2) . '</td>
                <td>' . $rowVentas["direccion"] . '</td>
                <td>
                    <a href="detalle_venta.php?id=' . $rowVentas["venta_id"] . '">Ver Detalle</a>
                </td>
              </tr>';
        
            }
        } else {
            echo '<tr><td colspan="9">No se encontraron ventas para este usuario.</td></tr>';
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
        echo '<div class="alert alert-danger" role="alert">No se encontraron resultados para este usuario.</div>';
        echo '</div>';
    }

    $conexion->close();
    ?>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
