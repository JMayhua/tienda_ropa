<?php
// aplicacion/vistas/productos/catalogo.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../plantillas/cabecera.php';
require_once __DIR__ . '/../../modelos/ModeloProductos.php';

$modelo = new ModeloProductos();
$productos = $modelo->obtenerProductos();
?>

<link rel="stylesheet" href="../publico/recursos/css/catalogo.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../publico/recursos/js/catalogo.js" defer></script>

<!-- Banner hero -->
<section class="hero-banner">
    <div class="hero-content">
        <h1>COLECCIÓN PREMIUM 2025</h1>
        <p>Descubre las prendas más exclusivas de la temporada</p>
    </div>
</section>

<!-- Filtros -->
<div class="category-filters">
    <button class="category-btn active">Todos</button>
    <button class="category-btn">Hombre</button>
    <button class="category-btn">Mujer</button>
    <button class="category-btn">Niños</button>
</div>

<!-- Productos -->
<div class="product-grid">
    <?php foreach ($productos as $producto): ?>
        <div class="product-card">
            <?php if ($producto['descuento'] > 0): ?>
                <span class="product-badge">-<?= $producto['descuento'] ?>% OFF</span>
            <?php endif; ?>
            
            <div class="product-media">
                <img src="<?= $producto['imagen'] ?>" alt="<?= $producto['nombre'] ?>" class="product-image">
                
                <div class="product-actions">
                    <button class="action-btn" title="Favoritos"><i class="fas fa-heart"></i></button>
                    <button class="action-btn" title="Vista rápida"><i class="fas fa-eye"></i></button>
                </div>
            </div>
            
            <div class="product-content">
                <span class="product-category"><?= $producto['categoria'] ?></span>
                <h3 class="product-title"><?= $producto['nombre'] ?></h3>
                <p class="product-description"><?= $producto['descripcion'] ?></p>
                
                <div class="product-price">
                    <?php if ($producto['descuento'] > 0): ?>
                        <span class="current-price">$<?= number_format($producto['precio'] * (1 - $producto['descuento']/100), 2) ?></span>
                        <span class="original-price">$<?= number_format($producto['precio'], 2) ?></span>
                    <?php else: ?>
                        <span class="current-price">$<?= number_format($producto['precio'], 2) ?></span>
                    <?php endif; ?>
                </div>
                
                <form method="POST" action="/Tienda_ropa/aplicacion/controladores/ControladorCarrito.php">
                    <input type="hidden" name="id_producto" value="<?= $producto['id'] ?>">
                    <input type="hidden" name="accion" value="añadir">
                    <button type="submit" class="add-to-cart">
                        <i class="fas fa-shopping-cart"></i> Añadir al carrito
                    </button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/../plantillas/pie.php'; ?>