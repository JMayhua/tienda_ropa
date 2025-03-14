<?php
$password = 'Admin'; // Cambia esto por la contraseña que quieras usar
$hash = password_hash($password, PASSWORD_DEFAULT);
echo $hash; // Copia este hash y úsalo en la base de datos
?>