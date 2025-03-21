<?php
//editado
if (isset($_POST["Registrar"])) {
    // Incluir la conexión a la base de datos
    include 'db_connect.php';

    $nombres = $_POST["nombres"];
    $apellidos = $_POST["apellidos"];
    $fecha_nacimiento = $_POST["fecha_nacimiento"];
    $username = $_POST["username"];
    $correo = $_POST["correo"];
    $telefono = $_POST["telefono"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Verificar si las contraseñas coinciden
    $confirmar_password = $_POST["confirmar_password"];
    if ($_POST["password"] != $confirmar_password) {
        $destino = "login.php?registro.php";
        echo '<script>alert("Las contraseñas no coinciden"); window.location.href = "' . $destino . '";</script>';
        exit();
    }

    // Insertar el nuevo usuario en la tabla
    $sql = "INSERT INTO usuarios (nombres, apellidos, fecha_nacimiento, username, correo, password, telefono) 
            VALUES ('$nombres', '$apellidos', '$fecha_nacimiento', '$username', '$correo', '$password','$telefono')";

    if ($conexion->query($sql) === TRUE) {
        $destino = "login.php?login.php";
        echo '<script>alert("Registro exitoso"); window.location.href = "' . $destino . '";</script>';
        exit();
    } else {
        echo "Error al registrar el usuario: " . $conexion->error;
    }

    $conexion->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="estilos/Stylle_registro.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  

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
<style>
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

<body>
    <nav class="navbar navbar-expand-lg fixed-top shadow-lg">
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
                        <a class="nav-link" href="quienesSomos.php" style="color: #000000;">¿Quienes somos?</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="productos.php" style="color: #000000;"></i>Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="carrito.php" style="color: #000000;"><i class="fa-solid fa-user"></i>Cuenta</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <form action="registro.php" method="post" style="margin-top: 10%;">
        <h1 class="title" style="color:#1a9bb8">Regístrate!</h1>
        <label for="nombres">
            <input placeholder="Nombres" type="text" name="nombres" id="nombres" autocomplete="off" required>
        </label>
        <label for="apellidos">
            <input placeholder="apellidos" type="text" name="apellidos" id="apellidos" autocomplete="off" required>
        </label>
        <label for="fecha_nacimiento">
            <p>Fecha de nacimiento</p>
            <input placeholder="Fecha de nacimiento" type="date" name="fecha_nacimiento" id="fecha_nac" autocomplete="off" required>
        </label>
        <label for="username">
            <input placeholder="nombre de usuario" type="text" name="username" id="username1" autocomplete="off" required>
        </label>
        <label for="correo">
            <input placeholder="correo electrónico" type="text" name="correo" id="username2" autocomplete="off" required>
        </label>
        <label for="telefono">
            <input placeholder="telefono" type="text" name="telefono" id="telefono" autocomplete="off" required>
        </label>
        <label for="password">
            <input placeholder="contraseña" type="password" name="password" id="password3" autocomplete="off" required>
        </label>
        <label for="confirmar_password">
            <input placeholder="confirmar contraseña" type="password" name="confirmar_password" id="password4" autocomplete="off" required>
        </label>
        <a href="login.php" style="margin: 5px;">Regresar</a>
        <input type="submit" name="Registrar" value="Registrarme" id="enviar">
    </form>

</body>
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
</html>