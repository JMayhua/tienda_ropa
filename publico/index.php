<?php
// publico/index.php

// Habilitar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir la configuración
require_once __DIR__ . '/../configuracion/config.php';

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir los controladores
require_once __DIR__ . '/../aplicacion/controladores/ControladorAutenticacion.php';
require_once __DIR__ . '/../aplicacion/controladores/ControladorProductos.php';
require_once __DIR__ . '/../aplicacion/controladores/ControladorCarrito.php';
require_once __DIR__ . '/../aplicacion/controladores/ControladorPedidos.php';
require_once __DIR__ . '/../aplicacion/controladores/ControladorPagos.php';
require_once __DIR__ . '/../aplicacion/controladores/ControladorAdmin.php';
require_once __DIR__ . '/../aplicacion/controladores/ControladorPerfil.php'; // Nuevo controlador de perfil

// Obtener la acción desde la URL
$accion = $_GET['accion'] ?? 'inicio';

// Manejar la acción correspondiente
switch ($accion) {
    // Autenticación
    case 'iniciar_sesion':
        $controlador = new ControladorAutenticacion();
        $controlador->iniciarSesion();
        break;
    case 'registrarse':
        $controlador = new ControladorAutenticacion();
        $controlador->registrarse();
        break;
    case 'registrar_admin':
        $controlador = new ControladorAutenticacion();
        $controlador->registrarAdmin();
        break;
    case 'cerrar_sesion':
        $controlador = new ControladorAutenticacion();
        $controlador->cerrarSesion();
        break;

    // Productos
    case 'catalogo':
        $controlador = new ControladorProductos();
        $controlador->catalogo();
        break;
    case 'mostrar_producto':
        $controlador = new ControladorProductos();
        $controlador->mostrarProducto($_GET['id']);
        break;

    // Carrito de compras
    case 'añadir_al_carrito':
        $controlador = new ControladorCarrito();
        $controlador->añadirAlCarrito($_GET['id']);
        break;
    case 'ver_carrito':
        $controlador = new ControladorCarrito();
        $controlador->verCarrito();
        break;
    case 'eliminar_del_carrito':
        $controlador = new ControladorCarrito();
        $controlador->eliminarDelCarrito($_GET['id']);
        break;
    case 'vaciar_carrito':
        $controlador = new ControladorCarrito();
        $controlador->vaciarCarrito();
        break;

    // Pedidos
    case 'finalizar_compra':
        $controlador = new ControladorCarrito();
        $controlador->finalizarCompra();
        break;
    case 'ver_pedidos': // Nueva acción para ver la lista de pedidos
        $controlador = new ControladorPedidos();
        $controlador->verPedidos();
        break;
    case 'ver_detalles_pedido': // Nueva acción para ver los detalles de un pedido
        if (isset($_GET['id'])) {
            $controlador = new ControladorPedidos();
            $controlador->verDetallePedido($_GET['id']);
        } else {
            $_SESSION['error'] = "No se proporcionó un ID de pedido.";
            header('Location: /Tienda_ropa/publico/index.php?accion=ver_pedidos');
            exit;
        }
        break;

    // Pagos
    case 'pago_yape':
        $controlador = new ControladorPagos();
        $controlador->pagoYape();
        break;
    case 'procesar_pago_yape':
        $controlador = new ControladorPagos();
        $controlador->procesarPagoYape();
        break;

    // Panel de administración
    case 'panel_admin':
        $controlador = new ControladorAdmin();
        $controlador->panel();
        break;
    case 'listar_productos':
        $controlador = new ControladorAdmin();
        $controlador->listarProductos();
        break;
    case 'añadir_producto':
        $controlador = new ControladorAdmin();
        $controlador->añadirProducto();
        break;
    case 'editar_producto':
        $controlador = new ControladorAdmin();
        $controlador->editarProducto($_GET['id']);
        break;
    case 'eliminar_producto':
        $controlador = new ControladorAdmin();
        $controlador->eliminarProducto($_GET['id']);
        break;
    case 'listar_pedidos':
        $controlador = new ControladorAdmin();
        $controlador->listarPedidos();
        break;
    case 'ver_pedido_admin': // Para que el administrador vea los detalles de un pedido
        $controlador = new ControladorAdmin();
        $controlador->verPedido($_GET['id']);
        break;
    case 'cambiar_estado_pedido':
        $controlador = new ControladorAdmin();
        $controlador->cambiarEstadoPedido($_GET['id']);
        break;
    case 'listar_usuarios':
        $controlador = new ControladorAdmin();
        $controlador->listarUsuarios();
        break;
    case 'editar_usuario':
        $controlador = new ControladorAdmin();
        $controlador->editarUsuario($_GET['id']);
        break;
    case 'eliminar_usuario':
        $controlador = new ControladorAdmin();
        $controlador->eliminarUsuario($_GET['id']);
        break;
        case 'gestionar_usuarios':
            $controlador = new ControladorAdmin();
            $controlador->gestionarUsuarios(); // Llamar al método correcto
            break;
    // Perfil del usuario
    case 'ver_perfil':
        $controlador = new ControladorPerfil();
        $controlador->verPerfil();
        break;
    case 'editar_perfil':
        $controlador = new ControladorPerfil();
        $controlador->editarPerfil();
        break;
    case 'actualizar_perfil':
        $controlador = new ControladorPerfil();
        $controlador->actualizarPerfil();
        break;

    // Página principal por defecto
    default:
        $controlador = new ControladorProductos();
        $controlador->catalogo();
        break;
}