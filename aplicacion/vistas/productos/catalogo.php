<?php
// aplicacion/vistas/productos/catalogo.php

// Aseguramos que la sesión esté iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


// Código para depuración
echo "<!-- DEBUG: ";
print_r($_SESSION);
echo " -->";


// Incluimos la cabecera dinámica
require_once __DIR__ . '/../plantillas/cabecera.php';

// Incluir el modelo de productos
require_once __DIR__ . '/../../modelos/ModeloProductos.php';

// Crear una instancia del modelo
$modelo = new ModeloProductos();

// Obtener todos los productos desde la base de datos
$productos = $modelo->obtenerProductos();
?>

<!-- Llamado al CSS -->
<link rel="stylesheet" href="../publico/recursos/css/catalogo.css">

<!-- Llamado a SweetAlert para notificaciones bonitas -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Llamado al archivo JS -->
<script src="../publico/recursos/js/catalogo.js" defer></script>

<!-- Banner de ofertas -->
<div class="banner">
    <h1>🔥 ¡Ofertas Exclusivas en Nuestra Tienda! 🔥</h1>
    <p>Descubre las mejores prendas al mejor precio.</p>
</div>

<!-- Título del catálogo -->
<h1 class="titulo-catalogo">Catálogo de Productos</h1>

<!-- Contenedor de productos -->
<div class="productos">
    <?php foreach ($productos as $producto): ?>
        <div class="producto">
            <div class="producto-imagen">
                <img src="<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre']; ?>" class="producto-img">
                <?php if ($producto['descuento'] > 0): ?>
                    <span class="descuento">-<?php echo $producto['descuento']; ?>%</span>
                <?php endif; ?>
            </div>
            <div class="producto-info">
                <h2><?php echo $producto['nombre']; ?></h2>
                <p class="descripcion"><?php echo $producto['descripcion']; ?></p>
                <p class="precio">
                    <?php if ($producto['descuento'] > 0): ?>
                        <span class="precio-antes">$<?php echo number_format($producto['precio'], 2); ?></span>
                        <span class="precio-descuento">$<?php echo number_format($producto['precio'] * (1 - $producto['descuento'] / 100), 2); ?></span>
                    <?php else: ?>
                        $<?php echo number_format($producto['precio'], 2); ?>
                    <?php endif; ?>
                </p>
                <p><strong>Categoría:</strong> <?php echo $producto['categoria']; ?></p>
                <p><strong>Stock:</strong> <?php echo $producto['stock']; ?></p>
                <form method="POST" action="/Tienda_ropa/aplicacion/controladores/ControladorCarrito.php" style="display: inline;">
                    <input type="hidden" name="id_producto" value="<?php echo $producto['id']; ?>">
                    <input type="hidden" name="accion" value="añadir">
                    <button type="submit" class="btn-agregar">🛒 Añadir al carrito</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/../plantillas/pie.php'; ?>