<?php
session_start();

require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["eliminar_articulo"])) {
    $carrito_id = $_POST["eliminar_articulo"];
    $usuario_id = $_SESSION["user_id"];

    $cantidad_sql = "SELECT cantidad, articulo_id FROM carritos WHERE id = ? AND usuario_id = ?";
    $stmt = $conexion->prepare($cantidad_sql);
    $stmt->bind_param("ii", $carrito_id, $usuario_id);
    $stmt->execute();
    $cantidad_resultado = $stmt->get_result();

    if ($cantidad_resultado && $cantidad_resultado->num_rows > 0) {
        $cantidad_fila = $cantidad_resultado->fetch_assoc();
        $cantidad_articulo = $cantidad_fila["cantidad"];
        $articulo_id = $cantidad_fila["articulo_id"];

        $eliminar_sql = "DELETE FROM carritos WHERE id = ?";
        $stmt = $conexion->prepare($eliminar_sql);
        $stmt->bind_param("i", $carrito_id);
        $stmt->execute();

        $actualizar_stock_sql = "UPDATE articulos SET stock = stock + ? WHERE id = ?";
        $stmt = $conexion->prepare($actualizar_stock_sql);
        $stmt->bind_param("ii", $cantidad_articulo, $articulo_id);
        $stmt->execute();

        echo json_encode(['status' => 'success', 'message' => 'Artículo eliminado']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Artículo no encontrado']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Solicitud inválida']);
}
?>