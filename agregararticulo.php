<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["username"])) {
    header("location: login");
    exit();
}

// Conectar a la base de datos
require 'db_connect.php';

// Obtener todos los artículos de la tabla 'articulos'
$articulos_sql = "SELECT * FROM articulos";
$articulos_resultado = $conexion->query($articulos_sql);

// Verificar si se ha enviado el formulario para agregar al carrito
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["articulo_id"]) && isset($_POST["cantidad"])) {

    $articulo_id = $_POST["articulo_id"];
    $cantidad = $_POST["cantidad"];

    // Obtener el ID del usuario actual
    $usuario_id = $_SESSION["user_id"];

    // Usar una consulta preparada para verificar si el artículo ya está en el carrito
    $stmt = $conexion->prepare("SELECT * FROM carritos WHERE usuario_id = ? AND articulo_id = ?");
    $stmt->bind_param("ii", $usuario_id, $articulo_id);
    $stmt->execute();
    $verificar_resultado = $stmt->get_result();

    // Consultar el stock disponible del artículo
    $stmt = $conexion->prepare("SELECT stock FROM articulos WHERE id = ?");
    $stmt->bind_param("i", $articulo_id);
    $stmt->execute();
    $stock_resultado = $stmt->get_result();
    $stock_row = $stock_resultado->fetch_assoc();
    $stock_disponible = $stock_row['stock'];

    if ($stock_disponible < $cantidad) {
        echo '<script>alert("No hay suficiente stock disponible."); window.location.href = "productos.php";</script>';
    } else {

        // Si el artículo no está en el carrito, insertarlo
        // Primero, obtenemos el precio final del artículo considerando el descuento
        $stmt_precio = $conexion->prepare("
            SELECT 
                CASE 
                    WHEN fecha_maxima_descuento >= CURDATE() THEN 
                        precio - (precio * porcentaje_descuento / 100)
                    ELSE 
                        precio 
                END AS precio_final
            FROM articulos 
            WHERE id = ?
        ");
        $stmt_precio->bind_param("i", $articulo_id);
        $stmt_precio->execute();
        $stmt_precio->bind_result($precio_final);
        $stmt_precio->fetch();
        $stmt_precio->close();

        // Redondear el precio final a dos decimales
        $precio_final = round($precio_final, 2);

        // Calculamos el subtotal usando el precio final (con descuento si aplica)
        $subtotal = $cantidad * $precio_final;

        // Ahora insertamos en la tabla carritos
        $stmt = $conexion->prepare("INSERT INTO carritos (usuario_id, articulo_id, cantidad, subtotal) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $usuario_id, $articulo_id, $cantidad, $subtotal);
        $stmt->execute();
        $stmt->close();

        // Actualizar el stock disponible
        $nuevo_stock = $stock_disponible - $cantidad;
        $stmt = $conexion->prepare("UPDATE articulos SET stock = ? WHERE id = ?");
        $stmt->bind_param("ii", $nuevo_stock, $articulo_id);
        $stmt->execute();

        // Redirigir a carrito.php después de agregar al carrito
        echo '<script>alert("Artículo agregado"); window.location.href = "productos.php";</script>';
        exit();
    }
}

// cantidad para el carrito
$usuario_id = $_SESSION["user_id"];
$consulta = "SELECT COUNT(*) AS total_productos, SUM(cantidad) AS cantidad_total FROM carritos WHERE usuario_id = ?";
$stmt = $conexion->prepare($consulta);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

// Verifica si la consulta fue exitosa
if ($resultado) {
    $fila = $resultado->fetch_assoc();
    $cant_total_productos = $fila['cantidad_total'] ?? 0;
} else {
    $cant_total_productos = 0;
}

$conexion->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    

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
</head>
<body>
    
</body>
</html>