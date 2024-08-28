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

    // Conectar a la base de datos
    $conexion = new mysqli("localhost", "root", "123456", "nutricode");

    if ($conexion->connect_error) {
        die("La conexión a la base de datos falló: " . $conexion->connect_error);
    }

    $usuario_id = $_GET["id"]; // Cambia esto según el ID del usuario que estás consultando

    // Consulta SQL
    $sqlUsuario = "SELECT username FROM usuarios WHERE id = $usuario_id";
    $resultUsuario = $conexion->query($sqlUsuario);

    // Verificar si hay resultados de usuario
    if ($resultUsuario->num_rows > 0) {
        // Obtener el username del usuario
        $rowUsuario = $resultUsuario->fetch_assoc();
        $username = $rowUsuario["username"];

        // Consulta SQL para obtener detalles del carrito
        $sqlCarrito = "SELECT a.id, a.nombre, a.precio, c.cantidad, c.subtotal
            FROM carritos c
            JOIN articulos a ON c.articulo_id = a.id
            WHERE c.usuario_id = $usuario_id";

        // Ejecutar la consulta
        $resultCarrito = $conexion->query($sqlCarrito);

        // Verificar si hay resultados del carrito
        if ($resultCarrito->num_rows > 0) {
            // Inicializar el monto total
            $montoTotal = 0;

            // Imprimir datos en una tabla HTML
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

                // Calcular y mostrar el subtotal
                $subtotal = $rowCarrito["cantidad"] * $rowCarrito["precio"];
                echo '<td>$' . $subtotal . '</td></tr>';

                // Sumar al monto total
                $montoTotal += $subtotal;
            }

            // Mostrar el monto total en la última fila
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

    // Cerrar la conexión
    $conexion->close();
    ?>
</body>

</html>
