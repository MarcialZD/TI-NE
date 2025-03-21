<?php
//editado
include 'db_connect.php';  // Incluir el archivo para la conexión a la base de datos

$file = 'contador.txt';  // Ubicación del archivo donde se almacenará el conteo

if (!file_exists($file)) {
    $visitas = 0;  // Inicializa el contador en 0 si el archivo no existe
} else {
    $visitas = (int) file_get_contents($file);  // Lee el contador del archivo
}

$visitas++;  // Incrementa el contador

file_put_contents($file, $visitas);  // Guarda el nuevo valor del contador en el archivo








session_start();
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

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="estilos/Style_servicios.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
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
            background-color:#faeae5;
            color: #03a9f4;
        }
     
        footer {
            background-color:#faeae5;
           
            color: #e8306d;
            padding: 20px 0;
        }

        footer a {
            color:#e8306d;
            
            
            text-decoration: none;
        }

        footer img {
            width: 30px;
            height: 30px;
        }

        .card-custom {
            display: flex;
            width: 90%;
            margin: 30px auto;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 15px;
            align-items: center;
            flex-direction: column;
        }

        .card-custom img {
            object-fit: cover;
            width: 100%;
            height: 100%;
            transition: transform 0.3s ease-in-out;
        }

        .card-custom img:hover {
            transform: scale(1.05);
        }

        .card-body {
            padding: 20px;
            align-items: center;
        }
        .card-body p {
            margin-top: 30px;
        }
        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .responsive-img {
            max-height: 200px;
        }

        h1.text-center {
            font-weight: bold;
            font-size: 3rem;
            color: #333;
            text-transform: uppercase;
            padding: 20px 15px;
            margin-bottom: 30px;
        }

        .btn {
            background-color: #e8306d;
            color: #fff;
            padding: 12px 25px;
            border-radius: 50px;
            text-align: center;
            font-weight: bold;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #c12c59;
        }

        .navbar-nav {
            font-weight: 500;
        }

        /* Animaciones */
        .card-custom {
            transition: all 0.3s ease-in-out;
        }

        .card-custom:hover {
            transform: scale(1.05);
        }
        .card-custom {
    display: flex;
    justify-content: center; /* Centra los elementos horizontalmente */
    align-items: center;     /* Centra los elementos verticalmente */
    flex-direction: column;  /* Coloca los elementos en columna (en caso de que haya varios elementos) */
    height: 100%;             /* Asegura que el contenedor tenga una altura para centrar el contenido */
}




    </style>

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light ">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="img/NutriCode_logo_sin_fondo.png" width="100" height="90" alt="Logo">
            </a>
            <button class="navbar-toggler custom-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav" style="color: #0e9edf;">
                <ul class="navbar-nav ms-auto" style="color: #0e9edf;">
                    <li class="nav-item">
                        <a class="nav-link" href="blog.php" style="color: #f10f5a;">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="servicios.php" style="color: #f10f5a">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="productos.php" style="color: #f10f5a">Pasteles</a>
                    </li>
                    <?php
                    if (!isset($_SESSION["username"])) {
                        echo '<li class="nav-item"><a class="nav-link" href="carrito.php" style="color: #f10f5a"><i class="fa-solid fa-user active"></i> Cuenta</a></li>';
                    } else {
                        echo '<li class="nav-item"><a class="nav-link" href="carrito.php" style="color: #f10f5a;">' . $_SESSION["username"] . ' <i class="fa-solid fa-cart-shopping"></i>(' . $cant_total_productos . ')</a></li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5 pt-4">
        <h1 class="text-center" data-aos="fade-up">Nuestros Servicios</h1>

        <div class="card card-custom" data-aos="fade-up">
            <div class="row g-0">
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title">Servicio de Entrega</h5>
                        <p>Entregamos tus pasteles en la comodidad de tu hogar, asegurando frescura y calidad.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <img src="images/entrega_pastel.jpg" alt="Entrega de pastel" class="img-fluid responsive-img">
                </div>
            </div>
        </div>

        <div class="card card-custom" data-aos="fade-up">
            <div class="row g-0">
                <div class="col-md-4">
                    <img src="images/metodo_pago.jpg" alt="Método de pago" class="img-fluid responsive-img">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title">Método de Pago</h5>
                        <p>Aceptamos pagos únicamente a través de PayPal para una transacción segura.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-custom" data-aos="fade-up">
            <div class="row g-0">
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title">Personalización de Pasteles</h5>
                        <p>Ofrecemos la opción de personalizar pasteles para eventos especiales, incluyendo mensajes y decoraciones a medida.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <img src="images/personalizacion_pastel.jpg" alt="Personalización de Pasteles" class="img-fluid responsive-img">
                </div>
            </div>
        </div>

        <div class="card card-custom" data-aos="fade-up">
            <div class="row g-0">
                <div class="col-md-4">
                    <img src="images/chatbot.jpg" alt="Chatbot" class="img-fluid responsive-img">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title">Chatbot</h5>
                        <p>Nuestro chatbot está disponible 24/7 para responder tus preguntas frecuentes.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="py-4">
    <div class="container">
        <div class="row text-center text-md-start">
            <div class="col-md-4 mb-3">
                <h5>Contacto</h5>
                <p><i class="fa-solid fa-envelope"></i> Correo: <a href="mailto:dargel@dargelreposteria.com">dargel@dargelreposteria.com</a></p>
            </div>
            <div class="col-md-4 mb-3">
                <h5>Redes Sociales</h5>
                <p><a href="https://www.facebook.com/profile.php?id=100063723304943" target="_blank"><i class="fa-brands fa-facebook"></i> Facebook</a></p>
                <p><a href="https://instagram.com" target="_blank"><i class="fa-brands fa-instagram"></i> Instagram</a></p>
                <p><a href="https://tiktok.com" target="_blank"><i class="fa-brands fa-tiktok"></i> TikTok</a></p> <!-- Añadido TikTok -->
            </div>
            <div class="col-md-4 mb-3">
                <h5>Información</h5>
                <p>Somos una empresa dedicada a ofrecer los mejores pasteles personalizados para cada ocasión.</p>
            </div>
        </div>
    </div>
    <div class="text-center">
        <p>&copy; 2024 Reposteria Dargel</p>
    </div>
</footer>


    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>

</html>
