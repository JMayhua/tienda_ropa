<?php
// aplicacion/modelos/ModeloProductos.php
require_once __DIR__ . '/../../configuracion/config.php';

class ModeloProductos {
    private $conexion;

    public function __construct() {
        try {
            // Usar PDO para la conexión a la base de datos
            $this->conexion = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }

    /**
     * Contar el número total de productos.
     *
     * @return int Número total de productos.
     */
    public function contarProductos() {
        try {
            $query = "SELECT COUNT(*) AS total FROM productos";
            $stmt = $this->conexion->query($query);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado['total'];
        } catch (PDOException $e) {
            error_log("Error al contar productos: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Obtener todos los productos.
     *
     * @return array Lista de productos.
     */
    public function obtenerProductos() {
        try {
            $query = "SELECT * FROM productos";
            $stmt = $this->conexion->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener productos: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener un producto por su ID.
     *
     * @param int $id ID del producto.
     * @return array|false Datos del producto o false si no se encuentra.
     */
    public function obtenerPorId($id) {
        try {
            $query = "SELECT * FROM productos WHERE id = :id";
            $stmt = $this->conexion->prepare($query);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener producto por ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Añadir un nuevo producto.
     *
     * @param string $nombre Nombre del producto.
     * @param string $descripcion Descripción del producto.
     * @param float $precio Precio del producto.
     * @param string $categoria Categoría del producto.
     * @param string $talla Talla del producto.
     * @param string $color Color del producto.
     * @param string $imagen Ruta de la imagen del producto.
     * @param int $stock Cantidad en stock.
     * @param float $descuento Descuento aplicado al producto (opcional, por defecto 0).
     * @return bool True si se añadió correctamente, false en caso contrario.
     */
    public function añadirProducto($nombre, $descripcion, $precio, $categoria, $talla, $color, $imagen, $stock, $descuento = 0) {
        try {
            $query = "INSERT INTO productos (nombre, descripcion, precio, categoria, talla, color, imagen, stock, descuento) 
                      VALUES (:nombre, :descripcion, :precio, :categoria, :talla, :color, :imagen, :stock, :descuento)";
            $stmt = $this->conexion->prepare($query);
            $stmt->execute([
                ':nombre' => $nombre,
                ':descripcion' => $descripcion,
                ':precio' => $precio,
                ':categoria' => $categoria,
                ':talla' => $talla,
                ':color' => $color,
                ':imagen' => $imagen,
                ':stock' => $stock,
                ':descuento' => $descuento
            ]);
            return true;
        } catch (PDOException $e) {
            error_log("Error al añadir producto: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualizar un producto existente.
     *
     * @param int $id ID del producto.
     * @param string $nombre Nombre del producto.
     * @param string $descripcion Descripción del producto.
     * @param float $precio Precio del producto.
     * @param string $categoria Categoría del producto.
     * @param string $talla Talla del producto.
     * @param string $color Color del producto.
     * @param string $imagen Ruta de la imagen del producto.
     * @param int $stock Cantidad en stock.
     * @param float $descuento Descuento aplicado al producto (opcional, por defecto 0).
     * @return bool True si se actualizó correctamente, false en caso contrario.
     */
    public function actualizarProducto($id, $nombre, $descripcion, $precio, $categoria, $talla, $color, $imagen, $stock, $descuento = 0) {
        try {
            $query = "UPDATE productos 
                      SET nombre = :nombre, 
                          descripcion = :descripcion, 
                          precio = :precio, 
                          categoria = :categoria, 
                          talla = :talla, 
                          color = :color, 
                          imagen = :imagen, 
                          stock = :stock, 
                          descuento = :descuento 
                      WHERE id = :id";
            $stmt = $this->conexion->prepare($query);
            $stmt->execute([
                ':id' => $id,
                ':nombre' => $nombre,
                ':descripcion' => $descripcion,
                ':precio' => $precio,
                ':categoria' => $categoria,
                ':talla' => $talla,
                ':color' => $color,
                ':imagen' => $imagen,
                ':stock' => $stock,
                ':descuento' => $descuento
            ]);
            return true;
        } catch (PDOException $e) {
            error_log("Error al actualizar producto: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Eliminar un producto.
     *
     * @param int $id ID del producto.
     * @return bool True si se eliminó correctamente, false en caso contrario.
     */
    public function eliminarProducto($id) {
        try {
            $query = "DELETE FROM productos WHERE id = :id";
            $stmt = $this->conexion->prepare($query);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (PDOException $e) {
            error_log("Error al eliminar producto: " . $e->getMessage());
            return false;
        }
    }
}