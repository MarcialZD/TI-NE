<!DOCTYPE html>

<?php
include './MiLib.php';
session_start();
if (!isset($_SESSION["articulo"]))
{
    $_SESSION["articulo"] = 0;
}
if (!isset($_SESSION["username"]))
{
    header("location:inicio.php");
    return;
}



$totalPagar = 0.00;
$precio = "";
$cantidad = "";

$articuloSeleccionado = $_SESSION["articulo"];
if (isset($_POST["enviar"]))
{
    $cantidad = $_POST["cantidad"];

    $articulo = indexArticulo($_POST["articulo"]);
    if ($articulo == "Seleccionar artículo")
    {
        header("location:agregararticulo.php");
    }
    else
    {
        $rec["articulo"] = $articulo;
        $rec["precio"] = indexPrecio($_POST["articulo"]);
        $rec["cantidad"] = $_POST["cantidad"];
        $rec ["subtotal"] = $rec["precio"] * $cantidad;
        $_SESSION["carrito"][] = $rec;
        header("location:carrito.php");
        return;
    }
}


$nombre = "{$_SESSION["username"]}";

?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Inicio de sesión</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <script src="js/bootstrap.bundle.min.js"></script>

        <link rel="stylesheet" href="estilos/Style_agregararticulo.css">
    </head>

    <body style="font-family: Tahoma">
        <nav class="navbar navbar-expand-lg navbar bg-light fixed-top shadow-lg ">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">
                    <img src="img/NutriCode_logo_sin_fondo.png" width="30" height="30" alt="">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="index.php">
                                Inicio
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="productos.php">Productos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="servicios.php">Servicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="somos.php">¿Quienes
                                somos?</a>
                        </li>
                    </ul>
                </div>


            </div>

        </nav>
        <h2>Usuario: <?php echo ($nombre); ?></h2>
        <div class="container container-fluid">

            <div class="product">
                <img src="img/p1.jpg" width="100" alt="Producto 1">
                <h3><?php echo (indexArticulo(1)); ?></h3>
                <p>Disponible</p>
                <p class="price">$<?php echo (indexPrecio(1));?></p>
                <a href="#miFormulario"><button onclick="agregarAlCarrito(1)">Agregar al Carrito</button></a>
            </div>
            <div class="product">
                <img src="img/p1.jpg" width="100" alt="Producto 2">
                <h3><?php echo (indexArticulo(2)); ?></h3>
                <p>Disponible</p>
                <p class="price">$<?php echo (indexPrecio(2));?></p>
                <a href="#miFormulario"><button onclick="agregarAlCarrito(2)">Agregar al Carrito</button></a>
            </div>
            <div class="product">
                <img src="img/p1.jpg" width="100" alt="Producto 3">
                <h3><?php echo (indexArticulo(3)); ?></h3>
                <p>Disponible</p>
                <p class="price">$<?php echo (indexPrecio(3));?></p>
                <a href="#miFormulario"><button onclick="agregarAlCarrito(3)">Agregar al Carrito</button></a>
            </div>

            <div class="product">
                <img src="img/p1.jpg" width="100" alt="Producto 4">
                 <h3><?php echo (indexArticulo(4)); ?></h3>
                <p>Disponible</p>
                <p class="price">$<?php echo (indexPrecio(4));?></p>
                <a href="#miFormulario"><button onclick="agregarAlCarrito(4)">Agregar al Carrito</button></a>
            </div>
            <div class="product">
                <img src="img/p1.jpg" width="100" alt="Producto 5">
                 <h3><?php echo (indexArticulo(5)); ?></h3>
                <p>Disponible</p>
                <p class="price">$<?php echo (indexPrecio(5));?></p>
                <a href="#miFormulario"><button onclick="agregarAlCarrito(5)">Agregar al Carrito</button></a>
            </div>
            <div class="product">
                <img src="img/p1.jpg" width="100" alt="Producto 6">
                 <h3><?php echo (indexArticulo(6)); ?></h3>
                <p>Disponible</p>
                <p class="price">$<?php echo (indexPrecio(6));?></p>
                <a href="#miFormulario"><button onclick="agregarAlCarrito(6)">Agregar al Carrito</button></a>
            </div>
            <div class="product">
                <img src="img/p1.jpg" width="100" alt="Producto 7">
                 <h3><?php echo (indexArticulo(7)); ?></h3>
                <p>Disponible</p>
                <p class="price">$<?php echo (indexPrecio(7));?></p>
                <a href="#miFormulario"><button onclick="agregarAlCarrito(7)">Agregar al Carrito</button></a>
            </div><div class="product">
            <img src="img/p1.jpg" width="100" alt="Producto 8">
                 <h3><?php echo (indexArticulo(8)); ?></h3>
                <p>Disponible</p>
                <p class="price">$<?php echo (indexPrecio(8));?></p>
                <a href="#miFormulario"><button onclick="agregarAlCarrito(8)">Agregar al Carrito</button></a>
            </div>
            <div class="product">
                <img src="img/p1.jpg" width="100" alt="Producto 9">
                 <h3><?php echo (indexArticulo(9)); ?></h3>
                <p>Disponible</p>
                <p class="price">$<?php echo (indexPrecio(9));?></p>
                <a href="#miFormulario"><button onclick="agregarAlCarrito(9)">Agregar al Carrito</button></a>
            </div>


        </div>

        <form action="agregararticulo.php" method="post" name="form" id="miFormulario">
            <table border="1" with="280">

                <tr>
                    <td with="70">Articulo</td>
                    <td with="180"><select id="articulos" name="articulo">
                            <option value="0">Seleccionar Artículo</option>
                            <option value="1">Proteína de Suero de Leche  (Sabor Chocolate)</option>
                            <option value="2">Proteína de Suero de Leche (Sabor Vainilla)</option>
                            <option value="3">Proteína de Caseína (Sabor Fresa)</option>
                            <option value="4">Proteína de Caseína (Sabor Chocolate)</option>
                            <option value="5">Proteína de Guisante (Sabor Natural)</option>
                            <option value="6">Proteína de Guisante (Sabor Vainilla)</option>
                            <option value="7">Proteína de Soja (Sabor Chocolate)</option>
                            <option value="8">Proteína de Arroz (Sabor Natural)</option>
                            <option value="9">Proteína Vegana (Mezcla de Guisante y Arroz, Sabor Cookies y Crema)</option>


                        </select>
                    </td>

                </tr>

                <tr>
                    <td with="70">Cantidad</td>
                    <td with="180"><input size="30" type="text" name="cantidad" autocomplete="off" value="<?php echo $cantidad; ?>" required=""> </td>

                </tr>
            </table>

            <input type="submit" name="enviar" id="enviar" autocomplete="off" value="Detalle del pedido">
            <a href="carrito.php" class="btn">Volver a Carrito</a>


        </form>
        <script>
            // Función para agregar un producto al carrito y seleccionar su índice en el select
            function agregarAlCarrito(index) {
                var selectArticulo = document.getElementById('articulos');
                selectArticulo.selectedIndex = index;
            }
        </script>
    </body>

</html>
