<?php require_once __DIR__ . '/../plantillas/cabecera.php'; ?>

<link rel="stylesheet" href="/Tienda_ropa/publico/recursos/css/iniciar_sesion.css">

<div class="login-container">
    <h1>Iniciar Sesión</h1>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="error-message">
            <?php echo $_SESSION['error']; ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="success-message">
            <?php echo $_SESSION['mensaje']; ?>
            <?php unset($_SESSION['mensaje']); ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="/Tienda_ropa/publico/index.php?accion=iniciar_sesion">
        <label for="email">Correo Electrónico:</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Contraseña:</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Iniciar Sesión</button>
    </form>

    <p>¿No tienes una cuenta? <a href="/Tienda_ropa/publico/index.php?accion=registrarse">Regístrate aquí</a></p>
</div>

<?php require_once __DIR__ . '/../plantillas/pie.php'; ?>