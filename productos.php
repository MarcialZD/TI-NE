<?php
require 'db_connect.php';


// Obtener todos los artículos de la tabla 'articulos'
$articulos_sql = "
    SELECT 
        id,
        nombre,
        precio,stock,imagen,
        porcentaje_descuento,
        fecha_maxima_descuento,
        CASE 
            WHEN fecha_maxima_descuento < CURDATE() THEN precio
            ELSE precio - (precio * porcentaje_descuento / 100)
        END AS precio_final,
        CASE 
            WHEN fecha_maxima_descuento >= CURDATE() THEN porcentaje_descuento
            ELSE 0
        END AS descuento
    FROM articulos
";

$articulos_resultado = $conexion->query($articulos_sql);






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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Artículo</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="estilos/Style_agregararticulo.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilos/Style_servicios.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


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
        .card-img-top {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }




        nav {
            background-color: #faeae5;
            text-align: center;
        }




        .card {
            background-color: #faeae5;


        }

        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .btn {
            background-color: #e881a2;
            color: #faeae5;
        }

        .btn:hover {
            background-color: #1a9bb8;
            color: #faeae5;

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

        footer {
            background-color: #faeae5;

            color: #e8306d;
            padding: 20px 0;
        }

        footer a {
            color: #e8306d;


            text-decoration: none;
        }

        footer img {
            width: 30px;
            height: 30px;
        }
    </style>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-VX18B9GBD3');
    </script>
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






    <div class="container">
        <div class="row">
            <?php
            while ($articulo = $articulos_resultado->fetch_assoc()) {
                echo "<div class='col-md-4 mb-4'>";
                echo "<div class='card fade-in'>";
                echo "<img src='{$articulo['imagen']}' class='card-img-top' alt='{$articulo['nombre']}'>";
                echo "<div class='card-body'>";
                echo "<h5 class='card-title'>{$articulo['nombre']}</h5>";
                echo "<p class='card-text'>Stock: {$articulo['stock']} unidades</p>";

                // Mostrar el precio y el descuento si aplica
                if ($articulo['descuento'] > 0) {
                    echo "<p class='card-text'>Precio original: <del>{$articulo['precio']} USD</del></p>";
                    echo "<p class='card-text'>Descuento: {$articulo['descuento']}%</p>";
                    echo "<p class='card-text'>Precio final: $ " . round($articulo['precio_final'], 2) . " </p>";
                } else {
                    echo "<p class='card-text'>Precio: $ {$articulo['precio']} </p>";
                }

                // Formulario para agregar al carrito
                echo "<form action='agregararticulo.php' method='post'>";
                echo "<input type='hidden' name='articulo_id' value='{$articulo['id']}'>";
                echo "<div class='mb-3'>";
                echo "<label for='cantidad{$articulo['id']}' class='form-label'>Cantidad:</label>";
                echo "<input type='number' name='cantidad' id='cantidad{$articulo['id']}' value='1' min='1' class='form-control' required autocomplete='off'>";
                echo "</div>";
                // Botón para ver el producto
                echo "<button type='submit' class='btn'>Agregar al Carrito</button> <br>";
                echo "<a href='vistaProducto.php?id={$articulo['id']}' class='btn mt-2'>Ver Producto</a>";
                echo "</form>";

                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
            ?>
        </div>
    </div>

</body>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const serviceCards = document.querySelectorAll('.fade-in');
        serviceCards.forEach((card, index) => {
            setTimeout(() => {
                card.classList.add('visible');
            }, index * 200); // Ajusta el tiempo según sea necesario
        });
    });
</script>
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

</html>