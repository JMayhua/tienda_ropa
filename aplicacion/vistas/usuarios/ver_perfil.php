
<!-- aplicacion/vistas/perfil/ver_perfil.php -->
<?php require_once __DIR__ . '/../plantillas/cabecera.php'; ?>

<h1>Mi Perfil</h1>

<?php if (isset($_SESSION['exito'])): ?>
    <div class="exito"><?php echo $_SESSION['exito']; unset($_SESSION['exito']); ?></div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<?php if (isset($usuario)): ?>
    <p><strong>Nombre:</strong> <?php echo htmlspecialchars($usuario['nombre']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>

    <a href="/Tienda_ropa/publico/index.php?accion=editar_perfil">Editar Perfil</a>
<?php else: ?>
    <p>No se encontró la información del usuario.</p>
<?php endif; ?>



<?php require_once __DIR__ . '/../plantillas/pie.php'; ?>