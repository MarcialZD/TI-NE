<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="estilos/style_inicio.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    

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
        body {
            
            text-align: center;
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
    <nav class="navbar navbar-expand-lg  fixed-top shadow-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="img/NutriCode_logo_sin_fondo.png" width="100" height="90" alt="">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon custom-toggler "></span>
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


    <form action="loginx.php" method="post">
        <h1 class="title"  style="color:#1a9bb8">LOGIN</h1>
        <label for="">
            <i class="fa-solid fa-user"></i>
            <input placeholder="nombre de usuario" type="text" name="username" id="username" autocomplete="off" required>

        </label>
        <label for="">
            <i class="fa-solid fa-lock"></i>
            <input placeholder="contraseña" type="password" name="password" id="password" autocomplete="off">

        </label>

        <a href="registro.php" class="link">No estoy registrado</a>




        <input type="submit" name="enviar" value="Enviar" id="enviar">

    </form>




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

</body>


</html>