<?php
// aplicacion/controladores/ControladorCarrito.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../configuracion/config.php';

class ControladorCarrito {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    // Añadir un producto al carrito
    public function añadirAlCarrito($id) {
        // Verificar si el usuario ha iniciado sesión
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /Tienda_ropa/publico/index.php?accion=iniciar_sesion');
            exit;
        }
    
        // Inicializar el carrito si no existe
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
    
        // Agregar el producto al carrito
        if (isset($_SESSION['carrito'][$id])) {
            $_SESSION['carrito'][$id] += 1; // Incrementar la cantidad si el producto ya está en el carrito
        } else {
            $_SESSION['carrito'][$id] = 1; // Agregar el producto al carrito
        }
    
        // Redirigir al carrito
        header('Location: /Tienda_ropa/publico/index.php?accion=ver_carrito');
        exit;
    }

    // Ver el carrito
    public function verCarrito() {
        // Inicializar el carrito si no existe
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        // Obtener el carrito de la sesión
        $carrito = $_SESSION['carrito'];

        // Incluir el modelo de productos
        require_once __DIR__ . '/../modelos/ModeloProductos.php';
        $modeloProducto = new ModeloProductos();

        // Obtener los detalles de los productos en el carrito
        $productosCarrito = [];
        
        // Solo intentar obtener productos si el carrito no está vacío
        if (!empty($carrito)) {
            foreach ($carrito as $id => $cantidad) {
                $producto = $modeloProducto->obtenerPorId($id);
                if ($producto) {
                    $producto['cantidad'] = $cantidad;
                    $producto['subtotal'] = $producto['precio'] * $cantidad;
                    $productosCarrito[] = $producto;
                }
            }
        }

        // Pasar los productos a la vista
        require_once __DIR__ . '/../vistas/carrito/ver.php';
    }

    // Eliminar un producto del carrito
    public function eliminarDelCarrito($id) {
        // Verificar si el producto existe en el carrito
        if (isset($_SESSION['carrito'][$id])) {
            // Si la cantidad es mayor a 1, decrementar
            if ($_SESSION['carrito'][$id] > 1) {
                $_SESSION['carrito'][$id]--;
            } else {
                // Si la cantidad es 1, eliminar completamente el producto
                unset($_SESSION['carrito'][$id]);
            }
        }

        // Redirigir de vuelta al carrito
        header('Location: /Tienda_ropa/publico/index.php?accion=ver_carrito');
        exit;
    }

    // Vaciar el carrito
    public function vaciarCarrito() {
        $_SESSION['carrito'] = [];
        header('Location: /Tienda_ropa/publico/index.php?accion=ver_carrito');
        exit;
    }

    // Finalizar la compra (crear pedido y detalles_pedido)
    public function finalizarCompra() {
        // Verificar si el usuario ha iniciado sesión y tiene carrito
        if (!isset($_SESSION['usuario_id']) || empty($_SESSION['carrito'])) {
            header('Location: /Tienda_ropa/publico/index.php?accion=ver_carrito');
            exit;
        }
    
        // Incluir el modelo de productos
        require_once __DIR__ . '/../modelos/ModeloProductos.php';
        $modeloProducto = new ModeloProductos();
    
        // Calcular el total del pedido
        $total = 0;
        foreach ($_SESSION['carrito'] as $productoId => $cantidad) {
            $producto = $modeloProducto->obtenerPorId($productoId);
            if ($producto) {
                $total += $producto['precio'] * $cantidad;
            }
        }
    
        // Guardar el total en la sesión para la página de pago
        $_SESSION['total_pedido'] = $total;
    
        // Redirigir a la página de pago con Yape
        header('Location: /Tienda_ropa/publico/index.php?accion=pago_yape');
        exit;
    }

    // Método para crear pedido (he movido este código que estaba fuera de la clase)
    public function crearPedido() {
        // Incluir el modelo de productos
        require_once __DIR__ . '/../modelos/ModeloProductos.php';
        $modeloProducto = new ModeloProductos();
    
        // Crear el pedido
        $usuarioId = $_SESSION['usuario_id'];
        $fecha = date('Y-m-d H:i:s');
        $estado = 'pendiente';
        $total = 0;
        $comprobante = 'pedido_' . uniqid(); // Generar un comprobante único
    
        // Calcular el total del pedido
        foreach ($_SESSION['carrito'] as $productoId => $cantidad) {
            $producto = $modeloProducto->obtenerPorId($productoId);
            if ($producto) {
                $total += $producto['precio'] * $cantidad;
            }
        }
    
        // Insertar el pedido en la tabla `pedidos`
        $query = "INSERT INTO pedidos (usuario_id, fecha, total, estado, comprobante) VALUES (:usuario_id, :fecha, :total, :estado, :comprobante)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':usuario_id' => $usuarioId,
            ':fecha' => $fecha,
            ':total' => $total,
            ':estado' => $estado,
            ':comprobante' => $comprobante
        ]);
        $pedidoId = $this->db->lastInsertId(); // Obtener el ID del pedido recién creado
    
        // Crear los detalles del pedido
        foreach ($_SESSION['carrito'] as $productoId => $cantidad) {
            $producto = $modeloProducto->obtenerPorId($productoId);
            if ($producto) {
                $precioUnitario = $producto['precio'];
                $query = "INSERT INTO detalles_pedido (pedido_id, producto_id, cantidad, precio_unitario) VALUES (:pedido_id, :producto_id, :cantidad, :precio)";
                $stmt = $this->db->prepare($query);
                $stmt->execute([
                    ':pedido_id' => $pedidoId,
                    ':producto_id' => $productoId,
                    ':cantidad' => $cantidad,
                    ':precio_unitario' => $precioUnitario
                ]);
            }
        }
    
        // Vaciar el carrito
        $_SESSION['carrito'] = [];
    
        // Redirigir a una página de confirmación
        header('Location: /tienda_ropa/publico/index.php?accion=confirmacion_compra&id=' . $pedidoId);
        exit;
    }

    // Método para procesar las solicitudes
    public function procesarSolicitud() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['accion']) && $_POST['accion'] === 'añadir' && isset($_POST['id_producto'])) {
                $this->añadirAlCarrito($_POST['id_producto']);
            } elseif (isset($_POST['accion']) && $_POST['accion'] === 'finalizar_compra') {
                $this->finalizarCompra();
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (isset($_GET['accion']) && $_GET['accion'] === 'ver_carrito') {
                $this->verCarrito();
            } elseif (isset($_GET['accion']) && $_GET['accion'] === 'vaciar_carrito') {
                $this->vaciarCarrito();
            } elseif (isset($_GET['accion']) && $_GET['accion'] === 'eliminar_del_carrito' && isset($_GET['id'])) {
                $this->eliminarDelCarrito($_GET['id']);
            }
        }
    }
}

// Código fuera de la clase para procesar la solicitud
$controlador = new ControladorCarrito();
$controlador->procesarSolicitud();