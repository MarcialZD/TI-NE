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




// Consulta para obtener los artículos en el carrito del usuario, considerando el precio con descuento si aplica
$carrito_sql = "
    SELECT 
        c.id,
        c.articulo_id,
        a.nombre,
        CASE 
            WHEN a.fecha_maxima_descuento >= CURDATE() THEN 
                a.precio - (a.precio * a.porcentaje_descuento / 100)
            ELSE 
                a.precio 
        END AS precio_final,
        c.cantidad,
        c.mensaje
    FROM carritos c
    JOIN articulos a ON c.articulo_id = a.id
    WHERE c.usuario_id = ?
";

$stmt = $conexion->prepare($carrito_sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$carrito_resultado = $stmt->get_result();

// Consulta para contar artículos y obtener cantidad total
$consulta = "
    SELECT COUNT(*) AS total_productos, SUM(cantidad) AS cantidad_total
    FROM carritos
    WHERE usuario_id = ?
";

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
    'Los Olivos' => 3,
    'Comas' => 5,
    'Ventanilla' => 6,
    'San Isidro' => 8,
    'Miraflores' => 10,
    'Barranco' => 7,
    'Surco' => 6,
    'La Molina' => 9,
    'San Borja' => 8,
    'San Miguel' => 6,
    'Lince' => 3,
    'Pueblo Libre' => 6,
    'Chorrillos' => 5,
    'Magdalena' => 7,
    'Rímac' => 5,
];


$total_pagar = 0;
$carrito_items = [];
while ($item = $carrito_resultado->fetch_assoc()) {
    // Usamos el precio_final calculado con descuento si corresponde
    $subtotal = $item['cantidad'] * $item['precio_final'];  // Cambiar 'precio' por 'precio_final'
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



$ventas_sql = "
    SELECT 
        v.id AS id_venta, v.transaccion_id,
        v.monto_total, 
        v.fecha_hora, 
        v.direccion, v.estado
    FROM 
        ventas v
    
    WHERE 
        v.usuario_id = ?  ORDER BY 
        v.fecha_hora DESC
";

$stmt = $conexion->prepare($ventas_sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$ventas_resultado = $stmt->get_result();
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
   

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-6BER8BQQ0Z"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-6BER8BQQ0Z');
</script>


<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KK32PFZL"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->



  
    <link rel="icon" href="images/logo.png" type="image/png">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-KK32PFZL');</script>
<!-- End Google Tag Manager -->
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<!-- Hotjar Tracking Code for Site 5212520 (name missing) -->
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:5212520,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
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



        #user-input {
            flex-grow: 1;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-right: 5px;
        }

        button {
            background-color: #e881a2;
            color: white;
            border: none;
            cursor: pointer;
            padding: 5px;
            border-radius: 5px;
        }
    </style>
    
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-light ">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="img/NutriCode_logo_sin_fondo.png" width="100" height="90" alt="Logo">
            </a>
            <button class="navbar-toggler custom-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav" style="color: #0e9edf;">
                <ul class="navbar-nav ms-auto" style="color: #0e9edf;">
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


    <div class="container container-sm" style="margin-top: 5%; ">
   

        <?php if ($carrito_resultado->num_rows > 0): ?>
            <div class=" row">
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
                                <td><?php echo number_format($item['precio_final'], 2); ?></td>
                                <td><?php echo htmlspecialchars($item['cantidad']); ?></td>
                                <td><?php echo number_format($item['cantidad'] * $item['precio_final'], 2); ?></td>
                                <td><?php echo htmlspecialchars($item['mensaje']); ?></td>

                                <td>
                                    <form method="post">
                                        <button type="submit" name="eliminar_articulo" value="<?php echo ($item['id']); ?>" class="btn btn-danger">Eliminar</button>
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
                            <td>$ <?php echo number_format($total_pagar, 2); ?></td>
                        </tr>
                        <tr>
                            <th>Distrito seleccionado:</th>
                            <td><?php echo ($selected_district); ?></td>
                        </tr>
                        <tr>
                            <th>Precio de entrega por distrito:</th>
                            <td>$ <?php echo number_format($delivery_fee, 2); ?></td>
                        </tr>
                        <tr>
                            <th>Total final:</th>
                            <td>$ <?php echo number_format($total_final, 2); ?></td>
                        </tr>
                    </tbody>
                </table>
                <form action="carrito.php" method="post">
                    <div class="form-group">
                        <label for="district">Seleccionar Distrito:</label>
                        <select id="district" name="district" class="form-select">
                            <option value="" selected>Selecciona un distrito</option>
                            <option value="Los Olivos" <?php echo $selected_district === 'Los Olivos' ? 'selected' : ''; ?>>Los Olivos</option>
                            <option value="Comas" <?php echo $selected_district === 'Comas' ? 'selected' : ''; ?>>Comas</option>
                            <option value="Ventanilla" <?php echo $selected_district === 'Ventanilla' ? 'selected' : ''; ?>>Ventanilla</option>
                            <option value="San Isidro" <?php echo $selected_district === 'San Isidro' ? 'selected' : ''; ?>>San Isidro</option>
                            <option value="Miraflores" <?php echo $selected_district === 'Miraflores' ? 'selected' : ''; ?>>Miraflores</option>
                            <option value="Barranco" <?php echo $selected_district === 'Barranco' ? 'selected' : ''; ?>>Barranco</option>
                            <option value="Surco" <?php echo $selected_district === 'Surco' ? 'selected' : ''; ?>>Surco</option>
                            <option value="La Molina" <?php echo $selected_district === 'La Molina' ? 'selected' : ''; ?>>La Molina</option>
                            <option value="San Borja" <?php echo $selected_district === 'San Borja' ? 'selected' : ''; ?>>San Borja</option>
                            <option value="San Miguel" <?php echo $selected_district === 'San Miguel' ? 'selected' : ''; ?>>San Miguel</option>
                            <option value="Lince" <?php echo $selected_district === 'Lince' ? 'selected' : ''; ?>>Lince</option>
                            <option value="Pueblo Libre" <?php echo $selected_district === 'Pueblo Libre' ? 'selected' : ''; ?>>Pueblo Libre</option>
                            <option value="Chorrillos" <?php echo $selected_district === 'Chorrillos' ? 'selected' : ''; ?>>Chorrillos</option>
                            <option value="Magdalena" <?php echo $selected_district === 'Magdalena' ? 'selected' : ''; ?>>Magdalena</option>
                            <option value="Rímac" <?php echo $selected_district === 'Rímac' ? 'selected' : ''; ?>>Rímac</option>
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





<?php else: ?>
    <p class="lead">Tu carrito está vacío.</p>
<?php endif; ?>

<?php 
if(($ventas_resultado->num_rows == 0)){

?>
    <p class="lead">No realizaste Compras</p>

<?php 
}else{


?>

<h2 class="mt-5">Mis Compras</h2>
<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>Transacción</th>
                <th>Monto Total ($)</th>
                <th>Fecha y Hora</th>
                <th>Dirección</th>
                <th>Estado</th>
            </tr>
        </thead>
        
        <tbody>
            <?php while ($venta = $ventas_resultado->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($venta['transaccion_id']); ?></td>
                    <td><?php echo number_format($venta['monto_total'], 2); ?></td>
                    <td><?php echo htmlspecialchars($venta['fecha_hora']); ?></td>
                    <td><?php echo htmlspecialchars($venta['direccion']); ?></td>
                    <td><?php echo htmlspecialchars($venta['estado']); ?></td>

                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php }?>


</body>

</html>