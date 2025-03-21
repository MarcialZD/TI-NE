<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("location: login.php");
    exit();
}
if($_SESSION["es_admin"] == 1){
    echo("<script>window.location.href ='admin_interface.php';</script>");
}

require 'db_connect.php';

$usuario_id = $_SESSION["user_id"];

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
    $subtotal = $item['cantidad'] * $item['precio_final'];
    $total_pagar += $subtotal;
    $carrito_items[] = $item;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['finalizar_compra'])) {
    $total_final = $_SESSION['total_final'];
    $_SESSION['selected_district'] = $selected_district ?? $_SESSION['selected_district'];
    header('Location: realizar_pago_paypal.php');
    exit();
}

$selected_district = $_SESSION['selected_district'] ?? '';
$delivery_fee = isset($district_prices[$selected_district]) ? $district_prices[$selected_district] : 0;
$total_final = $total_pagar + $delivery_fee;

$_SESSION['total_final'] = $total_final;

$ventas_sql = "
    SELECT 
        v.id AS id_venta, v.transaccion_id,
        v.monto_total, 
        v.fecha_hora, 
        v.direccion, v.estado
    FROM 
        ventas v
    WHERE 
        v.usuario_id = ? 
    ORDER BY 
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

    <div class="container container-sm" style="margin-top: 5%;">
        <?php if ($carrito_resultado->num_rows > 0): ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="cart-table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Subtotal</th>
                                    <th>Personalización</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($carrito_items as $item): ?>
                                    <tr data-id="<?php echo $item['id']; ?>">
                                        <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                                        <td><?php echo number_format($item['precio_final'], 2); ?></td>
                                        <td><?php echo htmlspecialchars($item['cantidad']); ?></td>
                                        <td><?php echo number_format($item['cantidad'] * $item['precio_final'], 2); ?></td>
                                        <td><?php echo htmlspecialchars($item['mensaje']); ?></td>
                                        <td>
                                            <button class=" eliminar-articulo" data-id="<?php echo $item['id']; ?>">Eliminar</button>
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
                                    <td id="total_pagar">$ <?php echo number_format($total_pagar, 2); ?></td>
                                </tr>
                                <tr>
                                    <th>Distrito seleccionado:</th>
                                    <td id="selected_district"><?php echo htmlspecialchars($selected_district); ?></td>
                                </tr>
                                <tr>
                                    <th>Precio de entrega por distrito:</th>
                                    <td id="delivery_fee">$ <?php echo number_format($delivery_fee, 2); ?></td>
                                </tr>
                                <tr>
                                    <th>Total final:</th>
                                    <td id="total_final">$ <?php echo number_format($total_final, 2); ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <form id="district-form" method="post">
                            <div class="form-group">
                                <label for="district">Seleccionar Distrito:</label>
                                <select id="district" name="district" class="form-select">
                                    <option value="" selected>Selecciona un distrito</option>
                                    <?php foreach ($district_prices as $district => $price): ?>
                                        <option value="<?= $district ?>" data-price="<?= $price ?>" <?= $selected_district === $district ? 'selected' : ''; ?>>
                                            <?= $district ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div><br><br>
                            <a href="productos.php" >Añadir más productos</a>
                        </form>
                        <form id="finalizar-compra-form" action="carrito.php" method="post">
                            <button type="submit" name="finalizar_compra" class="btn btn-danger">Finalizar Compra</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <p class="lead">Tu carrito está vacío.</p>
        <?php endif; ?>

        <?php if ($ventas_resultado->num_rows == 0): ?>
            <p class="lead">No realizaste Compras</p>
        <?php else: ?>
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
                            <th>Ver Detalle</th>
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
                                <td>
    <a href="detalle_venta.php?id=<?php echo $venta['id_venta']; ?>" >Ver Detalle</a>
</td>

                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <script>
    $(document).ready(function() {
        $('#district').on('change', function() {
            let selectedDistrict = $(this).val();
            let deliveryFee = $(this).find(':selected').data('price') || 0;
            let totalPagar = parseFloat($('#total_pagar').text().replace('$', '').replace(',', ''));

            $('#selected_district').text(selectedDistrict);
            $('#delivery_fee').text('$' + parseFloat(deliveryFee).toFixed(2));
            $('#total_final').text('$' + parseFloat(totalPagar + deliveryFee).toFixed(2));

            if (selectedDistrict) {
                $('#finalizar-compra-form').show();
            } else {
                $('#finalizar-compra-form').hide();
            }

            $.ajax({
                url: 'actualizar_distrito.php',
                method: 'POST',
                data: {
                    district: selectedDistrict,
                    total_pagar: totalPagar
                },
                success: function(response) {
                    console.log('Distrito actualizado:', response);
                },
                error: function(xhr, status, error) {
                    console.error('Error al actualizar el distrito:', error);
                }
            });
        });

        $('.eliminar-articulo').on('click', function() {
            let carritoId = $(this).data('id');
            let row = $(this).closest('tr');
            let subtotal = parseFloat(row.find('td:nth-child(4)').text().replace(',', ''));

            $.ajax({
                url: 'eliminar_articulo_carrito.php',
                method: 'POST',
                data: { eliminar_articulo: carritoId },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        row.remove();

                        let totalPagar = parseFloat($('#total_pagar').text().replace('$', '').replace(',', '')) - subtotal;
                        $('#total_pagar').text('$' + totalPagar.toFixed(2));

                        let deliveryFee = parseFloat($('#delivery_fee').text().replace('$', '')) || 0;
                        $('#total_final').text('$' + (totalPagar + deliveryFee).toFixed(2));

                        let currentCount = parseInt($('.fa-cart-shopping').text().match(/\d+/) || 0);

                        if ($('#cart-table tbody tr').length === 0) {
                            $('#cart-table').replaceWith('<p class="lead">Tu carrito está vacío.</p>');
                            $('#finalizar-compra-form').hide();
                        }

                        $.ajax({
                            url: 'actualizar_distrito.php',
                            method: 'POST',
                            data: {
                                district: $('#selected_district').text(),
                                total_pagar: totalPagar
                            },
                            success: function(response) {
                                console.log('Total final actualizado en la sesión:', response);
                            }
                        });

                        alert('Artículo eliminado y stock actualizado');
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al eliminar el artículo:', error);
                }
            });
        });
    });
    </script>
</body>
</html>