<?php

include_once 'db_connect.php';

// Suponiendo que ya tienes una conexión a la base de datos
$sql = "SELECT id, nombre, precio, imagen, porcentaje_descuento, fecha_maxima_descuento FROM articulos WHERE id = 12";
$result = $conexion->query($sql);

// Verificamos si hay resultados
if ($result->num_rows > 0) {
    $producto = $result->fetch_assoc();
    $nombre = $producto['nombre'];
    $precio = $producto['precio'];
    $imagen = $producto['imagen'];
    $porcentaje_descuento = $producto['porcentaje_descuento'];
    
$porcentaje_descuento = round($porcentaje_descuento); // Redondear el porcentaje sin decimales


    $fecha_maxima_descuento = $producto['fecha_maxima_descuento'];
} else {
    // Si no se encuentra el producto, se puede manejar el error
    echo "Producto no encontrado";
}




function verificarCorreoExistente($conexion, $email)
{
    $count = 0;

    $checkStmt = $conexion->prepare("SELECT COUNT(*) FROM usuario_landing WHERE email = ?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();
    return $count > 0;
}

// Función para verificar si el nombre y teléfono ya están registrados
function verificarNombreExistente($conexion, $nombre)
{
    $count = 0;

    $checkStmt = $conexion->prepare("SELECT COUNT(*) FROM usuario_landing WHERE nombre = ? ");
    $checkStmt->bind_param("s", $nombre);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();
    return $count > 0;
}

// Función para verificar si el  teléfono ya están registrados
function verificarTelefonoExistente($conexion, $telefono)
{
    $count = 0;

    $checkStmt = $conexion->prepare("SELECT COUNT(*) FROM usuario_landing WHERE telefono = ? ");
    $checkStmt->bind_param("s", $telefono);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();
    return $count > 0;
}
session_start(); // Iniciar la sesión
if (isset($_POST["enviar_form_lan"]) && isset($_POST['nombre'], $_POST['email'], $_POST['telefono'])) {
    // Obtener y limpiar los datos del formulario
    $nombre = htmlspecialchars(trim($_POST['nombre']));
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $telefono = htmlspecialchars(trim($_POST['telefono']));
    $_SESSION['email'] = htmlspecialchars($_POST['email']);

    // Verificar que los campos no estén vacíos y que el correo sea válido
    if ($nombre && $email && $telefono) {
        // Verificar si el correo ya está registrado utilizando la función
        if (verificarCorreoExistente($conexion, $email)) {
            echo "El correo ya está registrado. Intenta con otro correo.";
        }
        // Verificar si el nombre y teléfono ya están registrados
        elseif (verificarNombreExistente($conexion, $nombre)) {
            echo "El  nombre ya está registrado. Intenta con otros datos.";
        } elseif (verificarTelefonoExistente($conexion, $telefono)) {
            echo "El  teléfono ya está registrado. Intenta con otros datos.";
        } else {
            // Generar un código de descuento aleatorio
            

            $codigo_descuento = 'DESC-' . strtoupper(substr(md5(uniqid()), 0, 8));
            $_SESSION['codigo_descuento'] =$codigo_descuento;
            // Preparar la consulta SQL para insertar los datos
            $stmt = $conexion->prepare("INSERT INTO usuario_landing (nombre, email, telefono, codigo_descuento) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nombre, $email, $telefono, $codigo_descuento);

            // Ejecutar la consulta y manejar los errores
            if ($stmt->execute()) {
                echo "Registro exitoso. ¡Gracias por unirte!";
            } else {
                echo "Error al registrar los datos: " . $conexion->error;
            }
          
            // Cerrar la consulta preparada
            $stmt->close();
            header("Location: enviar_e.php");
        }
    }
} else {
   
}


?>




<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repostería Dargel</title>
    <link rel="icon" href="images/logo.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">

    <script type="text/javascript" src="https://gc.kis.v2.scr.kaspersky-labs.com/FD126C42-EBFA-4E12-B309-BB3FDD723AC1/main.js?attr=DgITCFOimfZ2pwzc0l2zw890JhKLLXa2bu-I8JedYh45U0pFim77BOdl1fVJgKfl7WuTnb1ckFpMg3Rc7gFJIoX1B7KNAy4VhoybrmAyaHnInXPwY9jh10cowtvoj8-U7-0CMQi0Uff21-srrPaSY5luRDxUFSiDxRCI0kyHmk66i33HiW1MNgJvj9zQ_Gey" charset="UTF-8"></script>
    <script type="text/javascript" src="https://gc.kis.v2.scr.kaspersky-labs.com/FD126C42-EBFA-4E12-B309-BB3FDD723AC1/main.js?attr=tGr6BXp4dGqaMBRjyeNHcXebXlNafCIgIIHk4XRP1aMIBnn_j6hI7iYOGU8GTdURIuhLIkzDaggYSu2vhLgEgWKu_JVvbys6DVg4P10Kzf1OALWt5_8rBGFBqi31tprDrQ0bJ2V8bl8ghnKtcSdrS3e8g8xixOWZo8yERNx-oFxUE71ILMr9qARS-3taBKIb" charset="UTF-8"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #faeae5;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        header {
            text-align: center;
            background-color: #a5dbdf;
            padding: 10px;
            width: 100%;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        header img {
            max-height: 100px;
        }

        .hero {
            position: relative;
            margin: 20px 0;
            max-width: 1200px;
            width: 100%;
            height: 400px;
            display: flex;
            align-items: center;
            background-image: url('images/pasteleria.jpg');
            background-size: cover;
            background-position: left center;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .hero-text {
            flex: 1;
            color: white;
            padding-left: 20px;
            text-align: left;
        }

        .form-section {
            flex: 0 0 350px;
            padding: 20px;
            text-align: left;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-left: 20px;
            transition: transform 0.3s;
        }

     

        h1,
        h2,
        h3,
        h4 {
            margin: 10px 0;
            color: #4d4c4c;
        }

        h2 {
            font-size: 1.5em;
            color: #555;
        }

        h3 {
            font-size: 1.3em;
            margin-bottom: 10px;
        }

        input,
        textarea {
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 300px;
            font-size: 1em;
        }

        button {
            padding: 12px 15px;
            background-color: #a5dbdf;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.3s;
            margin-top: 10px;
            font-size: 1em;
        }

        button:hover {
            background-color: #88c3c7;
            transform: translateY(-2px);
        }

        footer {
            text-align: center;
            padding: 10px;
            background-color: #a5dbdf;
            position: relative;
            bottom: 0;
            width: 100%;
        }

        .comments-section {
            flex: 1;
            padding: 20px;
            text-align: left;
            max-width: 600px;
            width: 100%;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
        }

        .comments-section h3 {
            font-size: 1.8em;
            color: #333;
            margin-bottom: 15px;
            text-align: center;
        }

       

        @media (max-width: 768px) {
            .hero {
                flex-direction: column;
                align-items: center;
                height: auto;
                background-position: center;
            }

            .hero-text {
                padding-left: 0;
                text-align: center;
            }

            .form-section {
                margin-left: 0;
                width: 100%;
                max-width: none;
            }

            .calendar-grid {
                grid-template-columns: repeat(3, 1fr);
                /* 3 columnas en pantallas pequeñas */
            }
        }

        .form-container {
            display: flex;
            justify-content: space-between;
            max-width: 1200px;
            width: 100%;
            margin:  0;
        }

        .form-section p {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 15px;
            border-radius: 5px;
            color: #333;
            font-size: 1em;
          align-items: center;
            text-align: justify;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            justify-content: center;

        }

      


        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #faeae5;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        header {
            text-align: center;
            background-color: #a5dbdf;
            padding: 10px;
            width: 100%;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        header img {
            max-height: 150px;
        }

        .hero {
            position: relative;
            margin: 20px 0;
            max-width: 1200px;
            width: 100%;
            height: 400px;
            display: flex;
            align-items: center;
            background-image: url('images/pasteleria.jpg');
            background-size: cover;
            background-position: left center;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .hero-text {
            flex: 1;
            color: white;
            padding-left: 20px;
            text-align: left;
        }

        .form-section {
            flex: 0 0 350px;
            padding: 20px;
            text-align: left;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-left: 20px;
         
        }

   

        h1,
        h2,
        h3,
        h4 {
            margin: 10px 0;
            color: #4d4c4c;
        }

        h2 {
            font-size: 1.5em;
            color: #555;
        }

        h3 {
            font-size: 1.3em;
            margin-bottom: 10px;
        }

        input,
        textarea {
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 300px;
            font-size: 1em;
        }

        button {
            padding: 12px 15px;
            background-color: #a5dbdf;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.3s;
            margin-top: 10px;
            font-size: 1em;
        }

        button:hover {
            background-color: #88c3c7;
            transform: translateY(-2px);
        }

        footer {
            text-align: center;
            padding: 10px;
            background-color: #a5dbdf;
            position: relative;
            bottom: 0;
            width: 100%;
        }

       

        .offer {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            text-align: center;
            width: 90%;
        }

        .offer h3 {
            font-size: 1.8em;
            color: #333;
            margin-bottom: 10px;
        }

        .offer p {
            font-size: 1.2em;
            color: #555;
            margin: 10px 0;
        }

        #countdown {
            font-weight: bold;
            font-size: 1.5em;
            color: #ff0000;
            margin-top: 15px;
        }

        .articles-container {
            display: flex;
            justify-content: space-between;
            max-width: 1200px;
            width: 100%;
            margin: 20px 0;
        }

        .article-section {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 45%;
            /* Ancho del 45% para permitir espacio entre ambos artículos */
            margin: 0 10px;
        }

        .article-section h3 {
            font-size: 1.8em;
            color: #333;
            margin-bottom: 10px;
            text-align: center;
        }

        .article-section p {
            margin: 10px 0;
            color: #555;
            line-height: 1.6;
        }

        @media (max-width: 768px) {
            .articles-container {
                flex-direction: column;
                align-items: center;
            }

            .article-section {
                width: 90%;
                /* Ajustar el ancho en pantallas pequeñas */
                margin: 10px 0;
            }
        }

    </style>
</head>

<body>
    <header>
        <img src="images/logo_vertical.PNG" alt="Logo Reposteria Dargel">
    </header>

    <div class="form-container">
        <section class="hero">
            <div class="hero-text">
                <style>
                    .frase-elegante {
    font-family: 'Playfair Display', serif; 
    font-size: 1.2em;
    color: #333; /* Color sobrio y profesional */
    font-weight: 600; /* Negrita para darle énfasis */
    margin: 20px 0; /* Espaciado elegante */
    line-height: 1.5; /* Mejora la legibilidad */
    position: relative;
}



                </style>
            <p class="frase-elegante">Convierte tus ideas en un pastel único</p>

            </div>
        </section>

        <div class="form-section">
            <form id="contact-form" name="contact-form" action="index.php" method="post">

                <input type="text" name="nombre" placeholder="Tu Nombre" required>
                <input type="email" name="email" placeholder="Tu Email" required>
                <input type="tel" name="telefono" placeholder="Tu Teléfono" required>
                <button type="submit" name="enviar_form_lan" style="color:#e8306d">Enviar</button>

            </form>
            <p>¡Completa el formulario y recibe un cupón de descuento en tu primer pedido</p>

        </div>
    </div>
    <style>
    .tarjeta-oferta {
        display: flex;
        background-color: #fdf6f8; /* Fondo suave y pastel */
        border-radius: 15px;
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin: 20px auto;
        width: 80%;
        font-family: 'Arial', sans-serif;
    }

    .tarjeta-contenido {
        display: flex;
        width: 100%;
        justify-content: space-between;
        padding: 20px;
        align-items: center;
    }


    .informacion-producto {
        flex: 2;
        padding-right: 20px;
        align-items: center;
        flex-direction: column; /* Alineación vertical de los elementos */
        justify-content: center; /* Centra los elementos verticalmente */

    }

    .informacion-producto h3 {
        font-size: 1.8em;
        color: #2c3e50;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .informacion-producto p {
        font-size: 1.1em;
        color: #7f8c8d;
        text-align: center;
    }

    .descuento {
        font-size: 1.4em;
        font-weight: bold;
        color: #e74c3c; /* Color de descuento en rojo */
    }

    .urgencia {
        font-size: 1.1em;
        color: #3498db; /* Color de urgencia en celeste */
        margin-top: 10px;
        font-weight: 600;
        text-align: center;
    }

    .contador {
        font-size: 1.2em;
        margin-top: 10px;
        color:#e8306d;
        font-weight: bold;
        text-align: center;
    }

    .imagen-producto {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 10px;
    }

    .imagen {
        width: 100%;
        height: auto;
        object-fit: cover;
        border-radius: 8px;
    }

   
</style>

<h2 class="titulo-comentarios">Oferta Especial</h2>
<div class="tarjeta-oferta" id="tarjeta-producto-<?php echo $producto['id']; ?>" style="width: 80%;">
    <div class="tarjeta-contenido">
        <div class="informacion-producto">
            <h3></h3>
            <p class="descuento"><?php echo $nombre; ?> con <span id="porcentaje-descuento"><?php echo $porcentaje_descuento; ?>% de descuento</span></p>
            <p class="urgencia">Aprovecha antes de que se acabe el tiempo</p>
            <div id="contador-<?php echo $producto['id']; ?>" class="contador"></div>
        </div>
        <div class="imagen-producto">
            <img src="<?php echo $imagen; ?>" alt="<?php echo $nombre; ?>" class="imagen" />
        </div>
    </div>
</div>



<script>
// Función para calcular el tiempo restante y actualizar el contador
function contarOferta(id, fechaLimite) {
    const contadorElemento = document.getElementById('contador-' + id);
    const fechaFinal = new Date(fechaLimite);
    
    setInterval(function() {
        const ahora = new Date();
        const tiempoRestante = fechaFinal - ahora;
        
        if (tiempoRestante <= 0) {
            contadorElemento.innerHTML = "Oferta terminada";
            clearInterval(this);
        } else {
            const dias = Math.floor(tiempoRestante / (1000 * 60 * 60 * 24));
            const horas = Math.floor((tiempoRestante % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutos = Math.floor((tiempoRestante % (1000 * 60 * 60)) / (1000 * 60));
            const segundos = Math.floor((tiempoRestante % (1000 * 60)) / 1000);
            
            contadorElemento.innerHTML = `${dias}d ${horas}h ${minutos}m ${segundos}s`;
        }
    }, 1000);
}

// Llamar a la función con la fecha máxima de descuento y el ID del producto
contarOferta(<?php echo $producto['id']; ?>, "<?php echo $fecha_maxima_descuento; ?>");
</script>

<style>
    body {
    font-family: 'Poppins', sans-serif;
    background-color: #f8f9fc;
    margin: 0;
    padding: 0;
}

.tarjetas-container {
    display: flex;
    justify-content: space-between;
    align-items: stretch; /* Asegura que todas las tarjetas tengan el mismo alto */
    width: 90%;
    margin: 40px auto;
    gap: 20px;
}

.tarjeta {
    display: flex;
    flex-direction: column;
    justify-content: space-between; /* Espaciado uniforme entre contenido y botón */
    align-items: center;
    background-color: #ffffff;
    border-radius: 15px;
    overflow: hidden;
    width: 30%;
    box-shadow: 0 1.5px 2px #e8306d;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    text-align: justify;
}


.tarjeta img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.tarjeta-content {
    padding: 20px;
    flex: 1; /* Permite que este bloque ocupe el espacio necesario */
}

.tarjeta-content h3 {
    font-size: 1.6em;
    color: #333;
    margin-bottom: 15px;
}

.tarjeta-content p {
    font-size: 1em;
    color: #666;
    line-height: 1.6;
}




/* Ajustes Responsivos */
@media (max-width: 768px) {
    .tarjetas-container {
        flex-direction: column;
        align-items: center;
    }

    .tarjeta {
        width: 90%;
        margin-bottom: 20px;
    }
}
.tarjeta .proximamente {
    font-size: 1em;
    color: #e8306d; /* Un tono de naranja suave para resaltar */
    font-weight: bold;
    margin-top: 10px;
    letter-spacing: 1px;
    text-transform: uppercase;
    font-family: 'Arial', sans-serif;
    display: block;
    opacity: 0.8;
}
</style>
<h2 class="titulo-comentarios">Novedades</h2>
    <div class="tarjetas-container">
    <div class="tarjeta">
        <img src="images/pastel_navidad.jpg" alt="Pastel de Navidad">
        <div class="tarjeta-content">
            <h3>Pasteles de Navidad</h3>
            <p>Endulza tus fiestas con nuestros pasteles decorados con motivos navideños, ideales para compartir en familia y amigos.</p>
            <span class="proximamente">Próximamente</span>

        </div>
    </div>
    <div class="tarjeta">
        <img src="images/pastel_ano_nuevo.jpg" alt="Pastel de Año Nuevo">
        <div class="tarjeta-content">
            <h3>Pasteles de Año Nuevo</h3>
            <p>Recibe el nuevo año con un pastel personalizado que será el centro de atención de tu celebración.</p>
            <span class="proximamente">Próximamente</span>

        </div>
    </div>
    <div class="tarjeta">
        <img src="images/pastel_verano.jpg" alt="Pastel Temático de Primavera">
        <div class="tarjeta-content">
            <h3>Pasteles de Verano</h3>
            <p>Celebra la frescura y color del Verano con pasteles decorados.</p>
            <span class="proximamente">Próximamente</span>

        </div>
    </div>
</div>



    <style>


header {
    text-align: center;
    background-color: #a5dbdf;
    padding: 10px;
    width: 100%;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

header img {
    max-height: 100px;
    width: auto; /* Imagen adaptable */
}

.hero {
    position: relative;
    margin: 20px 0;
    width: 100%;
    height: 400px;
    display: flex;
    align-items: center;
    background-image: url('images/pasteleria.jpg');
    background-size: cover;
    background-position: center;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.hero-text {
    flex: 1;
    color: white;
    padding-left: 20px;
    text-align: left;
}

.form-container {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    max-width: 1200px;
    width: 100%;
    margin: 20px 0;
    padding: 0 20px;
    box-sizing: border-box;
}

.form-section {
    flex: 0 0 350px;
    padding: 20px;
    text-align: left;
    background-color: rgba(255, 255, 255, 0.9);
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-left: 20px;
    max-width: 100%;
    margin-bottom: 25px;
    margin-top: 20px;
}


h1, h2, h3, h4 {
    margin: 10px 0;
    color: #4d4c4c;
}

h2 {
    font-size: 1.5em;
    color: #555;
}

h3 {
    font-size: 1.3em;
    margin-bottom: 10px;
}

input, textarea {
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 4px;
    width: 100%;
    font-size: 1em;
    box-sizing: border-box;
}

button {
    padding: 12px 15px;
    background-color: #a5dbdf;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.3s, transform 0.3s;
    margin-top: 10px;
    font-size: 1em;
    width: 100%;
}

button:hover {
    background-color: #88c3c7;
    transform: translateY(-2px);
}

footer {
    text-align: center;
    padding: 10px;
    background-color: #a5dbdf;
    position: relative;
    bottom: 0;
    width: 100%;
}




.comments-section {
    flex: 1;
    padding: 20px;
    text-align: left;
    max-width: 600px;
    width: 100%;
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin: 20px auto;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
}

.comments-section blockquote {
    background-color: #f9f9f9;
    border-left: 4px solid #a5dbdf;
    padding: 0px 15px;
    margin: 0;
    border-radius: 8px;
    font-style: italic;
}

.comments-section blockquote p {
    margin: 0;
    color: #555;
}

.comments-section blockquote footer {
    text-align: right;
    font-weight: bold;
    color: #333;
    margin-top: 0;
}

.comentarios-contenedor {
    width: 90%;
    margin: 0 auto;
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
}

.comentario {
    width: calc(25% - 20px);
    background-color: #ffffff;
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    transition: transform 0.3s ease;
}

.comentario:hover {
    transform: translateY(-5px);
}

.usuario {
    font-weight: bold;
    color: #2a7c7c;
    padding: 0;
    margin-bottom: 0;
    font-size: 16px;
}

.comentario-texto {
    font-size: 14px;
    color: #555;
    line-height: 1.6;
    text-align: justify;
}

@media (max-width: 768px) {
    /* Ajustes para pantallas pequeñas */
    header img {
        max-height: 80px;
    }

    .hero {
        height: 300px;
        background-position: center;
    }

    .form-container {
        flex-direction: column;
        align-items: center;
    }

    .form-section {
        width: 90%;
        margin: 10px 0;
    }

    .comments-section {
        width: 90%;
        margin: 10px 0;
    }

    .comentarios-contenedor {
        flex-direction: column;
        align-items: center;
    }

    .comentario {
        width: 100%;
        margin-bottom: 15px;
    }

    .offer {
        width: 100%;
        margin: 10px 0;
    }

    .articles-container {
        flex-direction: column;
        align-items: center;
    }

    .article-section {
        width: 90%;
        margin: 10px 0;
    }

    .calendar-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 480px) {
    /* Ajustes para pantallas muy pequeñas */
    .hero {
        height: 250px;
    }

    .offer h3 {
        font-size: 1.5em;
    }

    .offer p {
        font-size: 1.1em;
    }

    #countdown {
        font-size: 1.2em;
    }

    .form-section input,
    .form-section button {
        width: 100%;
    }
}

    </style>




<style>
  .comentarios-contenedor {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
    width: 90%;
    margin: 40px auto;
}

.comentario {
    background-color: #ffffff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    max-width: 300px;
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    position: relative;
    justify-content: space-between; /* Asegura que el nombre esté alineado con el comentario */
    height: 100%; /* Asegura que el contenedor tenga la altura suficiente */
}

.comentario:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

.comentario::before {
    content: "“";
    font-size: 6em;
    color: #a5dbdf;
    position: absolute;
    top: -30px;
    left: -4px;
    opacity: 10;
}

.comentario-texto {
    font-size: 1em;
    color: #555;
    line-height: 1.6;
    text-align: justify;
    margin-bottom: 15px;
    font-style: italic;
    position: relative;
    z-index: 1;
    flex-grow: 1; /* Hace que el comentario ocupe el espacio disponible */
}

.usuario {
    font-weight: bold;
    color: #2a7c7c;
    font-size: 1em;
    text-align: right;
    align-self: flex-end;
    margin-top: 10px; /* Asegura separación del comentario */
}

.usuario::before {
    content: "- ";
    color: #555;
    font-size: 0.9em;
    font-weight: normal;
}
.titulo-comentarios {
        font-family: 'Arial', sans-serif;
        font-size: 2em;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 20px;
        text-transform: uppercase;
        width: 60%;
        text-align: center;
    }

</style>
       <?php 
// Consulta para obtener los comentarios
$sql = "SELECT   u.nombres, o.comentario FROM opinion_usuario o
JOIN usuarios u ON o.id_usuario = u.id   ORDER BY u.id desc limit 3 ";
$result = $conexion->query($sql);

// Verificar si hay comentarios
if ($result->num_rows > 0) {
    echo '<h2 class="titulo-comentarios">Nuestros clientes</h2>';

    echo '<div class="comentarios-contenedor">';

    // Mostrar cada comentario
    while($row = $result->fetch_assoc()) {
        echo '<div class="comentario">';
        // Mostrar el comentario
        echo '<div class="comentario-texto">"<i>' . htmlspecialchars($row['comentario']) . '</i>"</div>';
        // Mostrar el nombre del usuario de forma destacada
        echo '<div class="usuario">' . htmlspecialchars($row['nombres']) . '</div>';
        echo '</div>';
    }
    echo '</div>';
} else {
    echo '<p>No hay comentarios disponibles.</p>';
}
$conexion->close();
?>
 <style>
    /* Estilos de la barra de cookies */
    #cookie-banner {
        position: fixed;
        bottom: 0;
        width: 100%;
        background-color: #2c3e50;
        color: white;
        text-align: center;
        padding: 15px 20px;
        z-index: 1000;
        font-family: Arial, sans-serif;
        box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.2);
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    /* Estilo del texto y enlace */
    #cookie-banner p {
        margin: 0 0 10px;
        font-size: 1rem;
    }

    #cookie-banner a {
        color: #f1c40f;
        text-decoration: underline;
        margin-left: 5px;
        font-size: 1rem;
    }

    /* Estilo del contenedor de botones */
    #cookie-banner .button-container {
        display: flex;
        gap: 10px;
        flex-wrap: wrap; /* Los botones se ajustarán si no caben horizontalmente */
        justify-content: center;
        width: 100%;
        flex-direction: row; /* Los botones están en fila horizontal */
    }

    /* Estilos generales de los botones */
    #cookie-banner button {
        background-color: #1abc9c;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1rem;
        flex: 0 1 auto; /* Ajusta el tamaño de los botones */
        max-width: 150px;
        min-width: 80px;
    }

    #cookie-banner button:hover {
        background-color: #16a085;
    }

    /* Ajustes para pantallas más pequeñas */
    @media (max-width: 600px) {
        #cookie-banner {
            padding: 10px 15px;
        }

        #cookie-banner button {
            font-size: 0.9rem; /* Reduce ligeramente el tamaño del texto */
            padding: 8px 12px;
        }

        #cookie-banner p {
            font-size: 0.9rem; /* Ajusta el tamaño del texto */
        }

        /* En pantallas pequeñas, cambia la dirección a vertical */
        #cookie-banner .button-container {
            flex-direction: column; /* Los botones estarán en columna en móviles */
            align-items: center;
        }
    }
</style>
         <!-- Contenedor del banner de cookies -->
    <div id="cookie-banner">
        <p>
            Este sitio utiliza cookies para garantizar que obtengas la mejor experiencia en nuestro sitio web. 
            <a href="politica-cookies.html">Leer más</a>
        </p>
        <div class="button-container">

        <button id="accept-cookies">Aceptar</button>
        <button id="reject-cookies">Rechazar</button>
        </div>
    
    </div> 
   

 <script>
        // JavaScript para manejar el banner de cookies
        document.addEventListener("DOMContentLoaded", function () {
            const cookieBanner = document.getElementById("cookie-banner");
            const acceptCookies = document.getElementById("accept-cookies");
            const rejectCookies = document.getElementById("reject-cookies");

            // Mostrar el banner si no se ha guardado la preferencia
            if (!localStorage.getItem("cookiesAccepted")) {
                cookieBanner.style.display = "block";
            }

            // Manejar clic en "Aceptar"
            acceptCookies.addEventListener("click", function () {
                localStorage.setItem("cookiesAccepted", "true");
                cookieBanner.style.display = "none";
            });

            // Manejar clic en "Rechazar"
            rejectCookies.addEventListener("click", function () {
                localStorage.setItem("cookiesAccepted", "false");
                cookieBanner.style.display = "none";
            });
        });
    </script>
    <footer>
        <p>&copy; 2024 Reposteria Dargel.</p>
    </footer>

</body>

</html>