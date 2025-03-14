<?php
// aplicacion/modelos/ModeloPedidos.php
require_once __DIR__ . '/../../configuracion/config.php';

class ModeloPedidos {
    private $db;

    public function __construct() {
        global $db; // Usar la conexión global
        $this->db = $db;
    }

    /**
     * Obtener todos los pedidos.
     *
     * @return array Lista de pedidos.
     */
    public function obtenerPedidos() {
        try {
            $query = "SELECT * FROM pedidos ORDER BY fecha DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener pedidos: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener los pedidos de un usuario específico.
     *
     * @param int $usuarioId ID del usuario.
     * @return array Lista de pedidos del usuario.
     */
    public function obtenerPedidosPorUsuario($usuarioId) {
        try {
            $query = "SELECT * FROM pedidos WHERE usuario_id = :usuario_id ORDER BY fecha DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':usuario_id' => $usuarioId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener pedidos del usuario: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener los detalles de un pedido.
     *
     * @param int $pedidoId ID del pedido.
     * @return array|false Detalles del pedido o false si no se encuentra.
     */
    public function obtenerDetallesPedido($pedidoId) {
        try {
            $query = "SELECT * FROM pedidos WHERE id = :pedido_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':pedido_id' => $pedidoId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener detalles del pedido: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener los productos de un pedido.
     *
     * @param int $pedidoId ID del pedido.
     * @return array Lista de productos del pedido.
     */
    public function obtenerProductosPedido($pedidoId) {
        try {
            $query = "SELECT p.nombre, dp.cantidad, dp.precio_unitario 
                      FROM detalles_pedido dp 
                      JOIN productos p ON dp.producto_id = p.id 
                      WHERE dp.pedido_id = :pedido_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':pedido_id' => $pedidoId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener productos del pedido: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Contar el número total de pedidos.
     *
     * @return int Número total de pedidos.
     */
    public function contarPedidos() {
        try {
            $query = "SELECT COUNT(*) AS total FROM pedidos";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado['total'];
        } catch (PDOException $e) {
            error_log("Error al contar pedidos: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Cambiar el estado de un pedido.
     *
     * @param int $id ID del pedido.
     * @param string $estado Nuevo estado del pedido.
     * @return bool True si se actualizó correctamente, false en caso contrario.
     */
    public function actualizarEstadoPedido($id, $estado) {
        try {
            $query = "UPDATE pedidos SET estado = :estado WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':id' => $id,
                ':estado' => $estado
            ]);
            return true;
        } catch (PDOException $e) {
            error_log("Error al actualizar el estado del pedido: " . $e->getMessage());
            return false;
        }
    }
}