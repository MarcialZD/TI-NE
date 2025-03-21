<!--editado-->
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
            background-color: #faeae5
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
    <nav class="navbar navbar-expand-lg fixed-top shadow-lg">
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

    // Verificar si hay resultados de usuario
    if ($resultUsuario->num_rows > 0) {
        $rowUsuario = $resultUsuario->fetch_assoc();
        $username = $rowUsuario["username"];

        // Consulta SQL para obtener detalles del carrito
        $sqlCarrito = "SELECT a.id, a.nombre, a.precio, c.cantidad, c.subtotal
                       FROM carritos c
                       JOIN articulos a ON c.articulo_id = a.id
                       WHERE c.usuario_id = $usuario_id";

        $resultCarrito = $conexion->query($sqlCarrito);

        if ($resultCarrito->num_rows > 0) {
            $montoTotal = 0;

            echo '<div class="container">';
            echo '<div class="card">';
            echo '<h2 class="mb-4">Carrito de ' . $username . '</h2>';

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

            while ($rowCarrito = $resultCarrito->fetch_assoc()) {
                echo '<tr>
                        <td>' . $rowCarrito["id"] . '</td>
                        <td>' . $rowCarrito["nombre"] . '</td>
                        <td>$' . $rowCarrito["precio"] . '</td>
                        <td>' . $rowCarrito["cantidad"] . '</td>';

                $subtotal = $rowCarrito["cantidad"] * $rowCarrito["precio"];
                echo '<td>$' . $subtotal . '</td></tr>';

                $montoTotal += $subtotal;
            }

            echo '<tr><td colspan="4"><b>Monto Total</b></td><td><b>$' . $montoTotal . '</b></td></tr>';
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
            echo '<h2 class="mb-4">Carrito de ' . $username . '</h2>';
            echo "No se encontraron resultados en el carrito.";
            echo '<div class="btn-container">';
            echo '<button type="button" class="btn btn-outline-primary" onclick="window.history.back()">Regresar</button>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
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
