<?php
// aplicacion/modelos/ModeloCarrito.php
class ModeloCarrito {
    public function __construct() {
        // Inicializar el carrito en la sesión si no existe
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
    }

    /**
     * Añadir un producto al carrito.
     *
     * @param int $id ID del producto.
     * @param int $cantidad Cantidad del producto (por defecto es 1).
     * @throws Exception Si el ID o la cantidad no son válidos.
     */
    public function añadirProducto($id, $cantidad = 1) {
        // Validar que el ID y la cantidad sean válidos
        if (!is_numeric($id) || $id <= 0) {
            throw new Exception("ID de producto no válido.");
        }
        if (!is_numeric($cantidad) || $cantidad <= 0) {
            throw new Exception("Cantidad no válida.");
        }

        // Añadir el producto al carrito
        if (isset($_SESSION['carrito'][$id])) {
            $_SESSION['carrito'][$id] += $cantidad;
        } else {
            $_SESSION['carrito'][$id] = $cantidad;
        }
    }

    /**
     * Eliminar un producto del carrito.
     *
     * @param int $id ID del producto.
     * @throws Exception Si el ID no es válido.
     */
    public function eliminarProducto($id) {
        // Validar que el ID sea válido
        if (!is_numeric($id) || $id <= 0) {
            throw new Exception("ID de producto no válido.");
        }

        // Eliminar el producto del carrito
        if (isset($_SESSION['carrito'][$id])) {
            unset($_SESSION['carrito'][$id]);
        }
    }

    /**
     * Obtener todos los productos del carrito.
     *
     * @return array Lista de productos en el carrito.
     */
    public function obtenerProductos() {
        return $_SESSION['carrito'];
    }

    /**
     * Vaciar el carrito.
     */
    public function vaciarCarrito() {
        $_SESSION['carrito'] = [];
    }
}