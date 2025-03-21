<?php
session_start();
include 'getAccessToken.php';
$total_final = isset($_SESSION['total_final']) ? $_SESSION['total_final'] : 0;
   $direccion = $_POST['address'];

   $_SESSION['direccion'] = $direccion;

if ($total_final <= 0) {
    die("Error: Total final debe ser mayor que cero.");
}

// Configuración
$clientId = 'AdX1t765961g-kvZxbV305khPY43GrFw_XyQOSV3zjw5-UE3QwRNH51jqoVH04aE1IsJ8NzYl8sK0Z7G';
$clientSecret = 'EOT3tcBYufLqZl4WU53O7KrdvghdzVTxexE_jWpEnY81RBCY47Hz1nfsSrNmW_d7YjXuf3h1DrATJe9U';
$apiUrl = 'https://api-m.sandbox.paypal.com'; 

$accessToken = getAccessToken($clientId, $clientSecret, $apiUrl);
if (!$accessToken) {
    die("Error: Unable to get access token.");
}

$formattedTotal = number_format($total_final, 2, '.', '');

// Crear pago
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$apiUrl/v1/payments/payment");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array(
    'intent' => 'sale',
    'redirect_urls' => array(
        // 'return_url' => 'http://dargelreposteria.com/success.php',
        // 'cancel_url' => 'http://dargelreposteria.com/cancel.php'
        'return_url' => 'http://localhost/TI_negocios/success.php',
        'cancel_url' => 'http://localhost/TI_negocios/cancel.php'
    ),
    'payer' => array('payment_method' => 'paypal'),
    'transactions' => array(array(
        'amount' => array(
            'total' => $formattedTotal,
            'currency' => 'USD'
        ),
        'description' => 'Description of the transaction'
    ))
)));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    "Authorization: Bearer $accessToken"
));
$response = curl_exec($ch);
if (curl_errno($ch)) {
    die("cURL Error: " . curl_error($ch));
}
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Imprimir la respuesta y el código de estado HTTP para depuración
echo "HTTP Code: $httpCode<br>";
echo "Response: <pre>$response</pre>";

if ($httpCode != 201) {
    die("Error: Unable to create payment. HTTP Code: $httpCode");
}

$payment = json_decode($response);
if (json_last_error() !== JSON_ERROR_NONE) {
    die("JSON Decode Error: " . json_last_error_msg());
}

// Redirigir al usuario a PayPal para completar el pago
if (isset($payment->links)) {
    foreach ($payment->links as $link) {
        if ($link->rel == 'approval_url') {
            header("Location: " . $link->href);
            exit;
        }
    }
} else {
    die("Error: Approval URL not found in response.");
}