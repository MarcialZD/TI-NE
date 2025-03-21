<?php
include 'getAccessToken.php';
include 'db_connect.php'; // Asegúrate de incluir esta línea antes de usar $mysqli
session_start();

// Configuración
$clientId = 'AdX1t765961g-kvZxbV305khPY43GrFw_XyQOSV3zjw5-UE3QwRNH51jqoVH04aE1IsJ8NzYl8sK0Z7G';
$clientSecret = 'EOT3tcBYufLqZl4WU53O7KrdvghdzVTxexE_jWpEnY81RBCY47Hz1nfsSrNmW_d7YjXuf3h1DrATJe9U';
$apiUrl = 'https://api-m.sandbox.paypal.com'; // URL de Sandbox

// Obtener token de acceso
$accessToken = getAccessToken($clientId, $clientSecret, $apiUrl);

// Obtener el ID del pago y el Payer ID de la URL de retorno
$paymentId = $_GET['paymentId'];
$payerId = $_GET['PayerID'];

// Ejecutar el pago
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

if ($httpCode != 200) {
    die("Error: Unable to execute payment. HTTP Code: $httpCode");
}

$payment = json_decode($response);

// Obtener detalles de la transacción
$total_final = $payment->transactions[0]->amount->total;
$usuario_id = $_SESSION['user_id']; // Asumiendo que el ID del usuario está en la sesión

// Iniciar la transacción
$conexion ->begin_transaction();
$transaction_id = $payment->transactions[0]->related_resources[0]->sale->id ?? 'N/A';

try {
    // Insertar venta en la tabla ventas
    if (isset($_SESSION['direccion'])) {
        $direccion = $_SESSION['direccion'];
    } else {
        // Manejar el caso en que no se haya establecido una dirección
        $direccion = 'No disponible'; // Valor por defecto
    }
    $estado="pendiente";
    $stmt = $conexion->prepare("INSERT INTO ventas (usuario_id, fecha_hora, monto_total, direccion,transaccion_id,estado) VALUES (?, NOW(), ?, ?,?,?)");
    $stmt->bind_param('idsss', $usuario_id, $total_final, $direccion,$transaction_id,$estado);
    $stmt->execute();
    
    $venta_id = $stmt->insert_id;
    $stmt->close();

    // Transferir artículos del carrito a venta_articulo y eliminar del carrito
    $stmt = $conexion ->prepare("SELECT articulo_id, cantidad, subtotal,mensaje FROM carritos WHERE usuario_id = ?");
    $stmt->bind_param('i', $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        // Insertar en venta_articulo
        $stmt2 = $conexion ->prepare("INSERT INTO venta_articulo (venta_id, articulo_id, cantidad, subtotal,mensaje) VALUES (?, ?, ?, ?,?)");
        $stmt2->bind_param('iiids', $venta_id, $row['articulo_id'], $row['cantidad'], $row['subtotal'],$row['mensaje']);
        $stmt2->execute();
        $stmt2->close();
    }

    $stmt->close();

    // Eliminar artículos del carrito
    $stmt = $conexion ->prepare("DELETE FROM carritos WHERE usuario_id = ?");
    $stmt->bind_param('i', $usuario_id);
    $stmt->execute();
    $stmt->close();

    // Confirmar la transacción
    $conexion ->commit();

} catch (Exception $e) {
    // Deshacer la transacción en caso de error
    $conexion ->rollback();
    die("Error: " . $e->getMessage());
}

// Cerrar la conexión
$conexion ->close();

// Mostrar alerta y redirigir
echo "<script>
        alert('Compra exitosa');
        window.location.href = 'carrito.php';
      </script>";
