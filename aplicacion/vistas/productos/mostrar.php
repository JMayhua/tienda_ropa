<!-- aplicacion/vistas/productos/mostrar.php -->
<?php include '../plantillas/cabecera.php'; ?>

<h1><?php echo $producto['nombre']; ?></h1>
<img src="<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre']; ?>" width="300">
<p><?php echo $producto['descripcion']; ?></p>
<p><strong>Precio:</strong> $<?php echo $producto['precio']; ?></p>
<p><strong>Categoría:</strong> <?php echo $producto['categoria']; ?></p>
<p><strong>Stock:</strong> <?php echo $producto['stock']; ?></p>

<a href="/tienda_ropa/publico/index.php?accion=catalogo">Volver al catálogo</a>

<?php include '../plantillas/pie.php'; ?>