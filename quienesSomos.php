<!DOCTYPE html>
<?php
session_start();

$file = 'contador.txt';

if (!file_exists($file)) {
    $visitas = 0;
} else {
    $visitas = (int)file_get_contents($file);
}

$visitas++;
file_put_contents($file, $visitas);

$cant_total_productos = 0;
if (isset($_SESSION["username"])) {
    include 'db_connect.php';

    $usuario_id = $_SESSION["user_id"];

    $consulta = "SELECT COUNT(*) AS total_productos, SUM(cantidad) AS cantidad_total
                 FROM carritos
                 WHERE usuario_id = $usuario_id";

    $resultado = $conexion->query($consulta);

    if ($resultado) {
        $fila = $resultado->fetch_assoc();
        $cant_total_productos = $fila['cantidad_total'] ?? 0;
    }

    $conexion->close();
}
?>

<html>

<head>
    <meta charset="UTF-8">
    <title>Repostería Dargel</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="estilos/Style_quienessomos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .fade-in {
            opacity: 0;
            transform: translateX(-30px);
            transition: opacity 2s ease, transform 2s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .fade-out {
            opacity: 0;
            transform: translateX(-30px);
        }

        .info-box {
            margin: 20px 0;
        }

        .column {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
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

        .card {
            background-color: #faeae5;


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
            padding: 5px;
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
    <script>
        const showBoxes = () => {
            const boxes = document.querySelectorAll('.info-box');
            const triggerBottom = window.innerHeight / 5 * 4;

            boxes.forEach((box, index) => {
                const boxTop = box.getBoundingClientRect().top;

                if (boxTop < triggerBottom) {
                    setTimeout(() => {
                        box.classList.add('visible');
                        box.classList.remove('fade-out');
                    }, index * 300);
                } else {
                    box.classList.remove('visible');
                    box.classList.add('fade-out'); // Agrega la clase de salida
                }
            });
        };

        window.addEventListener('scroll', showBoxes);
        document.addEventListener('DOMContentLoaded', showBoxes);
    </script>
</head>

<body style="background-color: #70c6cc; ">
    <nav class="navbar navbar-expand-lg  fixed-top shadow-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="img/NutriCode_logo_sin_fondo.png" width="100" height="90" alt="">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon custom-toggler"></span>
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
                        <a class="nav-link" href="quienesSomos.php" style="color: #000000;">¿Quiénes somos?</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="productos.php" style="color: #000000;">Productos</a>
                    </li>
                    <?php
                    if (!isset($_SESSION["username"])) {
                        echo '<li class="nav-item"><a class="nav-link" href="carrito.php" style="color: #000000;"><i class="fa-solid fa-user"></i> Cuenta</a></li>';
                    } else {
                        echo '<li class="nav-item"><a class="nav-link" href="carrito.php" style="color: #000000;">' . $_SESSION["username"] . ' <i class="bi bi-cart"></i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"></path>
                            </svg>(' . $cant_total_productos . ')</a></li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>
    <br><br><br>
    <div class="container-md" style="margin-top: 70px; ">
        <div class="shadow" style="background-color: #faeae5">
            <h1 class="text-center display-6 fst-estiloTitulo fw-bolder"> Repostería Dargel </h1>
        </div>

        <div class="column">
            <div class="info-box fade-in card">
                <div class="h-100 p-5 text-dark rounded-3">
                    <center>
                        <h2> ¿Quiénes Somos? </h2>
                    </center>
                    <p style="text-align: justify;">
                        En Repostería Dargel, nuestra pasión es crear dulces momentos. Con años de experiencia, nos dedicamos a ofrecer productos de repostería de alta calidad que endulzan la vida de nuestros clientes.
                    </p>
                </div>
            </div>

            <div class="info-box fade-in card">
                <div class="h-100 p-5 bg-light border rounded-3">
                    <center>
                        <h3> Nuestra Misión </h3>
                    </center>
                    <p style="text-align: justify;">
                        Nuestra misión es proporcionar productos de repostería deliciosos y frescos. Creemos que cada bocado debe ser una experiencia inolvidable.
                    </p>
                </div>
            </div>
            <div class="info-box fade-in card">
                <div class="h-100 p-5 bg-light border rounded-3">
                    <center>
                        <h3> Nuestra Visión </h3>
                    </center>
                    <p style="text-align: justify;">
                        En Repostería Dargel, nuestra visión es ser la opción preferida en el mundo de la repostería, reconocidos por nuestra innovación, calidad y compromiso con la satisfacción del cliente. Aspiramos a expandir nuestra presencia y seguir creando experiencias únicas a través de nuestros productos.
                    </p>
                </div>
            </div>

            <div class="info-box fade-in card">
                <div class="h-100 p-5 text-dark rounded-3">
                    <center>
                        <h3> Compromiso con la Calidad </h3>
                    </center>
                    <p style="text-align: justify;">
                        En Dargel, la calidad es primordial. Utilizamos ingredientes frescos y naturales, asegurando que cada producto cumpla con nuestros altos estándares.
                    </p>
                </div>
            </div>

            <div class="info-box fade-in card">
                <div class="h-100 p-5 bg-light border rounded-3">
                    <center>
                        <h3> Sostenibilidad </h3>
                    </center>
                    <p style="text-align: justify;">
                        Estamos comprometidos con prácticas sostenibles y el bienestar del medio ambiente. Cada producto se elabora con un enfoque en la sostenibilidad.
                    </p>
                </div>
            </div>
        </div>
    </div>
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
        <script src="//code.tidio.co/aookr9g7m2owen4eytsaebmypimtsi9k.js" async></script>

    </div>
    <footer style="background-color: #1a9bb8; color: #ffffff; text-align: center; ">
                <div class="container-">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <h4 style="color: #ffffff;">Contacto</h4>
                            <p style="color: #ffffff;">Correo electrónico: dargel@gmail.com</p>
                            <p style="color: #ffffff;">Teléfono: +51 984-153-862</p>

                            <p style="color: #ffffff;">&copy Derechos reservados Dargel</p>
                            <p>
                            <p>
                                <a href="https://www.facebook.com/profile.php?id=100063723304943" target="_blank" style="color: #ffffff;">
                                   
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/Facebook_f_logo_%282019%29.svg" alt="Facebook" style="width: 30px; height: 30px;" />  Dargel Repostería
                                </a>
                            </p>

                            </p>
                        </div>
                    </div>
                </div>
            </footer>
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