<!DOCTYPE html>
<html lang="en">

<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["username"])) {
    header("location: login.php");
    exit();
}

// Importar la conexión a la base de datos
require 'db_connect.php';

// Obtener el ID del usuario actual
$usuario_id = $_SESSION["user_id"];

// Obtener los artículos en el carrito del usuario
$carrito_sql = "SELECT c.articulo_id, a.nombre, a.precio, c.cantidad FROM carritos c
                JOIN articulos a ON c.articulo_id = a.id
                WHERE c.usuario_id = $usuario_id";
$carrito_resultado = $conexion->query($carrito_sql);

if ($carrito_resultado->num_rows > 0) {
    $total_pagar = 0;
    while ($item = $carrito_resultado->fetch_assoc()) {
        $subtotal = $item['cantidad'] * $item['precio'];
        $total_pagar += $subtotal;
    }
}
$total_final = isset($_SESSION['total_final']) ? $_SESSION['total_final'] : 0;

// Cantidad de artículos en carrito
$consulta = "SELECT COUNT(*) AS total_productos, SUM(cantidad) AS cantidad_total
             FROM carritos
             WHERE usuario_id = $usuario_id";

// Ejecutar la consulta
$resultado = $conexion->query($consulta);

// Verifica si la consulta fue exitosa
if ($resultado) {
    // Obtener el resultado como un array asociativo
    $fila = $resultado->fetch_assoc();

    // Almacena el total de productos en una variable
    $cant_total_productos = $fila['cantidad_total'];

    if ($cant_total_productos == null) {
        $cant_total_productos = 0;
    }
} else {
    // Si la consulta falla, muestra un mensaje de error
    echo "Error al obtener la cantidad de productos: " . $conexion->error;
}

// Cerrar la conexión a la base de datos
$conexion->close();



    ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realizar Pago</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="estilos/Styles_pago.css">
    <link href="https://www.paypal.com/sdk/js?client-id=AdX1t765961g-kvZxbV305khPY43GrFw_XyQOSV3zjw5-UE3QwRNH51jqoVH04aE1IsJ8NzYl8sK0Z7G" rel="stylesheet"> <!-- Incluye el SDK de PayPal -->
    <style>
        .paypal-button-container {
            text-align: center;
            margin: 20px 0;
        }
    </style>
    <script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-VX18B9GBD3');
</script>
<style>
        /* Estilo para la tabla */
        .table thead th {
            background-color: #343a40;
            color: white;
            text-align: center;
        }

        .table td {
            text-align: center;
            vertical-align: middle;
        }

        /* Estilo para los botones */
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

        .btn-outline-primary {
            border-color: #3490dc;
            color: #3490dc;
        }

        .btn-outline-primary:hover {
            background-color: #3490dc;
            color: white;
        }

        /* Contenedor */
        .container-sm {
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .total-pagar {
            font-size: 1.5rem;
            font-weight: bold;
            text-align: right;
        }

        .table-responsive {
            margin-top: 20px;
        }

        /* Añadir espacio inferior */
        .mb-3 {
            margin-bottom: 30px;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            margin-top: 5%;
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

        /* Estilos responsivos */
        @media (max-width: 768px) {
            .row {
                display: flex;
                flex-direction: column;
            }

            .col-md-6 {
                width: 100%;
                margin-bottom: 20px;
            }

            .summary-table {
                margin-top: 10px;
            }
        }
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

        /* CSS */
        .custom-toggler {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3E%3Cpath stroke='black' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
        }
        .chat-container {
            width: 300px;
            height: 400px;
            position: fixed;
            bottom: 120px;
            right: 15px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            display: none;
            /* Oculta el chat al principio */
            flex-direction: column;
        }

        .chat-header {
            background-color: #e881a2;
            color: white;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .chat-header h3 {
            margin: 0;
            font-size: 16px;
        }

        .chat-header button {
            background: none;
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
        }

        .chat-box {
            flex-grow: 1;
            padding: 10px;
            overflow-y: auto;
            border-bottom: 1px solid #ddd;
            background-color: #f9f9f9;
        }

        .chat-input {
            display: flex;
            padding: 10px;
        }

        #user-input {
            flex-grow: 1;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-right: 5px;
        }

        button {
            background-color: #e881a2;
            color: white;
            border: none;
            cursor: pointer;
            padding: 5px ;
            border-radius: 5px;
        }



        .bot-text,
        .user-text {
            margin: 10px 0;
        }

        .bot-text {
            color: #03a9f4
        }

        .user-text {
            color: blue;
            text-align: right;
        }

        /* Icono del chatbot que aparece en la esquina */
        .chat-icon {
            position: fixed;
            bottom: 140px;
            right: 35px;
            background-color: #e881a2;
            color: white;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body style=" background-color: #70c6cc;">
    <nav class="navbar navbar-expand-lg fixed-top shadow-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="img/NutriCode_logo_sin_fondo.png" width="100" height="90" alt="Logo Dargel Repsoteria">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php" style="color: #000000;">
                            Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="servicios.php" style="color: #000000;">Servicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="quienesSomos.php" style="color: #000000;">¿Quienes somos?</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="carrito.php" style="color: #000000;">
                            <?php echo $_SESSION["username"]; ?> &nbsp
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                            </svg>(<?php echo $cant_total_productos; ?>)
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container" style="color:#e8306d; text-align: center;">
    <h1>Realizar Pago</h1>
    <p>Total a pagar: $ <?php echo number_format($total_final, 2); ?></p>
    <form action="createpayment.php" method="post" style="width: 30%; margin: 0 auto;">
    <input type="hidden" name="amount" value="">
    <input type="hidden" name="currency_code" value="USD">
    
    <div class="form-group">
        <label for="address" style="color:#000000">Dirección del Pedido:</label>
        <input type="text" id="address" name="address" class="form-control" placeholder="Ingresa tu dirección" required>
    </div>
<br><br>
    <div class="form-group">
        <button type="submit" class="paypal-button">Pagar con PayPal</button>
    </div>
</form>

</div>

<script src="//code.tidio.co/aookr9g7m2owen4eytsaebmypimtsi9k.js" async></script>

<div class="chat-icon" id="chat-icon" onclick="toggleChat()">
        💬
    </div>

    <div class="chat-container" id="chat-container">
        <div class="chat-header">
            <h3>Chat Dargel</h3>
            <button onclick="toggleChat()">✖</button>
        </div>
        <div class="chat-box" id="chat-box">
            <p class="bot-text">¡Hola! Soy el chatbot de Dargel, ¿en qué puedo ayudarte?</p>
        </div>
        <div class="chat-input">
            <input type="text" id="user-input" placeholder="Escribe tu pregunta aquí..." />
            <button onclick="sendMessage()">Enviar</button>
        </div>
    </div>
<script>
                    function toggleChat() {
                        const chatContainer = document.getElementById('chat-container');
                        const chatIcon = document.getElementById('chat-icon');

                        if (chatContainer.style.display === 'none' || chatContainer.style.display === '') {
                            chatContainer.style.display = 'flex';
                            chatIcon.style.display = 'none'; // Oculta el ícono cuando el chat está abierto
                        } else {
                            chatContainer.style.display = 'none';
                            chatIcon.style.display = 'flex'; // Muestra el ícono cuando el chat está cerrado
                        }
                    }

                    function sendMessage() {
                        const userInput = document.getElementById("user-input").value;
                        const chatBox = document.getElementById("chat-box");

                        // Muestra el mensaje del usuario
                        const userMessage = document.createElement("p");
                        userMessage.classList.add("user-text");
                        userMessage.textContent = userInput;
                        chatBox.appendChild(userMessage);

                        // Limpiar el campo de entrada
                        document.getElementById("user-input").value = '';

                        // Respuestas automáticas del chatbot
                        const botResponse = document.createElement("p");
                        botResponse.classList.add("bot-text");

                        if (userInput.toLowerCase().includes("productos") || userInput.toLowerCase().includes("venden") || userInput.toLowerCase().includes("que")) {
                            botResponse.textContent = "Ofrecemos pasteles con diferentes temáticas y sabores!";
                        } else if (userInput.toLowerCase().includes("horario") || userInput.toLowerCase().includes("atienden") || userInput.toLowerCase().includes("cuando")) {
                            botResponse.textContent = "Nuestro horario es de lunes a viernes de 9 a.m. a 6 p.m.";
                        } else if (userInput.toLowerCase().includes("ubicación") || userInput.toLowerCase().includes("ubican") || userInput.toLowerCase().includes("donde")) {
                            botResponse.textContent = "Nos ubicamos en Av. Las Palmers, Los olivos Lima Perú";
                        }else if (userInput.toLowerCase().includes("precios") ) {
                                botResponse.textContent = "Nuestros precios varian según el pedido.";
                        }else if (userInput.toLowerCase().includes("envio") || userInput.toLowerCase().includes("entrega") || userInput.toLowerCase().includes("donde")) {
                                    botResponse.textContent = "La entrega del pedido demoran un minimo de 3 dias y según el pedido";
                        } else {
                            botResponse.textContent = "Lo siento, no entiendo tu pregunta. Puedes usar el chat en vivo";
                        }

                        chatBox.appendChild(botResponse);
                        chatBox.scrollTop = chatBox.scrollHeight; // Scroll al final del chat
                    }
                </script>
</body>


</html>
