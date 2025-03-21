<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
       
        .form-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .input-group {
            width: 300px;
            margin-bottom: 10px;
        }
        .input-group-prepend {
            min-width: 120px;
            text-align: right;
        }
        .form-control {
            width: 180px;
        }
        .btn__submit {
            margin-top: 10px;
        }
    </style>
    <script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-VX18B9GBD3');
</script>
</head>

<body>
    <?php
    require 'db_connect.php'; 

    error_reporting(0);

    $id = $_GET["id"];

    // Obtener datos del usuario
    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    if ($usuario) {
        $nombres = $usuario["nombres"];
        $apellidos = $usuario["apellidos"];
        $fecha_nacimiento = $usuario["fecha_nacimiento"];
        $username = $usuario["username"];
        $telefono = $usuario["telefono"];
        $correo = $usuario["correo"];
        $password = $usuario["password"];
        $es_admin = $usuario["es_admin"];
    ?>

    <center><h3>Editar Usuario</h3></center>

    <center>
        <form action="" method="post">
            <label>ID Usuario:</label><br>
            <input type="text" name="txtId" disabled value="<?php echo htmlspecialchars($id); ?>"><br>
            <label>Apellidos:</label><br>
            <input type="text" name="txtApellidos" value="<?php echo htmlspecialchars($apellidos); ?>"><br>
            <label>Nombres:</label><br>
            <input type="text" name="txtNombres" value="<?php echo htmlspecialchars($nombres); ?>"><br>
            <label>Fecha de Nacimiento:</label><br>
            <input type="text" name="txtFechaNacimiento" value="<?php echo htmlspecialchars($fecha_nacimiento); ?>"><br>
            <label>Username:</label><br>
            <input type="text" name="txtUsername" value="<?php echo htmlspecialchars($username); ?>"><br>
            <label>Correo:</label><br>
            <input type="text" name="txtCorreo" value="<?php echo htmlspecialchars($correo); ?>"><br>
            <label>Telefono:</label><br>
            <input type="text" name="txtTelefono" value="<?php echo htmlspecialchars($telefono); ?>"><br>
            <label>Contraseña:</label><br>
            <input type="password" name="txtPassword" value="<?php echo htmlspecialchars($password); ?>"><br>
            <label>Es Admin:</label><br>
            <input type="text" name="txtEsAdmin" value="<?php echo htmlspecialchars($es_admin); ?>"><br><br>
            <button class="btn btn-success" type="submit">Guardar cambios</button><br><br>
            <a href="admin_interface.php" class="btn btn-secondary">Regresar</a>
        </form>
    </center>

    <?php
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (
            isset($_POST["txtApellidos"]) &&
            isset($_POST["txtNombres"]) &&
            isset($_POST["txtFechaNacimiento"]) &&
            isset($_POST["txtUsername"]) &&
            isset($_POST["txtCorreo"]) &&
            isset($_POST["txtPassword"]) &&
            isset($_POST["txtEsAdmin"]) && isset($_POST["txtTelefono"])
        ) {
            $apellidos = $_POST["txtApellidos"];
            $nombres = $_POST["txtNombres"];
            $fecha_nacimiento = $_POST["txtFechaNacimiento"];
            $username = $_POST["txtUsername"];
            $correo = $_POST["txtCorreo"];
            $password = password_hash($_POST["txtPassword"], PASSWORD_DEFAULT);
            $es_admin = $_POST["txtEsAdmin"];
            $telefono = $_POST["txtTelefono"];

            $stmt = $conexion->prepare("UPDATE usuarios SET
                apellidos=?, 
                nombres=?, 
                fecha_nacimiento=?, 
                username=?, 
                correo=?, 
                password=?, 
                es_admin=?, telefono=?
                WHERE id=?");

            $stmt->bind_param("ssssssisi", $apellidos, $nombres, $fecha_nacimiento, $username, $correo, $password, $es_admin, $telefono,$id);

            $stmt->execute();

            echo "<script>alert('Usuario editado exitosamente'); window.location.href = 'admin_interface.php';</script>";
            exit();
        }
    }
    ?>
</body>
</html>
