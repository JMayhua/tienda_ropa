<?php
// aplicacion/vistas/admin/productos/gestionar_productos.php
require_once __DIR__ . '/../plantillas/cabecera.php';
?>

<div class="contenedor">
    <h2>Gestionar Productos</h2>
    <a href="/Tienda_ropa/publico/index.php?accion=listar_productos" class="btn">Volver a la Lista</a>
    <form action="/Tienda_ropa/publico/index.php?accion=añadir_producto" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" required></textarea>
        </div>
        <div class="form-group">
            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="precio" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="categoria">Categoría:</label>
            <input type="text" id="categoria" name="categoria" required>
        </div>
        <div class="form-group">
            <label for="talla">Talla:</label>
            <input type="text" id="talla" name="talla" required>
        </div>
        <div class="form-group">
            <label for="color">Color:</label>
            <input type="text" id="color" name="color" required>
        </div>
        <div class="form-group">
            <label for="stock">Stock:</label>
            <input type="number" id="stock" name="stock" required>
        </div>
        <div class="form-group">
            <label for="descuento">Descuento (%):</label>
            <input type="number" id="descuento" name="descuento" step="0.01" min="0" max="100" value="0">
        </div>
        <div class="form-group">
            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen" name="imagen" accept="image/*" required>
        </div>
        <button type="submit" class="btn">Añadir Producto</button>
    </form>
</div>

<?php
require_once __DIR__ . '/../plantillas/pie.php';
?>