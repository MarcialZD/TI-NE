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
unset($_SESSION['total_final']);
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realizar Pago</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="estilos/Styles_pago.css">
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
                        <a class="nav-link" href="carrito.php" style="color: #000000;">
                            <?php echo $_SESSION["username"]; ?> &nbsp
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                            </svg>(<?php echo $cant_total_productos; ?>)
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <form action="realizar_pago.php" method="post" style="border: 2px solid black; padding: 30px; margin-top:40px;">
        <h1 class="title">Pago con Tarjeta de Débito</h1>
        <p>Usuario: <b><?php echo $_SESSION["username"]; ?></b> </p>
        <label for="nombre">
            <i class="fa-solid fa-user"></i>
            <input placeholder="Nombre del titular" type="text" name="nombre" id="nombre" autocomplete="off" required>
        </label>
        <label for="card_number">
            <i class="fa-solid fa-credit-card"></i>
            <input placeholder="Número de tarjeta" type="text" name="card_number" id="card_number" autocomplete="off" required>
        </label>
        <label for="card_cvv">
            <i class="fa-solid fa-lock"></i>
            <input placeholder="CVV" type="text" name="card_cvv" id="card_cvv" autocomplete="off" required>
        </label>
        <label for="card_expiry" style="margin-left: -5px;">
            <i class="fa-solid fa-calendar"></i>
            <input placeholder="Fecha de vencimiento" type="text" name="card_expiry" id="card_expiry" 
                   autocomplete="off" required pattern="^(0[1-9]|1[0-2])\/?([0-9]{2})$" 
                   title="El formato debe ser MM/YY, por ejemplo, 08/24" maxlength="5" oninput="formatExpiry(this)">
        </label>
        <label for="amount_to_pay" style="margin: 0" class="mb-2">
            <i class="fa-solid fa-dollar-sign"></i>
            <?php echo (number_format($total_final , 2)); ?>
        </label>
        <button type="submit" class="btn btn-success mb-2" style="width: 30%; margin: auto"> Realizar Pago</button>
        <a href="carrito.php" style="margin-bottom: 5px">Regresar</a>
    </form>
</body>
<script>
function formatExpiry(input) {
    let value = input.value.replace(/\D/g, ''); // Elimina todo lo que no sea un número
    if (value.length >= 2) {
        input.value = value.substring(0, 2) + '/' + value.substring(2, 4); // Agrega el '/'
    } else {
        input.value = value;
    }
}
</script>

</html>
