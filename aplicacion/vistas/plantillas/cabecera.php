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
    <title>Tienda ApliDig</title>
    <link rel="stylesheet" href="/Tienda_ropa/publico/recursos/css/estilos.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="cabecera">
        <div class="contenedor-cabecera">
            <!-- Logo e imagen a la izquierda -->
            <div class="logo-section">
                <h1 class="logo">Tienda ApliDig</h1>
                <div class="logo-image-container">
                    <img src="/Tienda_ropa/publico/recursos/imagenes/logo.jpg" alt="Logo" class="logo-image">
                </div>
            </div>
            
            <!-- Navegación a la derecha -->
            <nav class="navegacion">
                <?php if ($usuario_autenticado): ?>
                    <!-- Enlace de Inicio (común para todos) -->
                    <div class="nav-button">
                        <a href="/Tienda_ropa/publico/index.php" class="nav-link">
                            <span>Inicio</span>
                            <div class="border-animation"></div>
                        </a>
                    </div>
                    
                    <!-- Enlaces exclusivos para clientes -->
                    <?php if ($is_cliente): ?>
                        <div class="nav-button">
                            <a href="/Tienda_ropa/publico/index.php?accion=ver_carrito" class="nav-link">
                                <span>Carrito 🛒</span>
                                <div class="border-animation"></div>
                            </a>
                        </div>
                        <div class="nav-button">
                            <a href="/Tienda_ropa/publico/index.php?accion=ver_pedidos" class="nav-link">
                                <span>Mis Pedidos</span>
                                <div class="border-animation"></div>
                            </a>
                        </div>
                        <div class="nav-button">
                            <a href="/Tienda_ropa/publico/index.php?accion=ver_perfil" class="nav-link">
                                <span>Mi Perfil</span>
                                <div class="border-animation"></div>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Enlaces exclusivos para administradores -->
                    <?php if ($is_admin): ?>
                        <div class="nav-button">
                            <a href="/Tienda_ropa/publico/index.php?accion=panel_admin" class="nav-link">
                                <span>Panel del Administrador</span>
                                <div class="border-animation"></div>
                            </a>
                        </div>
                        <div class="nav-button">
                            <a href="/Tienda_ropa/publico/index.php?accion=listar_productos" class="nav-link">
                                <span>Gestionar Productos</span>
                                <div class="border-animation"></div>
                            </a>
                        </div>
                        <div class="nav-button">
                            <a href="/Tienda_ropa/publico/index.php?accion=gestionar_usuarios" class="nav-link">
                                <span>Gestionar Usuarios</span>
                                <div class="border-animation"></div>
                            </a>
                        </div>
                        <div class="nav-button">
                            <a href="/Tienda_ropa/publico/index.php?accion=listar_pedidos" class="nav-link">
                                <span>Ver Pedidos</span>
                                <div class="border-animation"></div>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Enlace para cerrar sesión -->
                    <div class="nav-button">
                        <a href="/Tienda_ropa/publico/index.php?accion=cerrar_sesion" class="nav-link">
                            <span>Cerrar Sesión</span>
                            <div class="border-animation"></div>
                        </a>
                    </div>
                    
                <?php else: ?>
                    <!-- Enlaces para usuarios no autenticados -->
                    <div class="nav-button">
                        <a href="/Tienda_ropa/publico/index.php" class="nav-link">
                            <span>Inicio</span>
                            <div class="border-animation"></div>
                        </a>
                    </div>
                    <div class="nav-button">
                        <a href="/Tienda_ropa/aplicacion/vistas/productos/nosotros.php" class="nav-link">
                            <span>Nosotros</span>
                            <div class="border-animation"></div>
                        </a>
                    </div>
                    
                    <div class="nav-button">
                        <a href="/Tienda_ropa/publico/index.php?accion=iniciar_sesion" class="nav-link">
                            <span>Iniciar Sesión</span>
                            <div class="border-animation"></div>
                        </a>
                    </div>
                    
                    <div class="nav-button">
                        <a href="/Tienda_ropa/publico/index.php?accion=registrarse" class="nav-link">
                            <span>Registrarse</span>
                            <div class="border-animation"></div>
                        </a>
                    </div>
                <?php endif; ?>
            </nav>
        </div>
    </header>