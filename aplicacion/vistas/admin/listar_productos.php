<?php
// aplicacion/vistas/admin/productos/listar_productos.php
require_once __DIR__ . '/../../plantillas/cabecera.php';
?>

<div class="contenedor">
    <h2>Lista de Productos</h2>
    <a href="/Tienda_ropa/publico/index.php?accion=añadir_producto" class="btn">Añadir Producto</a>
    <table class="tabla">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $producto): ?>
            <tr>
                <td><?php echo $producto['id']; ?></td>
                <td><?php echo $producto['nombre']; ?></td>
                <td>S/ <?php echo number_format($producto['precio'], 2); ?></td>
                <td><?php echo $producto['stock']; ?></td>
                <td>
                    <a href="/Tienda_ropa/publico/index.php?accion=editar_producto&id=<?php echo $producto['id']; ?>" class="btn">Editar</a>
                    <a href="/Tienda_ropa/publico/index.php?accion=eliminar_producto&id=<?php echo $producto['id']; ?>" class="btn btn-eliminar">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
require_once __DIR__ . '/../../plantillas/pie.php';
?>