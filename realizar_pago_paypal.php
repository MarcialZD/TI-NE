<!DOCTYPE html>
<html lang="en">

<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["username"])) {
    header("location: login.php");
    exit();
}

// Importar la conexión a la base de datos
require 'db_connect.php';

// Obtener el ID del usuario actual
$usuario_id = $_SESSION["user_id"];

// Obtener los artículos en el carrito del usuario
$carrito_sql = "SELECT c.articulo_id, a.nombre, a.precio, c.cantidad FROM carritos c
                JOIN articulos a ON c.articulo_id = a.id
                WHERE c.usuario_id = $usuario_id";
$carrito_resultado = $conexion->query($carrito_sql);

if ($carrito_resultado->num_rows > 0) {
    $total_pagar = 0;
    while ($item = $carrito_resultado->fetch_assoc()) {
        $subtotal = $item['cantidad'] * $item['precio'];
        $total_pagar += $subtotal;
    }
}
$total_final = isset($_SESSION['total_final']) ? $_SESSION['total_final'] : 0;

// Cantidad de artículos en carrito
$consulta = "SELECT COUNT(*) AS total_productos, SUM(cantidad) AS cantidad_total
             FROM carritos
             WHERE usuario_id = $usuario_id";

// Ejecutar la consulta
$resultado = $conexion->query($consulta);

// Verifica si la consulta fue exitosa
if ($resultado) {
    // Obtener el resultado como un array asociativo
    $fila = $resultado->fetch_assoc();

    // Almacena el total de productos en una variable
    $cant_total_productos = $fila['cantidad_total'];

    if ($cant_total_productos == null) {
        $cant_total_productos = 0;
    }
} else {
    // Si la consulta falla, muestra un mensaje de error
    echo "Error al obtener la cantidad de productos: " . $conexion->error;
}

// Cerrar la conexión a la base de datos
$conexion->close();



?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realizar Pago</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://www.paypal.com/sdk/js?client-id=AdX1t765961g-kvZxbV305khPY43GrFw_XyQOSV3zjw5-UE3QwRNH51jqoVH04aE1IsJ8NzYl8sK0Z7G" rel="stylesheet"> <!-- Incluye el SDK de PayPal -->
    <style>
        .paypal-button-container {
            text-align: center;
            margin: 20px 0;
        }
    </style>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-VX18B9GBD3');
    </script>
    <style>
        /* Estilos existentes */
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
            text-align: center;
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
        footer {
            background-color: #faeae5;
            margin-top: 4%;
            color: #e8306d;
            padding: 20px 0;
        }

        footer a {
            color: #e8306d;


            text-decoration: none;
        }

        footer img {
            width: 30px;
            height: 30px;
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

    <div class="container">
        <br> <br>
        <h1>Realizar Pago</h1>
        <p>Total a pagar: $ <?php echo number_format($total_final, 2); ?></p>
        <form action="createpayment.php" method="post" style="width: 30%; margin: 0 auto;">
            <input type="hidden" name="amount" value="">
            <input type="hidden" name="currency_code" value="USD">

            <div class="form-group">
                <label for="address" style="color:#000000">Dirección del Pedido:</label>
                <br> <br>
                <input type="text" id="address" name="address" class="form-control" placeholder="Ingresa tu dirección" required>
            </div>
            <br><br>
            <div class="form-group">
                <button type="submit" class="paypal-button">Pagar con PayPal</button>
            </div>
        </form>

    </div>


</body>
<footer class="py-4">
        <div class="container">
            <div class="row text-center text-md-start">
                <div class="col-md-4 mb-3">
                    <h5>Contacto</h5>
                    <p><i class="fa-solid fa-envelope"></i> Correo: <a href="mailto:dargel@dargelreposteria.com">dargel@dargelreposteria.com</a></p>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>Redes Sociales</h5>
                    <p><a href="https://www.facebook.com/profile.php?id=100063723304943" target="_blank"><i class="fa-brands fa-facebook"></i> Facebook</a></p>
                    <p><a href="https://instagram.com" target="_blank"><i class="fa-brands fa-instagram"></i> Instagram</a></p>
                    <p><a href="https://tiktok.com" target="_blank"><i class="fa-brands fa-tiktok"></i> TikTok</a></p> <!-- Añadido TikTok -->
                </div>
                <div class="col-md-4 mb-3">
                    <h5>Información</h5>
                    <p>Somos una empresa dedicada a ofrecer los mejores pasteles personalizados para cada ocasión.</p>
                </div>
            </div>
        </div>
        <div class="text-center">
            <p>&copy; 2024 Reposteria Dargel</p>
        </div>
    </footer>
</html>