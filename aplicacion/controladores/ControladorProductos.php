<?php
// aplicacion/controladores/ControladorProductos.php
require_once __DIR__ . '/../modelos/ModeloProductos.php';

class ControladorProductos {
    private $modelo;

    public function __construct() {
        $this->modelo = new ModeloProductos();
    }

    // Mostrar todos los productos
    public function listar() {
        $productos = $this->modelo->obtenerProductos();
        require_once '../vistas/productos/index.php';
    }
    public function catalogo() {
        $productos = $this->modelo->obtenerProductos();
       require_once __DIR__ . '/../vistas/productos/catalogo.php';
    }

    // Mostrar formulario para añadir un producto
    public function añadir() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            $precio = $_POST['precio'];
            $categoria = $_POST['categoria'];
            $talla = $_POST['talla'];
            $color = $_POST['color'];
            $imagen = $_POST['imagen']; // Aquí podrías implementar la subida de imágenes
            $stock = $_POST['stock'];

            if ($this->modelo->añadirProducto($nombre, $descripcion, $precio, $categoria, $talla, $color, $imagen, $stock)) {
                header('Location: /tienda_ropa/publico/index.php?accion=listar_productos');
            } else {
                echo "Error al añadir el producto.";
            }
        }
        require_once '../vistas/admin/productos/añadir.php';
    }

    // Mostrar formulario para editar un producto
    public function editar($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            $precio = $_POST['precio'];
            $categoria = $_POST['categoria'];
            $talla = $_POST['talla'];
            $color = $_POST['color'];
            $imagen = $_POST['imagen'];
            $stock = $_POST['stock'];

            if ($this->modelo->actualizarProducto($id, $nombre, $descripcion, $precio, $categoria, $talla, $color, $imagen, $stock)) {
                header('Location: /tienda_ropa/publico/index.php?accion=listar_productos');
            } else {
                echo "Error al actualizar el producto.";
            }
        }
        $producto = $this->modelo->obtenerProductoPorId($id);
        require_once '../vistas/admin/productos/editar.php';
    }

    // Eliminar un producto
    public function eliminar($id) {
        if ($this->modelo->eliminarProducto($id)) {
            header('Location: /tienda_ropa/publico/index.php?accion=listar_productos');
        } else {
            echo "Error al eliminar el producto.";
        }
    }
}