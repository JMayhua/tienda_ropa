<?php
// aplicacion/vistas/admin/pedidos/cambiar_estado.php
require_once __DIR__ . '/../plantillas/cabecera.php';
?>

<div class="contenedor">
    <h2>Cambiar Estado del Pedido #<?php echo $pedido['id']; ?></h2>

    <form action="/Tienda_ropa/publico/index.php?accion=cambiar_estado_pedido&id=<?php echo $pedido['id']; ?>" method="POST">
        <div class="form-group">
            <label for="estado">Estado:</label>
            <select name="estado" id="estado" class="form-control">
                <option value="pendiente" <?php echo ($pedido['estado'] == 'pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                <option value="en_proceso" <?php echo ($pedido['estado'] == 'en_proceso') ? 'selected' : ''; ?>>En Proceso</option>
                <option value="completado" <?php echo ($pedido['estado'] == 'completado') ? 'selected' : ''; ?>>Completado</option>
                <option value="cancelado" <?php echo ($pedido['estado'] == 'cancelado') ? 'selected' : ''; ?>>Cancelado</option>
            </select>
        </div>

        <button type="submit" class="btn">Actualizar Estado</button>
        <a href="/Tienda_ropa/publico/index.php?accion=listar_pedidos" class="btn">Cancelar</a>
    </form>
</div>

<?php
require_once __DIR__ . '/../plantillas/pie.php';
?>