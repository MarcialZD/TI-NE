<!DOCTYPE html>
<?php
    $file = 'contador.txt';  // Ubicación del archivo donde se almacenará el conteo

    if (!file_exists($file)) {
        $visitas = 0;  // Inicializa el contador en 0 si el archivo no existe
    } else {
        $visitas = (int)file_get_contents($file);  // Lee el contador del archivo
    }

    $visitas++;  // Incrementa el contador

    file_put_contents($file, $visitas);  // Guarda el nuevo valor del contador en el archivo

    echo $visitas;  // Imprime el valor del contador
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Quienes Somos</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <script src="js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="estilos/Style_quienessomos.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>
    <body style="background: linear-gradient(to bottom right, #F8F8FF, #01d063);">
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
        </nav>     <div class="container-md" style="margin-top: 70px;">
            
            <div class="shadow">
                <h1 class="text-center display-6 fst-estiloTitulo fw-bolder"> Quienes Somos </h1>
            </div>

            <div class="row align-items-md-stretch">
                <div class="col-md-3">
                    <div class="h-100 p-5 text-dark rounded-3" style="background-color: #98FB98; border: 2px solid #258a60">
                        <center><h2> Quienes Somos </h2></center>
                        <p style="text-align: justify;">
                            En NutriCode, nuestra pasión es nutrir vidas y fomentar un estilo de vida saludable. Desde nuestros modestos inicios, hemos crecido para convertirnos en una empresa líder en la industria de la nutrición, comprometida con la entrega de productos de alta calidad que enriquecen la vida de las personas.
                        </p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="h-100 p-5 bg-light border rounded-3">
                        <center><h3> Nuestra Misión </h3></center>
                        <p style="text-align: justify;">
                            Nuestra misión es simple pero poderosa: mejorar la salud y el bienestar de las personas a través de una alimentación nutritiva. Creemos que la nutrición adecuada es la base para una vida plena y activa, y nos esforzamos por brindar soluciones que hagan que la nutrición sea accesible y deliciosa para todos.
                        </p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="h-100 p-5 text-dark rounded-3" style="background-color: #98FB98; border: 2px solid #258a60">
                        <center><h3>Nuestro deber con la Calidad</h3></center>
                        <p style="text-align: justify;">
                            En NutriCode, la calidad es nuestra prioridad. Nos asociamos con proveedores que comparten nuestra pasión por la calidad y la sostenibilidad. Cada producto que ofrecemos es seleccionado y probado con medida para garantizar su frescura y valor nutricional. Nos enorgullece decir que nuestros estándares de calidad son insuperables.
                        </p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="h-100 p-5 bg-light border rounded-3">
                        <center><h3> Pacto con el Bienestar Ambiental</h3></center>
                        <p style="text-align: justify;">
                            Estamos comprometidos con el bienestar de las generaciones futuras y la salud de nuestro planeta. Trabajamos de manera constante para minimizar nuestro impacto ambiental y promover prácticas sostenibles en toda nuestra cadena de suministro.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <hr>
            
        <footer style="background-color: #333; color: #fff; text-align: center; padding: 20px;">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <h4>Contacto</h4>
                        <p>Correo electrónico: NutriCode@gmail.com</p>
                        <p>Teléfono: +51-456-7890</p>
                        <p>Esta página ha sido visitada <?php echo $visitas; ?> veces.</p>
                        <p>&copy Derechos reservados Nutricode</p>
                        
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>