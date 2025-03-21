
<?php 
session_start();
require 'db_connect.php';
function obtener_comentarios_por_articulo($article_id) {
    global $conexion; 

    $query = "SELECT comentarios.comentario, comentarios.created_at, usuarios.username 
              FROM comentarios 
              INNER JOIN usuarios ON comentarios.user_id = usuarios.id 
              WHERE comentarios.article_id = ? 
              ORDER BY comentarios.created_at ASC";

    if ($stmt = $conexion->prepare($query)) {

        $stmt->bind_param("i", $article_id); 

        
        $stmt->execute();

       
        $result = $stmt->get_result();
        
        $comentarios = [];
        
        while ($row = $result->fetch_assoc()) {
            $comentarios[] = $row;
        }

        $stmt->close();

        return $comentarios;
    } else {
        echo "Error al preparar la consulta: " . $conexion->error;
        return [];
    }
}
if(isset($_SESSION["user_id"])){

    $usuario_id = $_SESSION["user_id"];


    $consulta = "SELECT COUNT(*) AS total_productos, SUM(cantidad) AS cantidad_total
                 FROM carritos
                 WHERE usuario_id = ?";
    
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado) {
        $fila = $resultado->fetch_assoc();
        $cant_total_productos = $fila['cantidad_total'];
        if ($cant_total_productos == null) {
            $cant_total_productos = 0;
        }
    } else {
    }
    
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repostería Dargel | Blog</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script async src="https://www.googletagmanager.com/gtag/js?id=G-6BER8BQQ0Z"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-6BER8BQQ0Z');
    </script>

    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KK32PFZL"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

    <link rel="icon" href="images/logo.png" type="image/png">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">

    <style>
        nav {
            text-align: center;
            width: 100%;
            background-color: #faeae5;
        }

        footer {
            background-color: #faeae5;
            color: #e8306d;
            padding: 20px 0;
            text-align: center;
        }

        footer a {
            color: #e8306d;
            text-decoration: none;
        }

        footer img {
            width: 30px;
            height: 30px;
        }

        .whatsapp-button {
            position: fixed;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            display: inline-flex;
            align-items: center;
            background-color: #25D366;
            color: white;
            padding: 10px 15px;
            border-radius: 25px;
            text-decoration: none;
            font-size: 16px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border: 3px solid transparent;
            animation: pulse-border 0.5s infinite;
        }

        @keyframes pulse-border {
            0% {
                border-color: transparent;
                transform: scale(1);
            }

            50% {
                border-color: #128C7E;
                transform: scale(1.1);
            }

            100% {
                border-color: transparent;
                transform: scale(1);
            }
        }

        .whatsapp-button i {
            font-size: 20px;
            margin-right: 8px;
        }

        .whatsapp-button:hover {
            background-color: #128C7E;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        form {
            max-width: 400px;
            margin: 3% auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        form h1.title {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: #e8306d;
            font-weight: bold;
        }

        form label {
            display: block;
            width: 100%;
            margin-bottom: 15px;
            text-align: left;
        }

        form input {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
            box-sizing: border-box;
        }

        form input:focus {
            border-color: #e881a2;
            box-shadow: 0 0 5px rgba(232, 129, 162, 0.5);
        }

        form input[type="submit"] {
            background-color: #e8306d;
            color: #fff;
            font-size: 1rem;
            font-weight: bold;
            border: none;
            cursor: pointer;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #c8285c;
        }

        form a {
            display: inline-block;
            margin-top: 10px;
            color: #e881a2;
            font-size: 0.9rem;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        form a:hover {
            color: #c8285c;
        }

        .card-container {
            margin-top: 50px;
            display: block;
        }

        .card {
            width: 100%;
            margin-bottom: 20px;
        }

        .card-body {
            padding: 20px;
            text-align: justify;
        }

        .card-title {
            font-size: 1.5rem;
            color: #e8306d;
        }

        .btn-custom {
            background-color: #e8306d;
            color: white;
            margin: 5px;
            border-radius: 5px;
            padding: 10px 20px;
            transition: background-color 0.3s ease;
        }

        .btn-custom:hover {
            background-color: #c8285c;
        }
    </style>
</head>

<body>
    <?php 
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $user_id = $_SESSION['user_id']; 
        $article_id = $_POST['article_id']; 
        $comentario = mysqli_real_escape_string($conexion, $_POST['comentario']); 
    
        $query = "INSERT INTO comentarios (user_id, article_id, comentario) VALUES ('$user_id', '$article_id', '$comentario')";
        
        if (mysqli_query($conexion, $query)) {
            echo "  
    <script>
                    Swal.fire({
                        title: 'Comentario enviado!',
                        text: 'Tu comentario se ha enviado correctamente.',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'blog.php'; // O la página donde quieras redirigir
                        }
                    });
                  </script>";
        } else {
            echo "Error al enviar el comentario: " . mysqli_error($conn);
        }
    }
    ?>
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

    <div class="container mt-5">
        <h2 class="text-center text-danger">Blog de Repostería Dargel</h2>

        <div class="card-container">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Pasteles para Baby Showers</h5>
                    <p>El baby shower es uno de los momentos más esperados para los futuros padres, y un pastel único puede marcar la diferencia en la decoración y el ambiente del evento. En Dargel Repostería, ofrecemos una amplia variedad de pasteles personalizados para baby showers, desde diseños tiernos hasta decoraciones modernas. Nuestros pasteles son hechos con ingredientes frescos y de la mejor calidad, para asegurar un sabor delicioso que acompañe ese momento tan especial.</p>
                    <p>Con diseños innovadores y personalizados, podemos crear el pastel perfecto para que tu evento sea memorable. Ya sea que busques algo clásico o algo más original, tenemos opciones que se adaptan a tus gustos y necesidades. La variedad en colores, sabores y temas es infinita, lo que garantiza que cada pastel sea único y especial.</p>
                    <p><strong>Ideas populares:</strong> Diseños con animales, personajes de cuentos, colores pastel o temas de naturaleza. Además, podemos incorporar nombres, fechas o mensajes especiales para hacerlo aún más personal.</p>
                </div>
            </div>
               <h5>Comentarios:</h5>
            <?php
            $comentarios = obtener_comentarios_por_articulo(1); 
            foreach ($comentarios as $comentario) {
                echo "<div class='comentario'>";
                echo "<p><strong>" . $comentario['username'] . ":</strong></p>";
                echo "<p>" . $comentario['comentario'] . "</p>";
                echo "<p><small>Publicado el: " . $comentario['created_at'] . "</small></p>";
                echo "</div>";
            }
            ?>
            <?php if (isset($_SESSION["username"])): ?>
                <form action="" method="POST" style="max-width: 1000px; margin: 3% auto;">
                    <div class="form-group">
                        <label for="comentario">Tu comentario:</label>
                        <textarea name="comentario" id="comentario" rows="4" class="form-control" required></textarea>
                    </div>
                    <input type="hidden" name="article_id" value=1> 
                    <button type="submit" class="btn btn-custom mt-3">Enviar comentario</button>
                </form>
            <?php else: ?>
                <p>Para comentar, <a href="login.php">inicia sesión</a> primero.</p>
            <?php endif; ?>

         


            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Tendencias de Decoración en Pasteles para Fiestas Infantiles</h5>
                    <p>Las fiestas infantiles se vuelven más especiales con un pastel único y bien decorado. En Dargel, seguimos las últimas tendencias en decoración de pasteles para fiestas infantiles. Ofrecemos diseños innovadores y personalizados que van desde figuras divertidas hasta temas inspirados en la naturaleza. Nuestros pasteles son perfectos para cualquier ocasión y garantizan hacer de tu fiesta un evento memorable.</p>
                    <p>Los niños disfrutan de pasteles que combinan tanto en sabor como en diseño. Desde temas de personajes de fantasía hasta animales y paisajes encantados, en Dargel podemos hacer realidad cualquier idea. Cada pastel está diseñado pensando en hacer sonreír a los más pequeños mientras disfrutan de un sabor delicioso y único.</p>
                    <p><strong>Temas populares:</strong> Superhéroes, princesas, animales, piratas y personajes de películas famosas. ¡La imaginación no tiene límites!</p>
                </div>
            </div>
<h5>Comentarios:</h5>
<?php
$comentarios = obtener_comentarios_por_articulo(2); 
foreach ($comentarios as $comentario) {
    echo "<div class='comentario'>";
    echo "<p><strong>" . $comentario['username'] . ":</strong></p>";
    echo "<p>" . $comentario['comentario'] . "</p>";
    echo "<p><small>Publicado el: " . $comentario['created_at'] . "</small></p>";
    echo "</div>";
}
?>
<?php if (isset($_SESSION["username"])): ?>
    <form action="" method="POST" style="max-width: 1000px; margin: 3% auto;">
        <div class="form-group">
            <label for="comentario">Tu comentario:</label>
            <textarea name="comentario" id="comentario" rows="4" class="form-control" required></textarea>
        </div>
        <input type="hidden" name="article_id" value=2> 
        <button type="submit" class="btn btn-custom mt-3">Enviar comentario</button>
    </form>
<?php else: ?>
    <p>Para comentar, <a href="login.php">inicia sesión</a> primero.</p>
<?php endif; ?>



        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Tips para Mantener tu Pastel Fresco y Delicioso</h5>
                <p>Después de recibir tu pastel de Dargel Repostería, es importante saber cómo mantenerlo fresco para disfrutarlo en su mejor estado. Aquí te damos algunos consejos para que tu pastel mantenga su sabor y textura intactos por más tiempo:</p>
                <ul>
                    <li><strong>Conservación adecuada:</strong> Guarda tu pastel en un lugar fresco y seco, preferentemente en la nevera si tiene rellenos o decoraciones frescas.</li>
                    <li><strong>Evitar cambios bruscos de temperatura:</strong> No expongas tu pastel a cambios drásticos de temperatura, ya que esto puede afectar su textura y sabor.</li>
                    <li><strong>Cortar porciones pequeñas:</strong> Si no vas a consumir todo el pastel, corta porciones pequeñas para evitar que el aire entre en contacto con el pastel completo.</li>
                    <li><strong>Usar envases herméticos:</strong> Si decides guardar un trozo, asegúrate de envolverlo adecuadamente en plástico o utilizar un envase hermético para mantener su frescura.</li>
                </ul>
                <p>Siguiendo estos consejos, podrás disfrutar de tu pastel por más tiempo sin comprometer su delicioso sabor y frescura.</p>
            </div>
        </div>
<h5>Comentarios:</h5>
<?php
$comentarios = obtener_comentarios_por_articulo(3); 
foreach ($comentarios as $comentario) {
    echo "<div class='comentario'>";
    echo "<p><strong>" . $comentario['username'] . ":</strong></p>";
    echo "<p>" . $comentario['comentario'] . "</p>";
    echo "<p><small>Publicado el: " . $comentario['created_at'] . "</small></p>";
    echo "</div>";
}
?>        
<?php if (isset($_SESSION["username"])): ?>
    <form action="" method="POST" style="max-width: 1000px; margin: 3% auto;"  >
        <div class="form-group" style="width: 80%;" >
            <label for="comentario">Tu comentario:</label>
            <textarea name="comentario" id="comentario" rows="4" class="form-control" required></textarea>
        </div>
        <input type="hidden" name="article_id" value=3> 
        <button type="submit" class="btn btn-custom mt-3">Enviar comentario</button>
    </form>
<?php else: ?>
    <p>Para comentar, <a href="login.php">inicia sesión</a> primero.</p>
<?php endif; ?>



    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<a href="https://wa.me/51917333240" target="_blank" class="whatsapp-button">
  <i class="fab fa-whatsapp"></i> 
</a>

<style>
  .whatsapp-button {
    position: fixed;
    right: 30px; 
    top: 75%; 
    transform: translateY(-50%); 
    display: inline-flex;
    align-items: center;
    background-color: #25D366; 
    color: white;
    border-radius: 25px;
    text-decoration: none;
    font-size: 16px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    border: 1px solid transparent; 
    animation: pulse-border 3s infinite; 
    display: flex;
    align-items: center;
    width: 50px;

  }

  @keyframes pulse-border {
    0% {
      border-color: transparent; 
      transform: scale(1); 
    }
    50% {
      border-color: #128C7E; 
      transform: scale(1.2); 
    }
    100% {
      border-color: transparent; 
      transform: scale(1); 
    }
  }

  .whatsapp-button i {
    font-size: 20px;
  
  }

  .whatsapp-button:hover {
    background-color: #128C7E; 
  }
</style>


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
</body>

</html>