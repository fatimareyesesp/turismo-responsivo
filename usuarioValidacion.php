<?php
session_start();
require 'db.php'; 

$email_enviado = $_POST['email'];
$clave_enviada = $_POST['passw'];

$sql = "SELECT * FROM usuarios WHERE email = :email";
$stmt = $pdo->prepare($sql);
$stmt->execute([':email' => $email_enviado]);


$usuario_db = $stmt->fetch();

if ($usuario_db && $usuario_db['clave'] == $clave_enviada) {
    

    $_SESSION['usuarioEmail'] = $usuario_db['email'];
    $_SESSION['rol'] = $usuario_db['rol'];           
    $_SESSION['nombre'] = $usuario_db['nombre'];     

    echo "<script>
            alert('¡Bienvenido " . $usuario_db['nombre'] . "!');
            window.location.href='panel.php';
          </script>";

} else {
    echo "<script>
            alert('Usuario no encontrado o contraseña incorrecta');
            window.location.href='login.php';
          </script>";
}
?>