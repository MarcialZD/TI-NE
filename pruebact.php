<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script>
        window.addEventListener('load', function() {
            var usuarioLogueado = <?php echo isset($_SESSION['username']) ? 'true' : 'false'; ?>;
            var nombreUsuario = "<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>";

            // Carga el script de Landbot
            !function(w,d,id){
                var s = d.createElement("script");
                s.src = "https://chat.landbot.io/landbot-embed.js";
                s.id = id;
                s.async = true;
                s.onload = function() {
                    window.landbot = new window.Landbot({
                        configUrl: 'https://storage.googleapis.com/landbot.online/v3/H-2660553-Z2LBECSVY9B4GDRX/index.json', // Reemplaza con tu URL de configuración
                        startPayload: usuarioLogueado ? { saludo: "Hola, " + nombreUsuario + "!" } : {}
                    });
                };
                d.body.appendChild(s);
            }(window, document, "landbot-embed");
        });
    </script>
    <script>
window.addEventListener('mouseover', initLandbot, { once: true });
window.addEventListener('touchstart', initLandbot, { once: true });
var myLandbot;
function initLandbot() {
  if (!myLandbot) {
    var s = document.createElement('script');s.type = 'text/javascript';s.async = true;
    s.addEventListener('load', function() {
      var myLandbot = new Landbot.Livechat({
        configUrl: 'https://storage.googleapis.com/landbot.online/v3/H-2660553-Z2LBECSVY9B4GDRX/index.json',
      });
    });
    s.src = 'https://cdn.landbot.io/landbot-3/landbot-3.0.0.js';
    var x = document.getElementsByTagName('script')[0];
    x.parentNode.insertBefore(s, x);
  }
}
</script>
</head>
<body>
    
</body>
</html>
