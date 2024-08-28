<?php
$services = array(
    array("Consulta Nutricional Personalizada", "Consulta Nutricional Personalizada para alcanzar tus objetivos específicos"),
    array("Planes de Comida Personalizados", "Planes de Comida Personalizados con el fin de contribuir a una dieta acorde a tus necesidades"),
    array("Evaluación del Índice de Masa Corporal (IMC)", "Evaluación del Índice de Masa Corporal (IMC) para saber cuantas calorías debes consumir al día"),
    array("Blog de Nutrición", "Blog de Nutrición para las últimas novedades"),
    array("Clases y Talleres Virtuales", "Clases y Talleres Virtuales para la formación constante"),
    array("Tienda Online", "Tienda Online con los mejores productos saludables")
);

$file = 'contador.txt';  // Ubicación del archivo donde se almacenará el conteo

if (!file_exists($file)) {
    $visitas = 0;  // Inicializa el contador en 0 si el archivo no existe
} else {
    $visitas = (int) file_get_contents($file);  // Lee el contador del archivo
}

$visitas++;  // Incrementa el contador

file_put_contents($file, $visitas);  // Guarda el nuevo valor del contador en el archivo
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quienes Somos</title>
    <!-- Tus enlaces a CSS y JavaScript -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="estilos/Style_servicios.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top shadow-lg">
      <nav class="navbar navbar-expand-lg navbar bg-light fixed-top shadow-lg">
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
        </nav>
    </nav>
    <br><br><br>
    <center><h1>Servicios de Nutrición</h1></center>
    <div class="container-fluid">
        <div class="container-fluid" style="text-align: center;">
            <form action="calculadora.php" method="get" style="display: inline-block;">
                <p>Prueba Nuestra calculadora de Índice de Masa Corporal. ¡Es gratis!</p>
                <button type="submit" class="btn btn-dark">CALCULADORA</button>
            </form>
            <div style="margin-top: 20px;">
                <img src="img/portada servicios.jpg" alt="" width="1560" height="1110"/>
            </div>
            <table class="table" style="margin-top: 20px;">
                <thead>
                    <tr>
                        <th>Servicio</th>
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $service): ?>
                        <tr>
                            <td><?php echo $service[0]; ?></td>
                            <td><?php echo $service[1]; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <br><br><br>
    <footer style="background-color: #333; color: #fff; text-align: center; padding: 20px;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h4>Contacto</h4>
                    <p>Correo electrónico: NutriCode@gmail.com</p>
                    <p>Teléfono: +51-456-7890</p>
                    <p>Esta página ha sido visitada <?php echo $visitas; ?> veces.</p>
                    <p>&copy Derechos reservados NutriCode</p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>