<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Editar Usuario</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <style>
            /* Estilos CSS aquí */
            body {
                background: linear-gradient(to bottom right, #F8F8FF, #01d063);
            }

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
    </head>

    <?php
include 'DB_Datos.php';
error_reporting(0);

$id = $_GET["id"];

$stmt = $dbh->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($usuarios as $usuario) {
    $nombres = $usuario["nombres"];
    $apellidos = $usuario["apellidos"];
    $fecha_nacimiento = $usuario["fecha_nacimiento"];
    $username = $usuario["username"];
    $correo = $usuario["correo"];
    $password = $usuario["password"];
    $es_admin = $usuario["es_admin"];
    ?>

    <link href="estilo/estilo_1.css" rel="stylesheet" type="text/css"/>
    <style>
        /* Estilos CSS aquí */
    </style>
    <center><h3>Editar Usuario</h3></center>
    
    <center>
        <form action="" method="post">
            <label>ID Usuario:</label><br>
            <input type="text" name="txtId" disabled value="<?php echo $id; ?>"><br>
            <label>Apellidos:</label><br>
            <input type="text" name="txtApellidos" value="<?php echo $apellidos; ?>"><br>
            <label>Nombres:</label><br>
            <input type="text" name="txtNombres" value="<?php echo $nombres; ?>"><br>
            <label>Fecha de Nacimiento:</label><br>
            <input type="text" name="txtFechaNacimiento" value="<?php echo $fecha_nacimiento; ?>"><br>
            <label>Username:</label><br>
            <input type="text" name="txtUsername" value="<?php echo $username; ?>"><br>
            <label>Correo:</label><br>
            <input type="text" name="txtCorreo" value="<?php echo $correo; ?>"><br>
            <label>Contraseña:</label><br>
            <input type="password" name="txtPassword" value="<?php echo $password; ?>"><br>
            <label>Es Admin:</label><br>
            <input type="text" name="txtEsAdmin" value="<?php echo $es_admin; ?>"><br><br>
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
        isset($_POST["txtEsAdmin"])
    ) {
        $apellidos = $_POST["txtApellidos"];
        $nombres = $_POST["txtNombres"];
        $fecha_nacimiento = $_POST["txtFechaNacimiento"];
        $username = $_POST["txtUsername"];
        $correo = $_POST["txtCorreo"];
        $password = password_hash($_POST["txtPassword"], PASSWORD_DEFAULT);
        $es_admin = $_POST["txtEsAdmin"];

        $stmt = $dbh->prepare("UPDATE usuarios SET
            apellidos=?, 
            nombres=?, 
            fecha_nacimiento=?, 
            username=?, 
            correo=?, 
            password=?, 
            es_admin=?
            WHERE id=?");

        $stmt->bindParam(1, $apellidos);
        $stmt->bindParam(2, $nombres);
        $stmt->bindParam(3, $fecha_nacimiento);
        $stmt->bindParam(4, $username);
        $stmt->bindParam(5, $correo);
        $stmt->bindParam(6, $password);
        $stmt->bindParam(7, $es_admin);
        $stmt->bindParam(8, $id);

        $stmt->execute();
        echo "<script>alert('Usuario editado exitosamente'); window.location.href = 'admin_interface.php';</script>";

        exit();
    }
}
?>