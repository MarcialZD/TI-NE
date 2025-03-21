<?php
session_start();

require 'db_connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['district'])) {
    $selected_district = $_POST['district'];
    $total_pagar = floatval($_POST['total_pagar']);

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

    $delivery_fee = array_key_exists($selected_district, $district_prices) ? $district_prices[$selected_district] : 0;
    $total_final = $total_pagar + $delivery_fee;

    $_SESSION['selected_district'] = $selected_district;
    $_SESSION['total_final'] = $total_final;

    echo json_encode(['status' => 'success', 'total_final' => $total_final]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Solicitud inválida']);
}
?>