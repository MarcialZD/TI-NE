<?php 

include 'db_connect.php';

// Obtener los parámetros de la URL
$nombres = isset($_GET["nombres"]) ? $_GET["nombres"] : '';
$apellidos = isset($_GET["apellidos"]) ? $_GET["apellidos"] : '';
$fecha_nacimiento = isset($_GET["fecha_nacimiento"]) ? $_GET["fecha_nacimiento"] : '';
$username = isset($_GET["username"]) ? $_GET["username"] : '';
$correo = isset($_GET["correo"]) ? $_GET["correo"] : '';
$telefono = isset($_GET["telefono"]) ? $_GET["telefono"] : '';
$password = isset($_GET["password"]) ? password_hash($_GET["password"], PASSWORD_DEFAULT) : '';

// Verificar que los campos requeridos no estén vacíos
if (!empty($nombres) && !empty($apellidos) && !empty($username) && !empty($correo) && !empty($password)) {
    // Preparar la consulta SQL de forma segura
    $sql = $conexion->prepare("INSERT INTO usuarios (nombres, apellidos, fecha_nacimiento, username, correo, telefono, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    // Vincular los parámetros
    $sql->bind_param("sssssss", $nombres, $apellidos, $fecha_nacimiento, $username, $correo, $telefono, $password);

    // Ejecutar la consulta
    if ($sql->execute()) {
        echo "Registro exitoso";
    } else {
        echo "Error en el registro: " . $sql->error;
    }

    // Cerrar la declaración preparada
    $sql->close();
} else {
    echo "Por favor, completa todos los campos requeridos en la URL.";
}

// Cerrar la conexión
$conexion->close();







