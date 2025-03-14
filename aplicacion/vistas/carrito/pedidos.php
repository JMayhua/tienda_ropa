<?php require_once __DIR__ . '/../plantillas/cabecera.php'; ?>

<div class="contenedor">
    <h1>Mis Pedidos</h1>

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

    <?php if (!empty($pedidos)): ?>
        <div class="lista-pedidos">
            <?php foreach ($pedidos as $pedido): ?>
                <div class="pedido-item">
                    <div class="pedido-info">
                        <h3>Pedido #<?php echo htmlspecialchars($pedido['id']); ?></h3>
                        <p><strong>Fecha:</strong> <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($pedido['fecha']))); ?></p>
                        <p><strong>Estado:</strong> <?php echo htmlspecialchars($pedido['estado']); ?></p>
                        <p><strong>Total:</strong> S/ <?php echo number_format($pedido['total'], 2); ?></p>
                    </div>
                    <div class="pedido-acciones">
                        <a href="/Tienda_ropa/publico/index.php?accion=ver_detalles_pedido&id=<?php echo $pedido['id']; ?>" class="boton">Ver detalles</a>
                        <?php if ($pedido['estado'] === 'pendiente'): ?>
                            <a href="/Tienda_ropa/publico/index.php?accion=cancelar_pedido&id=<?php echo $pedido['id']; ?>" class="boton boton-cancelar" onclick="return confirm('¿Estás seguro de que deseas cancelar este pedido?');">Cancelar</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No tienes pedidos realizados.</p>
        <a href="/Tienda_ropa/publico/index.php" class="boton">Ir a la tienda</a>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../plantillas/pie.php'; ?>