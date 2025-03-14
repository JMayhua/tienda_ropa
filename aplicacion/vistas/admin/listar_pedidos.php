<?php
// aplicacion/vistas/admin/pedidos/listar_pedidos.php
require_once __DIR__ . '/../plantillas/cabecera.php';
?>

<div class="contenedor">
    <h2>Lista de Pedidos</h2>
    <table class="tabla">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pedidos as $pedido): ?>
            <tr>
                <td><?php echo $pedido['id']; ?></td>
                <td><?php echo $pedido['usuario_id']; ?></td>
                <td><?php echo $pedido['fecha']; ?></td>
                <td>S/ <?php echo number_format($pedido['total'], 2); ?></td>
                <td><?php echo $pedido['estado']; ?></td>
                <td>
                    <a href="/Tienda_ropa/publico/index.php?accion=ver_pedido_admin&id=<?php echo $pedido['id']; ?>" class="btn">Ver Detalles</a>
                    <a href="/Tienda_ropa/publico/index.php?accion=cambiar_estado_pedido&id=<?php echo $pedido['id']; ?>" class="btn">Cambiar Estado</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
require_once __DIR__ . '/../plantillas/pie.php';
?>