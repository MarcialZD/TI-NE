<!DOCTYPE html>
<?php
   
?>
<html>
<head>
        
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Inicio</title>
        <!-- CSS only -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <!-- JavaScript Bundle with Popper -->
        <script src="js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="estilos/Style_principal.css">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    </head> 
    <body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top shadow-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="Principal.php">
                    <img src="img/NutriCode_logo_sin_fondo.png" width="30" height="30" alt="">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse navbar-custom" id="navbarNav">
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
                        <?php
                        session_start();

                        if (!isset($_SESSION["username"]))
                        {
                            ?>  <li class="nav-item">
                                <a class="nav-link" href="carrito.php" style="color: #000000;"><i class="fa-solid fa-user"></i>Cuenta</a>
                            </li><?php
                    }
                    else
                    {
                        if (!isset($_SESSION["username"]))
                        {
                            header("location: login.php");
                            exit();
                        }

// Conectar a la base de datos (reemplaza 'usuario', 'contraseña' y 'nombre_base_de_datos' con tus propios valores)
                        $conexion = new mysqli("localhost", "root", "123456", "nutricode");

                        if ($conexion->connect_error)
                        {
                            die("La conexión a la base de datos falló: " . $conexion->connect_error);
                        }

// Obtener el ID del usuario actual
                        $usuario_id = $_SESSION["user_id"];
// cant articulos en carrito:
                        $consulta = "SELECT COUNT(*) AS total_productos, SUM(cantidad) AS cantidad_total
             FROM carritos
             WHERE usuario_id = $usuario_id";

// Ejecuta la consulta
                        $resultado = $conexion->query($consulta);
// Verifica si la consulta fue exitosa
                        if ($resultado)
                        {
                            // Obtiene el resultado como un array asociativo
                            $fila = $resultado->fetch_assoc();

                            // Almacena el total de productos en una variable
                            $cant_total_productos = $fila['cantidad_total'];

                            if ($cant_total_productos == null)
                            {
                                $cant_total_productos = 0;
                            }
                            // Cierra la conexión a la base de datos
                            $conexion->close();
                        }
                        else
                        {
                            // Si la consulta falla, muestra un mensaje de error
                        }
                            ?> 
                            <li class="nav-item">

                                <a class="nav-link" href="carrito.php" style="color: #000000;"> <?php echo $_SESSION["username"]; ?> &nbsp <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                                    <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                                    </svg>(<?php echo $cant_total_productos; ?>)</a>
                            </li><?php
                    }
                        ?>


                    </ul>
                </div>
            </div>
        </nav>
        <br><br><br>
        <div>
            <div class="container-fluid">
        <!-- Carrusel de imágenes -->
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" data-interval="1000">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img width="1200" height="800" src="img/img1.jpg" class="d-block w-100" alt="Imagen 1">
        </div>
        <div class="carousel-item">
            <img width="1200" height="800" src="img/img2.jpg" class="d-block w-100" alt="Imagen 2">
        </div>
        <div class="carousel-item">
            <img width="1200" height="800" src="img/img3.jpg" class="d-block w-100" alt="Imagen 3">
        </div>
    </div>
    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
        <br><br>
        


        <!-- Banner -->
        <center><img src="img/img4.jpg" class="img-fluid" alt="Banner"></center>
        <br><br>

        <!-- Tabla -->
        <table class="table table-bordered text-center">
            <tr>
                <td><img src="img/img5.jpg" class="img-fluid" alt=""></td>
                <td><img src="img/img6.jpg" class="img-fluid" alt=""></td>
                <td><img src="img/img7.jpg" class="img-fluid" alt=""></td>
            </tr>
            <tr>
                <td><img src="img/img8.jpg" class="img-fluid" alt=""></td>
                <td><img src="img/img9.jpg" class="img-fluid" alt=""></td>
                <td><img src="img/img10.jpg" class="img-fluid" alt=""></td>
            </tr>
        </table>
        <br><br><br>

        <!-- Cuadro con texto y espacio para video -->
        <table class="table table-bordered text-center">
            <tr>
                <td><img src="img/img10.jpg" class="img-fluid" alt=""></td>
                <td><p><h1>NUTRICODE NUTRITION</h1></p><br>Comunidad y liderazgo. Este es el corazón y el alma de NUTRICODE Nutrition. Se trata de personas con corazón, motivando a comunidades enteras a ser más saludables a través de una buena nutrición y un estilo de vida activo.</td>
                <td><img src="img/img10.jpg" class="img-fluid" alt=""></td>
            </tr>
        </table>
    </div>

    <!-- Scripts necesarios para Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        </div>
        
       
<p class="text-center"> &copy; Derechos reservados, Universidad de Ciencias y Humanidades</p>
            
        <footer style="background-color: #333; color: #fff; text-align: center; padding: 20px;">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <h4>Contacto</h4>
                        <p>Correo electrónico: NutriCode@gmail.com</p>
                        <p>Teléfono: +51-456-7890</p>
                       
                        <p>&copy Derechos reservados NutriCode</p>
                        
                    </div>
                </div>
            </div>
        </footer>


    </body>

</html>


        