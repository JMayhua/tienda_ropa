<?php
// aplicacion/controladores/ControladorPagos.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../configuracion/config.php';

class ControladorPagos {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    // Método para mostrar la página de pago con Yape
    public function pagoYape() {
        // Verificar si hay un total de pedido en la sesión
        if (!isset($_SESSION['total_pedido']) || !isset($_SESSION['usuario_id'])) {
            header('Location: /Tienda_ropa/publico/index.php?accion=ver_carrito');
            exit;
        }

        // Cargar la vista de pago con Yape
        $total = $_SESSION['total_pedido'];
        require_once __DIR__ . '/../vistas/carrito/pago_yape.php';
    }

    // Método para procesar el pago con Yape
    public function procesarPagoYape() {
        // Verificar si el usuario ha iniciado sesión y tiene carrito
        if (!isset($_SESSION['usuario_id']) || empty($_SESSION['carrito'])) {
            header('Location: /Tienda_ropa/publico/index.php?accion=ver_carrito');
            exit;
        }

        // Validar que se haya subido un comprobante
        if (!isset($_FILES['comprobante']) || $_FILES['comprobante']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error_pago'] = "Debes subir un comprobante de pago.";
            header('Location: /Tienda_ropa/publico/index.php?accion=pago_yape');
            exit;
        }

        // Validar el tipo de archivo (solo imágenes)
        $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif'];
        $tipoArchivo = $_FILES['comprobante']['type'];
        if (!in_array($tipoArchivo, $tiposPermitidos)) {
            $_SESSION['error_pago'] = "El archivo debe ser una imagen (JPEG, PNG o GIF).";
            header('Location: /Tienda_ropa/publico/index.php?accion=pago_yape');
            exit;
        }

        // Asegurarse de que el directorio existe
        $directorioComprobantes = __DIR__ . '/../../comprobantes/';
        if (!file_exists($directorioComprobantes)) {
            mkdir($directorioComprobantes, 0755, true);
        }

        // Guardar el comprobante en el servidor
        $nombreArchivo = uniqid() . '_' . basename($_FILES['comprobante']['name']);
        $rutaComprobante = $directorioComprobantes . $nombreArchivo;
        
        if (!move_uploaded_file($_FILES['comprobante']['tmp_name'], $rutaComprobante)) {
            $_SESSION['error_pago'] = "Hubo un error al subir el comprobante.";
            header('Location: /Tienda_ropa/publico/index.php?accion=pago_yape');
            exit;
        }

        try {
            // Crear el pedido
            $pedidoId = $this->crearPedido($nombreArchivo);
            
            // Redirigir a la página de detalles del pedido (usando el ControladorPedidos)
            header('Location: /Tienda_ropa/publico/index.php?accion=ver_pedidos&id=' . $pedidoId);
            exit;
        } catch (Exception $e) {
            $_SESSION['error_pago'] = "Error al procesar el pago: " . $e->getMessage();
            header('Location: /Tienda_ropa/publico/index.php?accion=pago_yape');
            exit;
        }
    }

    // Método para crear el pedido
    private function crearPedido($nombreComprobante) {
        // Verificar si el usuario tiene un carrito
        if (empty($_SESSION['carrito'])) {
            throw new Exception("El carrito está vacío.");
        }

        try {
            // Iniciar transacción
            $this->db->beginTransaction();
            
            // Incluir el modelo de productos
            require_once __DIR__ . '/../modelos/ModeloProductos.php';
            $modeloProducto = new ModeloProductos();

            // Datos del pedido
            $usuarioId = $_SESSION['usuario_id'];
            $fecha = date('Y-m-d H:i:s');
            $estado = 'pendiente'; // Cambiar a 'pagado' después de validar el comprobante
            $total = $_SESSION['total_pedido'];

            // Insertar pedido
            $query = "INSERT INTO pedidos (usuario_id, fecha, total, estado, comprobante) VALUES (:usuario_id, :fecha, :total, :estado, :comprobante)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':usuario_id' => $usuarioId,
                ':fecha' => $fecha,
                ':total' => $total,
                ':estado' => $estado,
                ':comprobante' => $nombreComprobante
            ]);
            $pedidoId = $this->db->lastInsertId();

            // Crear detalles del pedido
            foreach ($_SESSION['carrito'] as $productoId => $cantidad) {
                $producto = $modeloProducto->obtenerPorId($productoId);
                if ($producto) {
                    $precioUnitario = $producto['precio'];
                    $query = "INSERT INTO detalles_pedido (pedido_id, producto_id, cantidad, precio_unitario) VALUES (:pedido_id, :producto_id, :cantidad, :precio_unitario)";
                    $stmt = $this->db->prepare($query);
                    $stmt->execute([
                        ':pedido_id' => $pedidoId,
                        ':producto_id' => $productoId,
                        ':cantidad' => $cantidad,
                        ':precio_unitario' => $precioUnitario
                    ]);
                }
            }

            // Confirmar transacción
            $this->db->commit();

            // Limpiar carrito y total de sesión
            unset($_SESSION['carrito']);
            unset($_SESSION['total_pedido']);

            return $pedidoId;
        } catch (Exception $e) {
            // Si hay algún error, revertir la transacción
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }
}