<?php
session_start();
include 'db_connect.php';

// db_connect.php
$host = "sql101.infinityfree.com";
$user = "if0_37311818";
$password = "Ql8Biwu7fymI5s";
$database = "if0_37311818_nutricode";

// Crear la conexión
$conexion = new mysqli($host, $user, $password, $database);

// Verificar si la conexión fue exitosa
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
} else {
    echo "OK<br>";
    
    // Recibir los parámetros desde la URL
    $username = isset($_GET['nick']) ? $_GET['nick'] : '';
    $entered_password = isset($_GET['contra']) ? $_GET['contra'] : '';
    $pastel_nombre = isset($_GET['pastel_nombre']) ? $_GET['pastel_nombre'] : '';
    $relleno = isset($_GET['relleno']) ? $_GET['relleno'] : '';
    $cobertura = isset($_GET['cobertura']) ? $_GET['cobertura'] : '';
    $tipo_pastel = isset($_GET['tipo_pastel']) ? $_GET['tipo_pastel'] : '';
    $direccion = isset($_GET['direccion']) ? $_GET['direccion'] : '';
    $distrito = isset($_GET['distrito']) ? $_GET['distrito'] : '';
    // Definir el precio_delivery según el distrito
if ($distrito == 'ventanilla') {
    $precio_delivery = 6; // Precio para ventanilla
} elseif (in_array($distrito, ['comas', 'los_olvios', 'independencia'])) {
    $precio_delivery = 3; // Precio para comas, los_olvios, independencia
} else {
    $precio_delivery = 0; // Puedes poner un valor por defecto, si no coincide con ninguno de los anteriores
}


    $mensaje_pastel = isset($_GET['mensaje_pastel']) ? $_GET['mensaje_pastel'] : '';
    $comentario = "Relleno: " . $relleno . ", Cobertura: " . $cobertura . ", Tipo de pastel: " . $tipo_pastel . ", Mensaje en el pastel: " . $mensaje_pastel;
echo($comentario);

    // Imprimir los valores para verificar que están capturados correctamente
    echo "Nick: " . $username . "<br>";
    echo "Contraseña: " . $entered_password . "<br>";
    echo "Nombre del pastel: " . $pastel_nombre . "<br>";
    echo "Relleno: " . $relleno . "<br>";
    echo "Cobertura: " . $cobertura . "<br>";
    echo "Tipo de pastel: " . $tipo_pastel . "<br>";
    echo "Dirección: " . $direccion . "<br>";
    echo "Mensaje en el pastel: " . $mensaje_pastel . "<br>";
   
    // Realizamos la consulta para obtener el nombre de usuario y la contraseña cifrada
    $sql = "SELECT * FROM usuarios WHERE username = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Verificar si encontramos el usuario
    if ($resultado->num_rows > 0) {
        // Si encontramos el usuario, obtenemos la fila
        $fila = $resultado->fetch_assoc();

        // Verificar si la contraseña ingresada coincide con la almacenada en la base de datos
        if (password_verify($entered_password, $fila['password'])) {
            // Si la contraseña es correcta, mostramos una alerta
            echo "<script>alert('¡Contraseña correcta!');</script>";

            // Obtener el ID del usuario
            $user_id = $fila['id'];

             $_SESSION['user_id'] = $user_id ;

            // Obtener el ID del pastel utilizando el nombre del pastel
            $sql_pastel = "SELECT * FROM articulos WHERE nombre = ?";
            $stmt_pastel = $conexion->prepare($sql_pastel);
            $stmt_pastel->bind_param('s', $pastel_nombre);
            $stmt_pastel->execute();
            $resultado_pastel = $stmt_pastel->get_result();

            if ($resultado_pastel->num_rows > 0) {
                // Si encontramos el pastel, obtenemos su ID
                $fila_pastel = $resultado_pastel->fetch_assoc();
                $pastel_id = $fila_pastel['id'];
                $_session["pastel_id"] = $pastel_id;

                               echo "<script>alert('El pastel existe en la base de datos.');</script> ".$pastel_id."";
                                $sql_precio = "SELECT * FROM articulos WHERE id = ?";
    $stmt_precio = $conexion->prepare($sql_precio);
    $stmt_precio->bind_param('i', $pastel_id); // 'i' porque el id es un entero
    $stmt_precio->execute();
    $resultado_precio = $stmt_precio->get_result();
    
    if ($resultado_precio->num_rows > 0) {
        // Si encontramos el precio, lo obtenemos
        $fila_precio = $resultado_precio->fetch_assoc();
        $precio_pastel = $fila_precio['precio'];
        
        // Mostrar el precio
        echo "<script>alert('El precio del pastel es: " . $precio_pastel . "');</script>";

         $stock_pastel = $fila_precio['stock']; // Asumimos que hay un campo 'stock'
        
        // Mostrar el precio y el stock
        if ($stock_pastel > 0) {
            echo "<script>alert('El precio del pastel es: " . $precio_pastel . " y hay " . $stock_pastel . " en stock.');</script>";

            $precio_final = $precio_pastel + $precio_delivery;
$_session["direccion"]=$direccion;
$_session["mensaje"]=$mensaje_pastel;



            include 'getAccessToken.php';

            $clientId = 'Af49-brotlCa6LtOdP-RpqVx9QaE7vmtRFX1kU5MOC71sa5MyRM_EeyuYVMjAn1t18r5wtW9f1Up42Ud';
            $clientSecret = 'ELqS2cdGPXv1U-Fzaioj88jkXxHgULYowy7G7cEFOc3iDxfVVFYIWG6tyM2BSMiHO4SQbaowbBrr9Weq';
            $apiUrl = 'https://api-m.paypal.com'; 
// Obtener token de acceso
$accessToken = getAccessToken($clientId, $clientSecret, $apiUrl);
if (!$accessToken) {
    die("Error: Unable to get access token.");
}

// Formatear monto
$formattedTotal = number_format($precio_final, 2, '.', '');

// Crear pago
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$apiUrl/v1/payments/payment");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array(
    'intent' => 'sale',
    'redirect_urls' => array(
         'return_url' => 'https://dargelreposteria.com/success_bt.php',
         'cancel_url' => 'https://dargelreposteria.com/cancel.php'
        //'return_url' => 'http://localhost/TI_negocios/success_bt.php',
        //'cancel_url' => 'http://localhost/TI_negocios/cancel.php'
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



        } else {
            echo "<script>alert('El pastel está fuera de stock.');</script>";
        }



    } else {
        // Si no encontramos el precio
        echo "<script>alert('No se encontró el precio del pastel.');</script>";
    }

               
            } else {
                echo "<script>alert('El pastel no existe en la base de datos.');</script>";
            }
        } else {
            // Si la contraseña no es correcta
            echo "<script>alert('Contraseña incorrecta');</script>";
        }
    } else {
        // Si no encontramos el usuario
        echo "<script>alert('Usuario no encontrado');</script>";
    }

    // Cerrar la conexión
    $conexion->close();
}
?>
