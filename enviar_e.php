<?php
session_start(); // Iniciar la sesión
// require __DIR__ . '/vendor/autoload.php';


//   $email = $_SESSION['email'];
//   $codigo_descuento = $_SESSION['codigo_descuento'];


// $resend = Resend::client('re_DQ5fakwp_L2YgKLt4pxNgyxbFhyebZtqB');

// $resend->emails->send([
//   'from' => 'Reposteria Dargel <atencion@dargelreposteria.com>',
//   'to' => [$email],
//   'subject' => 'hello world',
//   'html' => '<strong>Obtuviste este cupón de descuento: </strong> ',
// ]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Enviar Correo</title>
  <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
</head>
<body>
  
</body>
</html>
<?php 
echo "<script>
Swal.fire({
    title: 'Registro Exitoso',
    text: 'Revisa tu bandeja de correo electrónico o en spam',
    icon: 'success',
    confirmButtonText: 'Aceptar'
}).then(function() {
    // Redirigir a la página después de que el usuario cierre la alerta
    window.location.href = 'index.php';
});
</script>
";

session_destroy();
?>