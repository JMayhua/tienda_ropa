<?php
// aplicacion/vistas/admin/pedidos/ver.php
require_once __DIR__ . '/../plantillas/cabecera.php';
?>

<div class="contenedor">
    <h1>Detalles del Pedido #<?php echo htmlspecialchars($pedido['id']); ?></h1>

    <!-- Mostrar mensajes de éxito o error -->
    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="mensaje">
            <?php echo $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="error">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- Información general del pedido -->
    <div class="info-pedido">
        <p><strong>Usuario ID:</strong> <?php echo htmlspecialchars($pedido['usuario_id']); ?></p>
        <p><strong>Fecha:</strong> <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($pedido['fecha']))); ?></p>
        <p><strong>Estado:</strong> <?php echo htmlspecialchars($pedido['estado']); ?></p>
        <p><strong>Total:</strong> S/ <?php echo number_format($pedido['total'], 2); ?></p>
        
        <!-- Mostrar comprobante si existe -->
        <?php if (!empty($pedido['comprobante'])): ?>
            <p><strong>Comprobante:</strong> 
                <a href="/Tienda_ropa/comprobantes/<?php echo htmlspecialchars($pedido['comprobante']); ?>" target="_blank">
                    Ver comprobante
                </a>
            </p>
        <?php endif; ?>
    </div>

    <!-- Lista de productos en el pedido -->
    <h2>Productos</h2>
    <?php if (!empty($detallesPedido)): ?>
        <table class="tabla-productos">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Imagen</th>
                    <th>Cantidad</th>
                    <th>Precio unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($detallesPedido as $detalle): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($detalle['nombre']); ?></td>
                        <td>
                            <?php if (!empty($detalle['imagen'])): ?>
                                <!-- Mostrar la imagen con la ruta correcta -->
                                <img src="/Tienda_ropa/<?php echo htmlspecialchars($detalle['imagen']); ?>" alt="<?php echo htmlspecialchars($detalle['nombre']); ?>" class="imagen-miniatura">
                            <?php else: ?>
                                <span>Sin imagen</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($detalle['cantidad']); ?></td>
                        <td>S/ <?php echo number_format($detalle['precio_unitario'], 2); ?></td>
                        <td>S/ <?php echo number_format($detalle['cantidad'] * $detalle['precio_unitario'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right"><strong>Total:</strong></td>
                    <td>S/ <?php echo number_format($pedido['total'], 2); ?></td>
                </tr>
            </tfoot>
        </table>
    <?php else: ?>
        <p>No hay productos en este pedido.</p>
    <?php endif; ?>

    <!-- Botones de acción -->
    <div class="acciones">
        <a href="/Tienda_ropa/publico/index.php?accion=listar_pedidos" class="boton">Volver a la lista de pedidos</a>
    </div>
</div>

<?php
require_once __DIR__ . '/../plantillas/pie.php';
?>