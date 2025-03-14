<?php
// Verificar el estado de la sesión
if (session_status() !== PHP_SESSION_ACTIVE) {
    // Configurar el nombre de la sesión basado en las cookies
    if (isset($_COOKIE['admin_session'])) {
        session_name('admin_session');
    } elseif (isset($_COOKIE['cliente_session'])) {
        session_name('cliente_session');
    } else {
        session_name('default_session');
    }
    // Iniciar la sesión
    session_start();
}

// Verificar el rol del usuario
$is_admin = isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
$is_cliente = isset($_SESSION['rol']) && $_SESSION['rol'] === 'cliente';
$usuario_autenticado = isset($_SESSION['usuario']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda de Ropa</title>
    <link rel="stylesheet" href="/Tienda_ropa/publico/recursos/css/estilos.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <header class="cabecera">
        <div class="contenedor">
            <h1 class="logo">Tienda de Ropa</h1>
            <nav class="navegacion">
                <!-- Enlace de Inicio (común para todos) -->
                <a href="/Tienda_ropa/publico/index.php" class="nav-link">Inicio</a>

                <?php if ($usuario_autenticado): ?>
                    <!-- Mostrar el carrito solo para clientes -->
                    <?php if ($is_cliente): ?>
                        <a href="/Tienda_ropa/publico/index.php?accion=ver_carrito" class="nav-link">Carrito 🛒</a>
                    <?php endif; ?>

                    <!-- Enlaces exclusivos para administradores -->
                    <?php if ($is_admin): ?>
                        <a href="/Tienda_ropa/publico/index.php?accion=panel_admin" class="nav-link">Panel del Administrador</a>
                        <a href="/Tienda_ropa/publico/index.php?accion=listar_productos" class="nav-link">Gestionar Productos</a>
                        <a href="/Tienda_ropa/publico/index.php?accion=gestionar_usuarios" class="nav-link">Gestionar Usuarios</a>
                        <a href="/Tienda_ropa/publico/index.php?accion=listar_pedidos" class="nav-link">Ver Pedidos</a>
                    <?php endif; ?>

                    <!-- Enlaces exclusivos para clientes -->
                    <?php if ($is_cliente): ?>
                        <a href="/Tienda_ropa/publico/index.php?accion=ver_pedidos" class="nav-link">Mis Pedidos</a>
                        <a href="/Tienda_ropa/publico/index.php?accion=ver_perfil" class="nav-link">Mi Perfil</a>
                    <?php endif; ?>

                    <!-- Enlace para cerrar sesión -->
                    <a href="/Tienda_ropa/publico/index.php?accion=cerrar_sesion" class="nav-link">Cerrar Sesión</a>

                <?php else: ?>
                    <!-- Enlaces para usuarios no autenticados -->
                    <a href="/Tienda_ropa/publico/index.php?accion=iniciar_sesion" class="nav-link">Iniciar Sesión</a>
                    <a href="/Tienda_ropa/publico/index.php?accion=registrarse" class="nav-link">Registrarse</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main>