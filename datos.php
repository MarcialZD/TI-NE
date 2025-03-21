<?php
session_start();
include 'db_connect.php';
$sql = "SELECT a.nombre, SUM(va.cantidad) AS cantidad_vendida
FROM venta_articulo va
JOIN ventas v ON va.venta_id = v.id
JOIN articulos a ON va.articulo_id = a.id
GROUP BY a.nombre
ORDER BY cantidad_vendida DESC;
";

$resultado = $conexion->query($sql);

$productos = [];
$cantidades = [];

while ($fila = $resultado->fetch_assoc()) {
    $productos[] = $fila["nombre"];
    $cantidades[] = $fila["cantidad_vendida"];
}

echo json_encode(["productos" => $productos, "cantidades" => $cantidades]);
?>
