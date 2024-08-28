
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Inicio</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <script src="js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="estilos/Style_inicio.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>

    <body style="body {

              min-height: 100vh;
              background-image:  url('https://i.gifer.com/BSAJ.gif');
              background-size: cover;
              background-repeat: no-repeat;
          }">
        <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top shadow-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="Principal.php">
                    <img src="img/NutriCode_logo_sin_fondo.png" width="30" height="30" alt="">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="Principal.php" style="color: #000000;">
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
            <h1 class="title">LOGIN</h1>
            <label for="">
                <i class="fa-solid fa-user"></i>
                <input placeholder="nombre de usuario" type="text" name="username" id="username" autocomplete="off" required>

            </label>
            <label for="">
                <i class="fa-solid fa-lock"></i>
                <input placeholder="contraseña" type="password" name="password" id="password" autocomplete="off" >

            </label>

            <a href="registro.php" class="link">No estoy registrado</a>




            <input type="submit" name="enviar" value="Enviar" id="enviar">

        </form>





    </body>

</html>
