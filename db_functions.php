<?php

// Conectar a la base de datos
function conectarDB()
{
    $conexion = new mysqli("localhost", "root", "123456", "nutricode");

    if ($conexion->connect_error) {
        die("La conexión a la base de datos falló: " . $conexion->connect_error);
    }

    return $conexion;
}

// Obtener información de un artículo por su ID
function obtenerInformacionArticulo($articuloId)
{
    $conexion = conectarDB();
    $sql = "SELECT * FROM articulos WHERE id = $articuloId";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {
        $articulo = $resultado->fetch_assoc();
        $conexion->close();
        return $articulo;
    } else {
        $conexion->close();
        return null;
    }
}

// Obtener lista de artículos
function obtenerListaArticulos()
{
    $conexion = conectarDB();
    $sql = "SELECT * FROM articulos";
    $resultado = $conexion->query($sql);

    $articulos = [];

    if ($resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            $articulos[] = $fila;
        }
    }

    $conexion->close();
    return $articulos;
}

// Agregar un artículo al carrito del usuario actual
function agregarAlCarrito($articulo)
{
    // Iniciar o recuperar la sesión
    session_start();

    // Verificar si existe la clave "carrito" en la sesión
    if (!isset($_SESSION["carrito"])) {
        $_SESSION["carrito"] = [];
    }

    // Agregar el artículo al carrito
    $_SESSION["carrito"][] = $articulo;
}
