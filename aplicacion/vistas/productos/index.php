<!-- aplicacion/vistas/productos/index.php -->
<?php include '../plantillas/cabecera.php'; ?>

<h1>Lista de Productos</h1>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Precio</th>
        <th>Categoría</th>
        <th>Acciones</th>
    </tr>
    <?php foreach ($productos as $producto): ?>
    <tr>
        <td><?php echo $producto['id']; ?></td>
        <td><?php echo $producto['nombre']; ?></td>
        <td><?php echo $producto['descripcion']; ?></td>
        <td><?php echo $producto['precio']; ?></td>
        <td><?php echo $producto['categoria']; ?></td>
        <td>
            <a href="/tienda_ropa/publico/index.php?accion=editar_producto&id=<?php echo $producto['id']; ?>">Editar</a>
            <a href="/tienda_ropa/publico/index.php?accion=eliminar_producto&id=<?php echo $producto['id']; ?>">Eliminar</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<a href="/tienda_ropa/publico/index.php?accion=añadir_producto">Añadir Producto</a>

<?php include '../plantillas/pie.php'; ?>