<?php
// aplicacion/vistas/admin/panel_admin.php
require_once __DIR__ . '/../plantillas/cabecera.php';
?>

<div class="contenedor">
    <h2>Panel de Administración</h2>
    <div class="resumen">
        <div class="card">
            <h3>Productos</h3>
            <p>Total: <?php echo $totalProductos; ?></p>
            <a href="/Tienda_ropa/publico/index.php?accion=listar_productos" class="btn">Gestionar Productos</a>
        </div>
        <div class="card">
            <h3>Pedidos</h3>
            <p>Total: <?php echo $totalPedidos; ?></p>
            <a href="/Tienda_ropa/publico/index.php?accion=listar_pedidos" class="btn">Ver Pedidos</a>
        </div>
        <div class="card">
            <h3>Usuarios</h3>
            <p>Total: <?php echo $totalUsuarios; ?></p>
            <a href="/Tienda_ropa/publico/index.php?accion=gestionar_usuarios" class="btn">Gestionar Usuarios</a>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../plantillas/pie.php';
?>