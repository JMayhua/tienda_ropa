<?php
// aplicacion/controladores/ControladorPedidos.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../configuracion/config.php';

class ControladorPedidos {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    // Método para ver todos los pedidos del usuario
    public function verPedidos() {
        // Verificar si el usuario ha iniciado sesión
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /Tienda_ropa/publico/index.php?accion=iniciar_sesion');
            exit;
        }

        $usuarioId = $_SESSION['usuario_id'];

        // Obtener los pedidos del usuario
        $pedidos = $this->obtenerPedidosPorUsuario($usuarioId);

        // Cargar la vista de lista de pedidos
        require_once __DIR__ . '/../vistas/carrito/pedidos.php';
    }

    // Método para ver los detalles de un pedido específico
    public function verDetallePedido() {
        if (!isset($_GET['id']) || !isset($_SESSION['usuario_id'])) {
            header('Location: /Tienda_ropa/publico/index.php?accion=ver_pedidos');
            exit;
        }

        $pedidoId = $_GET['id'];
        $usuarioId = $_SESSION['usuario_id'];

        // Obtener detalles del pedido
        $pedido = $this->obtenerPedido($pedidoId, $usuarioId);
        if (!$pedido) {
            $_SESSION['error'] = "No se encontró el pedido o no tienes permiso para verlo.";
            header('Location: /Tienda_ropa/publico/index.php?accion=ver_pedidos');
            exit;
        }

        // Obtener los productos del pedido
        $detallesPedido = $this->obtenerDetallesPedido($pedidoId);

        // Cargar la vista de detalles del pedido
        require_once __DIR__ . '/../vistas/carrito/detalle_pedido.php';
    }

    // Método para obtener todos los pedidos de un usuario
    private function obtenerPedidosPorUsuario($usuarioId) {
        $query = "SELECT * FROM pedidos WHERE usuario_id = :usuario_id ORDER BY fecha DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':usuario_id' => $usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener un pedido por ID y usuario
    private function obtenerPedido($pedidoId, $usuarioId) {
        $query = "SELECT * FROM pedidos WHERE id = :id AND usuario_id = :usuario_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $pedidoId, ':usuario_id' => $usuarioId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para obtener los detalles de un pedido
    private function obtenerDetallesPedido($pedidoId) {
        $query = "SELECT dp.*, p.nombre, p.imagen
                  FROM detalles_pedido dp 
                  JOIN productos p ON dp.producto_id = p.id 
                  WHERE dp.pedido_id = :pedido_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':pedido_id' => $pedidoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para cancelar un pedido (opcional)
    public function cancelarPedido() {
        if (!isset($_GET['id']) || !isset($_SESSION['usuario_id'])) {
            header('Location: /Tienda_ropa/publico/index.php?accion=ver_pedidos');
            exit;
        }

        $pedidoId = $_GET['id'];
        $usuarioId = $_SESSION['usuario_id'];

        // Verificar que el pedido existe y pertenece al usuario
        $pedido = $this->obtenerPedido($pedidoId, $usuarioId);
        if (!$pedido) {
            $_SESSION['error'] = "No se encontró el pedido o no tienes permiso para cancelarlo.";
            header('Location: /Tienda_ropa/publico/index.php?accion=ver_pedidos');
            exit;
        }

        // Verificar que el pedido esté en un estado que permita cancelación
        if ($pedido['estado'] != 'pendiente') {
            $_SESSION['error'] = "No se puede cancelar un pedido que no esté en estado pendiente.";
            header('Location: /Tienda_ropa/publico/index.php?accion=ver_detalle_pedido&id=' . $pedidoId);
            exit;
        }

        // Actualizar el estado del pedido a 'cancelado'
        $query = "UPDATE pedidos SET estado = 'cancelado' WHERE id = :id AND usuario_id = :usuario_id";
        $stmt = $this->db->prepare($query);
        $resultado = $stmt->execute([':id' => $pedidoId, ':usuario_id' => $usuarioId]);

        if ($resultado) {
            $_SESSION['mensaje'] = "El pedido ha sido cancelado correctamente.";
        } else {
            $_SESSION['error'] = "Error al cancelar el pedido.";
        }

        header('Location: /Tienda_ropa/publico/index.php?accion=ver_pedidos');
        exit;
    }
}