<?php
// aplicacion/controladores/ControladorAdmin.php
require_once __DIR__ . '/../modelos/ModeloProductos.php';
require_once __DIR__ . '/../modelos/ModeloPedidos.php';
require_once __DIR__ . '/../modelos/ModeloUsuarios.php';

class ControladorAdmin {
    private $modeloProductos;
    private $modeloPedidos;
    private $modeloUsuarios;

    public function __construct() {
        $this->modeloProductos = new ModeloProductos();
        $this->modeloPedidos = new ModeloPedidos();
        $this->modeloUsuarios = new ModeloUsuarios();
    }

    // Verificar si el usuario es administrador
    private function verificarAdmin() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
            header('Location: /Tienda_ropa/publico/index.php');
            exit();
        }
    }

    // Mostrar el panel de administración
    public function panel() {
        $this->verificarAdmin();

        // Obtener datos para el resumen
        $totalProductos = $this->modeloProductos->contarProductos();
        $totalPedidos = $this->modeloPedidos->contarPedidos();
        $totalUsuarios = $this->modeloUsuarios->contarUsuarios();

        require_once __DIR__ . '/../vistas/admin/panel.php';
    }

    // Listar productos (para el enlace "Gestionar Productos")
    public function listarProductos() {
        $this->verificarAdmin();

        $productos = $this->modeloProductos->obtenerProductos();
        require_once __DIR__ . '/../vistas/admin/gestionar_productos.php';
    }

    // Listar pedidos (para el enlace "Ver Pedidos")
    public function listarPedidos() {
        $this->verificarAdmin();
    
        // Obtener la lista de pedidos
        $pedidos = $this->modeloPedidos->obtenerPedidos();
    
        // Cargar la vista
        require_once __DIR__ . '/../vistas/admin/listar_pedidos.php';
    }
    // Listar usuarios (para el enlace "Gestionar Usuarios")
    public function listarUsuarios() {
        $this->verificarAdmin();

        $usuarios = $this->modeloUsuarios->obtenerUsuarios();
        require_once __DIR__ . '/../vistas/admin/listar_usuarios.php';
    }

    // Añadir un nuevo producto
    public function añadirProducto() {
        $this->verificarAdmin();
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar y sanitizar los datos del formulario
            $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
            $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);
            $precio = filter_input(INPUT_POST, 'precio', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $categoria = filter_input(INPUT_POST, 'categoria', FILTER_SANITIZE_STRING);
            $talla = filter_input(INPUT_POST, 'talla', FILTER_SANITIZE_STRING);
            $color = filter_input(INPUT_POST, 'color', FILTER_SANITIZE_STRING);
            $stock = filter_input(INPUT_POST, 'stock', FILTER_SANITIZE_NUMBER_INT);
            $descuento = filter_input(INPUT_POST, 'descuento', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    
            // Procesar la subida de la imagen
            $imagen = '';
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $rutaDestino = __DIR__ . '/../publico/imagenes/productos/' . basename($_FILES['imagen']['name']);
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
                    $imagen = basename($_FILES['imagen']['name']);
                } else {
                    $_SESSION['error'] = "Error al subir la imagen.";
                    header('Location: /Tienda_ropa/publico/index.php?accion=añadir_producto');
                    exit();
                }
            }
    
            // Añadir el producto si los datos son válidos
            if ($nombre && $descripcion && $precio && $categoria && $talla && $color && $stock) {
                if ($this->modeloProductos->añadirProducto($nombre, $descripcion, $precio, $categoria, $talla, $color, $imagen, $stock, $descuento)) {
                    $_SESSION['mensaje'] = "Producto añadido correctamente.";
                    header('Location: /Tienda_ropa/publico/index.php?accion=listar_productos');
                    exit();
                } else {
                    $_SESSION['error'] = "Error al añadir el producto.";
                    header('Location: /Tienda_ropa/publico/index.php?accion=añadir_producto');
                    exit();
                }
            } else {
                $_SESSION['error'] = "Datos del formulario no válidos.";
                header('Location: /Tienda_ropa/publico/index.php?accion=añadir_producto');
                exit();
            }
        }
    
        require_once __DIR__ . '/../vistas/admin/productos/añadir.php';
    }

    // Editar un producto existente
    public function editarProducto($id) {
        $this->verificarAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar y sanitizar los datos del formulario
            $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
            $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);
            $precio = filter_input(INPUT_POST, 'precio', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $categoria = filter_input(INPUT_POST, 'categoria', FILTER_SANITIZE_STRING);
            $talla = filter_input(INPUT_POST, 'talla', FILTER_SANITIZE_STRING);
            $color = filter_input(INPUT_POST, 'color', FILTER_SANITIZE_STRING);
            $stock = filter_input(INPUT_POST, 'stock', FILTER_SANITIZE_NUMBER_INT);

            // Procesar la subida de la imagen
            $imagen = '';
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $rutaDestino = __DIR__ . '/../publico/imagenes/productos/' . basename($_FILES['imagen']['name']);
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
                    $imagen = basename($_FILES['imagen']['name']);
                } else {
                    $_SESSION['error'] = "Error al subir la imagen.";
                    header('Location: /Tienda_ropa/publico/index.php?accion=editar_producto&id=' . $id);
                    exit();
                }
            }

            // Actualizar el producto si los datos son válidos
            if ($nombre && $descripcion && $precio && $categoria && $talla && $color && $stock) {
                if ($this->modeloProductos->actualizarProducto($id, $nombre, $descripcion, $precio, $categoria, $talla, $color, $imagen, $stock)) {
                    $_SESSION['mensaje'] = "Producto actualizado correctamente.";
                    header('Location: /Tienda_ropa/publico/index.php?accion=listar_productos');
                    exit();
                } else {
                    $_SESSION['error'] = "Error al actualizar el producto.";
                    header('Location: /Tienda_ropa/publico/index.php?accion=editar_producto&id=' . $id);
                    exit();
                }
            } else {
                $_SESSION['error'] = "Datos del formulario no válidos.";
                header('Location: /Tienda_ropa/publico/index.php?accion=editar_producto&id=' . $id);
                exit();
            }
        }

        $producto = $this->modeloProductos->obtenerProductoPorId($id);
        require_once __DIR__ . '/../vistas/admin/productos/editar.php';
    }

    // Eliminar un producto
    public function eliminarProducto($id) {
        $this->verificarAdmin();

        if ($this->modeloProductos->eliminarProducto($id)) {
            $_SESSION['mensaje'] = "Producto eliminado correctamente.";
        } else {
            $_SESSION['error'] = "Error al eliminar el producto.";
        }
        header('Location: /Tienda_ropa/publico/index.php?accion=listar_productos');
        exit();
    }

    // Ver detalles de un pedido
    public function verPedido($id) {
        $this->verificarAdmin();
    
        // Obtener los detalles del pedido
        $pedido = $this->modeloPedidos->obtenerDetallesPedido($id);
    
        // Verificar si el pedido existe
        if (!$pedido) {
            $_SESSION['error'] = "El pedido no existe.";
            header('Location: /Tienda_ropa/publico/index.php?accion=listar_pedidos');
            exit();
        }
    
        // Obtener los productos asociados al pedido
        $detallesPedido = $this->modeloPedidos->obtenerProductosPedido($id);
    
        // Pasar los datos a la vista
        require_once __DIR__ . '/../vistas/admin/ver.php';
    }

    // Cambiar el estado de un pedido
    public function cambiarEstadoPedido($id) {
        $this->verificarAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $estado = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_STRING);

            if ($this->modeloPedidos->actualizarEstadoPedido($id, $estado)) {
                $_SESSION['mensaje'] = "Estado del pedido actualizado correctamente.";
                header('Location: /Tienda_ropa/publico/index.php?accion=listar_pedidos');
                exit();
            } else {
                $_SESSION['error'] = "Error al actualizar el estado del pedido.";
                header('Location: /Tienda_ropa/publico/index.php?accion=cambiar_estado_pedido&id=' . $id);
                exit();
            }
        }

        $pedido = $this->modeloPedidos->obtenerDetallesPedido($id);
        require_once __DIR__ . '/../vistas/admin/cambiar_estado.php';
    }

    // Editar un usuario
    public function editarUsuario($id) {
        $this->verificarAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar y sanitizar los datos del formulario
            $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $rol = filter_input(INPUT_POST, 'rol', FILTER_SANITIZE_STRING);

            if ($nombre && $email && $rol) {
                if ($this->modeloUsuarios->actualizarUsuario($id, $nombre, $email, $rol)) {
                    $_SESSION['mensaje'] = "Usuario actualizado correctamente.";
                    header('Location: /Tienda_ropa/publico/index.php?accion=listar_usuarios');
                    exit();
                } else {
                    $_SESSION['error'] = "Error al actualizar el usuario.";
                    header('Location: /Tienda_ropa/publico/index.php?accion=editar_usuario&id=' . $id);
                    exit();
                }
            } else {
                $_SESSION['error'] = "Datos del formulario no válidos.";
                header('Location: /Tienda_ropa/publico/index.php?accion=editar_usuario&id=' . $id);
                exit();
            }
        }

        $usuario = $this->modeloUsuarios->obtenerUsuarioPorId($id);
        require_once __DIR__ . '/../vistas/admin/usuarios/editar.php';
    }

    // Eliminar un usuario
    public function eliminarUsuario($id) {
        $this->verificarAdmin();

        if ($this->modeloUsuarios->eliminarUsuario($id)) {
            $_SESSION['mensaje'] = "Usuario eliminado correctamente.";
        } else {
            $_SESSION['error'] = "Error al eliminar el usuario.";
        }
        header('Location: /Tienda_ropa/publico/index.php?accion=listar_usuarios');
        exit();
    }
    public function gestionarUsuarios() {
        $this->verificarAdmin();
    
        // Obtener la lista de usuarios
        $usuarios = $this->modeloUsuarios->obtenerUsuarios();
    
        // Cargar la vista
        require_once __DIR__ . '/../vistas/admin/gestionar_usuarios.php';
    }
}