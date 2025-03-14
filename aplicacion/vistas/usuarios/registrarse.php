<?php require_once __DIR__ . '/../plantillas/cabecera.php'; ?>
<link rel="stylesheet" href="/tienda_ropa/publico/recursos/css/iniciar_sesion.css">
<div class="login-container">
<h1>Registrarse</h1>
<form method="POST" action="">
    <label for="nombre">Nombre:</label>
    <input type="text" name="nombre" required>
    <br>
    <label for="email">Email:</label>
    <input type="email" name="email" required>
    <br>
    <label for="password">Contraseña:</label>
    <input type="password" name="password" required>  <!-- Cambiado a "password" -->
    <br>
    <button type="submit">Registrarse</button>
</form>
</div>
<?php require_once __DIR__ . '/../plantillas/pie.php'; ?>