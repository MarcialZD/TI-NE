

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Calculadora de Metabolismo Basal</title>
        <script src="js/bootstrap.bundle.min.js"></script>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="estilos/style_calculadora.css">
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
        </nav><div class="container">
            <h1>Calculadora de Metabolismo Basal</h1>
            <form action="calculadora.php" method="post">
                <label for="peso">Peso (kg):</label>
                <input type="text" name="peso" required  autocomplete="off"><br>

                <label for="altura">Altura (cm):</label>
                <input type="text" name="altura" required  autocomplete="off"><br>

                <label for="edad">Edad:</label>
                <input type="text" name="edad" required  autocomplete="off"><br>

                <label for="genero">Género:</label>
                <select name="genero" required  autocomplete="off">
                    <option value="masculino">Masculino</option>
                    <option value="femenino">Femenino</option>
                </select><br>

                <input type="submit" name="calcular" value="Calcular BMR">
            </form>
            <?php
// Incluye la clase MetabolismoBasalCalculator si no se ha incluido previamente
            include_once 'MetebolismoBasal.php';

            if (isset($_POST["calcular"]))
            {
                // Crea una instancia de la clase MetabolismoBasalCalculator
                $calculator = new MetabolismoBasalCalculator($_POST["peso"], $_POST["altura"], $_POST["edad"], $_POST["genero"]);

                // Llama al método calcularBMR()
                $bmr = $calculator->calcularBMR();

                echo "<p>Tu Metabolismo Basal (BMR) es: " . number_format($bmr, 2) . " kcal/día</p>";
            }
            ?>


            <a href="javascript:history.back()">Regresar a la página anterior</a>
            <a href="carrito.php" class="btn">Ver a Carrito</a>
        </div>
    </body>
</html>
