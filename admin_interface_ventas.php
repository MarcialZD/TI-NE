<?php
session_start();

if (!isset($_SESSION["username"]) || !isset($_SESSION["es_admin"]) || $_SESSION["es_admin"] != 1) {
    header("location: login.php");
    exit();
}

require 'db_connect.php';

$sql = "SELECT v.id, v.monto_total, v.direccion, v.estado,  v.fecha_hora,v.transaccion_id
        FROM ventas v
       
        ORDER BY v.fecha_hora DESC";

$total_sql = "SELECT SUM(monto_total) AS total_monto FROM ventas"; 

$result = $conexion->query($sql);
$total_result = $conexion->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_monto = $total_row['total_monto'] ?? 0; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['estado'])) {
    $venta_id = $_POST['venta_id'];
    $nuevo_estado = $_POST['estado'];

    $check_sql = "SELECT id FROM ventas WHERE id = ?";
    $check_stmt = $conexion->prepare($check_sql);
    $check_stmt->bind_param("i", $venta_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $update_sql = "UPDATE ventas SET estado = ? WHERE id = ?";
        $stmt = $conexion->prepare($update_sql);
        $stmt->bind_param("si", $nuevo_estado, $venta_id);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Actualización exitosa.');
                    window.location.href = window.location.href; // Recargar la página
                  </script>";
        } else {
            $error_message = "Error al actualizar el estado: " . $conexion->error;
        }

        $stmt->close();
    } else {
        $error_message = "El ID de la venta no existe.";
    }

    $check_stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buscar_venta'])) {
    $venta_id_buscar = $_POST['venta_id_buscar'];

    $user_sql = "SELECT u.nombres, u.telefono, u.correo
                 FROM ventas v
                 JOIN usuarios u ON v.usuario_id = u.id
                 WHERE v.id = ?";
    $user_stmt = $conexion->prepare($user_sql);
    $user_stmt->bind_param("i", $venta_id_buscar);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();

    if ($user_result->num_rows > 0) {
        $user_data = $user_result->fetch_assoc();
    } else {
        $error_message = "No se encontraron datos de usuario para este ID de venta.";
    }

    $user_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interfaz de Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilos/Style_admin.css">
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-VX18B9GBD3');
    </script>
    <style>
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
        .custom-toggler {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3E%3Cpath stroke='black' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg fixed-top shadow-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="admin_interface.php">
            <img src="img/NutriCode_logo_sin_fondo.png" width="100" height="90" alt="">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="admin_interface.php" style="color: #000000;">Usuarios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_interface_productos.php" style="color: #000000;">Productos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_interface_ventas.php" style="color: #000000;">Ventas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cerrarsesion.php" style="color: #000000;">Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<br><br>
<div class="container mt-5 pt-5">
    <h2 class="mb-4">Listado de Ventas</h2>

    <form action="" method="POST" class="mb-4">
        <div class="row mb-3">
            <div class="col">
                <label for="venta_id" class="form-label">ID de la Venta</label>
                <input type="number" name="venta_id" id="venta_id" class="form-control" required readonly>
            </div>
            <div class="col">
                <label for="estado" class="form-label">Estado</label>
                <select name="estado" id="estado" class="form-select" required>
                    <option value="" disabled selected>Seleccione el estado</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="entregado">Entregado</option>
                    <option value="devolución">Devolución</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-secondary">Actualizar Estado</button>
    </form>

    <div class="alert alert-info">
        <strong>Monto Total de Ventas:</strong> $<?php echo number_format($total_monto, 2); ?>
    </div>

  

    <?php
    if (isset($user_data)) {
        echo "<div class='mt-4'>";
        echo "<h5>Datos del Usuario Asociado a la Venta:</h5>";
        echo "<table class='table table-bordered'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>ID Venta</th>";
        echo "<th>Nombre</th>";
        echo "<th>Teléfono</th>";
        echo "<th>Correo</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        echo "<tr>";
        echo "<td>" . $venta_id_buscar . "</td>"; 
        echo "<td>" . $user_data['nombres'] . "</td>";
        echo "<td>" . $user_data['telefono'] . "</td>";
        echo "<td>" . $user_data['correo'] . "</td>";
        echo "</tr>";
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
    }

    if (isset($error_message)) {
        echo "<div class='alert alert-danger mt-3'>$error_message</div>";
    }
    ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID Venta</th>
                <th>Transaccion ID</th>
                <th>Monto Total($)</th>
                <th>Dirección</th>
                <th>Estado</th>
                <th>Fecha y Hora</th>
                <th>Ver Detalle</th>
                <th>Seleccionar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";

                    echo "<td>" . $row['transaccion_id'] . "</td>";

                    echo "<td>" . $row['monto_total'] . "</td>";
                    echo "<td>" . $row['direccion'] . "</td>";
                    echo "<td>" . $row['estado'] . "</td>";
                    echo "<td>" . $row['fecha_hora'] . "</td>";
                    echo "<td><a href='detalle_venta.php?id=" . $row['id'] . "'>Ver Detalle</a></td>";
                    echo "<td><button class='btn btn-primary btn-sm' onclick='seleccionarVenta(" . $row['id'] . ")'>Seleccionar</button></td>";


                        echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No se encontraron ventas.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <script>
function seleccionarVenta(id) {
    document.getElementById("venta_id").value = id;
    window.scrollTo({ top: 0, behavior: "smooth" });

}
</script>
</div>
<canvas id="grafico"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
fetch("datos.php")  
    .then(response => response.json())
    .then(data => {
        const ctx = document.getElementById("grafico").getContext("2d");
        new Chart(ctx, {
            type: "bar",
            data: {
                labels: data.productos,
                datasets: [{
                    label: "Cantidad Vendida",
                    data: data.cantidades,
                    backgroundColor: "rgba(54, 162, 235, 0.5)",
                    borderColor: "rgba(54, 162, 235, 1)",
                    borderWidth: 1
                }]
            }
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
