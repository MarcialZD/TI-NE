<?php
session_start(); // Iniciar la sesión
require 'db_connect.php'; // Incluir la conexión a la base de datos

if (isset($_GET['id'])) {
    $producto_id = $_GET['id'];
}

$stmt = $conexion->prepare("
    SELECT 
        a.id,
        a.nombre,
        a.precio,
        CASE 
            WHEN a.fecha_maxima_descuento >= CURDATE() THEN 
                a.precio - (a.precio * a.porcentaje_descuento / 100)
            ELSE 
                a.precio 
        END AS precio_final,
        a.stock,
        a.descripcion,
        a.imagen
    FROM articulos a 
    WHERE a.id = ?
");
$stmt->bind_param("i", $producto_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $producto = $resultado->fetch_assoc();
} else {
    echo "Producto no encontrado.";
    exit();
}

$cant_total_productos = 0;
if (isset($_SESSION["username"])) {
    $usuario_id = $_SESSION["user_id"];
    $stmt = $conexion->prepare("SELECT SUM(cantidad) AS cantidad_total FROM carritos WHERE usuario_id = ?");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado) {
        $fila = $resultado->fetch_assoc();
        $cant_total_productos = $fila['cantidad_total'] ?? 0;
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $cantidad = $_POST['cantidad'];
    $comentario = "Relleno: " . $_POST['relleno'] . ", " . "Cobertura: " . $_POST['cobertura'] . ", " . "Mensaje: " . $_POST['mensaje'] . ", " . "Masa: " . $_POST['masa'];

    if (isset($_SESSION["user_id"])) {
        $usuario_id = $_SESSION["user_id"];
        $stmt_stock = $conexion->prepare("SELECT stock FROM articulos WHERE id = ?");
        $stmt_stock->bind_param("i", $producto_id);
        $stmt_stock->execute();
        $resultado_stock = $stmt_stock->get_result();
        $producto_stock = $resultado_stock->fetch_assoc()['stock'];

        if ($cantidad > $producto_stock) {
            echo "<script>
                    alert('No hay suficiente stock disponible. Solo quedan $producto_stock unidades.');
                    window.location.href = 'vistaProducto.php?id=$producto_id';
                  </script>";
        } else {
            $stmt = $conexion->prepare("INSERT INTO carritos (usuario_id, articulo_id, cantidad, subtotal, mensaje) VALUES (?, ?, ?, 0, ?)");
            $stmt->bind_param("iiis", $usuario_id, $producto_id, $cantidad, $comentario);
            if ($stmt->execute()) {
                $stmt_stock_update = $conexion->prepare("UPDATE articulos SET stock = stock - ? WHERE id = ?");
                $stmt_stock_update->bind_param("ii", $cantidad, $producto_id);

                if ($stmt_stock_update->execute()) {
                    echo "Artículo agregado al carrito con éxito.";
                } else {
                    echo "Error al actualizar el stock: " . $stmt_stock_update->error;
                }

                $stmt_stock_update->close();
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
            header("Location: carrito.php");
            exit();
        }

        $stmt_stock->close();
    } else {
        echo '<script>alert("Ingrese sesión para agregar al carrito");</script>';
    }
}

$conexion->close(); // Cerrar la conexión a la base de datos
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista del Producto</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
          
        }
        .navbar{
            background-color: #faeae5;
            color: #e8306d;
            margin-bottom: 2%;
            text-align: center;
        }

        .container {
            max-width: 1100px;
        }

        .product-image {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .product-details {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .product-details h2 {
            color: #2b2d42;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .product-details p {
            font-size: 1rem;
            color: #6c757d;
            margin-bottom: 20px;
        }

        .product-price {
            font-size: 1.5rem;
            font-weight: bold;
            color: #e8306d;
        }

        .form-group select,
        .form-group input {
            border-radius: 5px;
            border: 1px solid #ddd;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 1%;
        }

        .form-group select:focus,
        .form-group input:focus {
            border-color: #e8306d;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1), 0 0 5px rgba(232, 48, 109, 0.6);
        }

        .btn {
            background-color: #e8306d;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            font-size: 1rem;
        }

        .btn:hover {
            background-color: #f10f5a;
            color: #2b2d42;
        }

        .btn-back {
            background-color: #f10f5a;
            color: #2b2d42;
        }

        .btn-back:hover {
            background-color: #e8306d;
            color: #fff;
        }

        footer {
            background-color: #faeae5;
            color: #e8306d;
            padding: 20px 0;
            margin-top: 30px;
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
<nav class="navbar navbar-expand-lg navbar-light ">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="img/NutriCode_logo_sin_fondo.png" width="100" height="90" alt="Logo">
            </a>
            <button class="navbar-toggler custom-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav" style="color: #0e9edf;">
                <ul class="navbar-nav ms-auto" style="color: #0e9edf;">
                    <li class="nav-item">
                        <a class="nav-link" href="blog" style="color: #f10f5a;">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="servicios" style="color: #f10f5a">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="productos" style="color: #f10f5a">Pasteles</a>
                    </li>
                    <?php
                    if (!isset($_SESSION["username"])) {
                        echo '<li class="nav-item"><a class="nav-link" href="carrito" style="color: #f10f5a"><i class="fa-solid fa-user active"></i> Cuenta</a></li>';
                    } else {
                        echo '<li class="nav-item"><a class="nav-link" href="carrito" style="color: #f10f5a;">' . $_SESSION["username"] . ' <i class="fa-solid fa-cart-shopping"></i>(' . $cant_total_productos . ')</a></li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
    <div class="row">
        <div class="col-md-6">
            <img src="<?php echo $producto['imagen']; ?>" class="img-fluid product-image" alt="Producto">
        </div>
        <div class="col-md-6 product-details">
            <h2><?php echo $producto['nombre']; ?></h2>
            <p><?php echo $producto['descripcion']; ?></p>
            <p class="product-price">Precio: $ <?php echo number_format($producto['precio_final'], 2); ?></p>

            <form action="vistaProducto.php?id=<?php echo $producto['id']; ?>" method="POST">
                <div class="form-group">
                    <label for="cantidad">Cantidad</label>
                    <input type="number" name="cantidad" id="cantidad" class="form-control" min="1" max="<?php echo $producto['stock']; ?>" value="1" >
                </div>

                <div class="form-group">
                    <label for="relleno">Relleno</label>
                    <select name="relleno" id="relleno" class="form-control" >
                        <option value="Chocolate">Chocolate</option>
                        <option value="Crema">Crema</option>
                        <option value="Caramelo">Caramelo</option>
                        <option value="Nuez">Nuez</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="cobertura">Cobertura</label>
                    <select name="cobertura" id="cobertura" class="form-control" >
                        <option value="Chocolate">Chocolate</option>
                        <option value="Manteca">Manteca</option>
                        <option value="Glaseado">Glaseado</option>
                        <option value="Azúcar Glass">Azúcar Glass</option>
                    </select>
                </div>

              
                <div class="form-group">
                    <label for="masa">Masa</label>
                    <select name="masa" id="masa" class="form-control" >
                        <option value="Bizcocho">Bizcocho</option>
                        <option value="Manteca">Manteca</option>
                        <option value="Almendra">Almendra</option>
                        <option value="Chocolate">Chocolate</option>
                    </select>
                </div>

              

           
                <div class="form-group">
                    <label for="tipo_pastel">Tipo de pastel</label>
                    <select name="tipo_pastel" id="tipo_pastel" class="form-control" >
                        <option value="Tradicional">Tradicional</option>
                        <option value="Vegano">Vegano</option>
                        <option value="Sin Gluten">Sin Gluten</option>
                        <option value="Bajo en Azúcar">Bajo en Azúcar</option>
                    </select>
                </div>
            
                <div class="form-group">
                    <label for="mensaje">Mensaje en la torta</label>
                    <input type="text" name="mensaje" id="mensaje" class="form-control" placeholder="Escribe tu mensaje" >
                </div>

                <button type="submit" class="btn">Agregar al Carrito</button>
            </form>
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
                <p><a href="https://tiktok.com" target="_blank"><i class="fa-brands fa-tiktok"></i> TikTok</a></p> 
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
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
