<?php
include 'getAccessToken.php';
include 'db_connect.php'; // Asegúrate de incluir esta línea antes de usar $mysqli
session_start();

// Configuración de PayPal
$clientId = 'Af49-brotlCa6LtOdP-RpqVx9QaE7vmtRFX1kU5MOC71sa5MyRM_EeyuYVMjAn1t18r5wtW9f1Up42Ud';
$clientSecret = 'ELqS2cdGPXv1U-Fzaioj88jkXxHgULYowy7G7cEFOc3iDxfVVFYIWG6tyM2BSMiHO4SQbaowbBrr9Weq';
$apiUrl = 'https://api-m.paypal.com'; 

// Obtener token de acceso
$accessToken = getAccessToken($clientId, $clientSecret, $apiUrl);

// Verificar que paymentId y PayerID existan en la URL
if (!isset($_GET['paymentId']) || !isset($_GET['PayerID'])) {
    die("Error: paymentId o PayerID no proporcionados.");
}

$paymentId = $_GET['paymentId'];
$payerId = $_GET['PayerID'];

// Ejecutar el pago a través de la API de PayPal
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$apiUrl/v1/payments/payment/$paymentId/execute");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array(
    'payer_id' => $payerId
)));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    "Authorization: Bearer $accessToken"
));
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Verificar que la ejecución de pago fue exitosa
if ($httpCode != 200) {
    die("Error: Unable to execute payment. HTTP Code: $httpCode");
}

$payment = json_decode($response);

// Verificar si la respuesta contiene la estructura esperada
if (!isset($payment->transactions[0]->amount->total)) {
    die("Error: Detalles de la transacción no disponibles.");
}

$total_final = $payment->transactions[0]->amount->total;
$usuario_id = $_SESSION['user_id']; // Asumiendo que el ID del usuario está en la sesión

// Iniciar la transacción
$conexion->begin_transaction();

try {
    // Verificar y obtener la dirección de la sesión
    $direccion = isset($_SESSION['direccion']) ? $_SESSION['direccion'] : 'No disponible';

    // Insertar venta en la tabla ventas
    $stmt = $conexion->prepare("INSERT INTO ventas (usuario_id, fecha_hora, monto_total, direccion) VALUES (?, NOW(), ?, ?)");
    $stmt->bind_param('ids', $usuario_id, $total_final, $direccion);
    $stmt->execute();

    // Obtener el ID de la venta
    $venta_id = $stmt->insert_id;
    $stmt->close();

    // Obtener los datos de la sesión para el artículo
    $mensaje = $_SESSION["mensaje"];
    $cantidad = 1;
    $id_pastel = $_SESSION["pastel_id"];

    // Insertar en venta_articulo
    $stmt2 = $conexion->prepare("INSERT INTO venta_articulo (venta_id, articulo_id, cantidad, subtotal, mensaje) VALUES (?, ?, ?, ?, ?)");
    $stmt2->bind_param('iiids', $venta_id, $id_pastel, $cantidad, $total_final, $mensaje);
    $stmt2->execute();
    $stmt2->close();

    // Actualizar el stock del artículo
    $stmt3 = $conexion->prepare("UPDATE articulos SET stock = stock - 1 WHERE id = ?");
    $stmt3->bind_param('i', $id_pastel);
    $stmt3->execute();
    $stmt3->close();

    // Confirmar la transacción
    $conexion->commit();

} catch (Exception $e) {
    // Deshacer la transacción en caso de error
    $conexion->rollback();
    die("Error: " . $e->getMessage());
}

// Cerrar la conexión a la base de datos
$conexion->close();

// Mostrar alerta y redirigir
echo "<script>
        alert('Compra exitosa');
        window.location.href = 'carrito.php';
      </script>";
?>
