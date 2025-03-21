<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
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


        form {
    display: flex;
    flex-direction: column;
    background-color: #faeae5;
    padding: 20px 5%; 
    box-shadow: 0px 5px 10px rgba(255, 255, 255, 0.7);
    width: 30%; 
    margin-left: 35%;
    margin-top: 5%;
  
}

form .title {
    color: #020202;
    font-size: 2rem; 
    font-weight: 800;
    margin-bottom: 30px;
}

form label {
    display: flex; 
    align-items: center; 
    margin-bottom: 25px; 
}

form label .fa-solid {
    font-size: 1.5rem; 
    color: rgb(7, 7, 7);
    margin-right: 10px; 
}

form label input {
    outline: none;
    border: none;
    color: #070707;
    border-bottom: solid 1px rgb(255, 255, 255);
    padding: 5px; 
    font-size: 1.2rem; 
    flex: 1; 
}

form label input::placeholder {
    color: rgba(37, 37, 37, 0.5);
}

form .link {
    color: rgba(37, 37, 37, 0.5);
    margin-bottom: 15px;
    font-size: 1rem; 
}

form #enviar {
    border: none;
    padding: 10px 15px; 
    cursor: pointer;
    font-size: 1.2rem;
    background-color: #e881a2;
    color: #faeae5;
}
footer {
            background-color: #faeae5;
            margin-top: 4%;
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
   
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-light navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="img/NutriCode_logo_sin_fondo.png" width="100" height="90" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
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







</body>
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

</html>