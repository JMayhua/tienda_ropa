<!-- aplicacion/vistas/pedidos/confirmacion.php -->
<?php include '../plantillas/cabecera.php'; ?>

<h1>Confirmación de Compra</h1>
<p>Gracias por tu compra. Tu pedido ha sido registrado con el número: <strong>#<?php echo $pedido_id; ?></strong></p>

<h2>Detalles del Pedido</h2>
<table border="1">
    <tr>
        <th>Producto</th>
        <th>Cantidad</th>
        <th>Precio Unitario</th>
        <th>Subtotal</th>
    </tr>
    <?php foreach ($detalles as $detalle): ?>
    <tr>
        <td><?php echo $detalle['nombre']; ?></td>
        <td><?php echo $detalle['cantidad']; ?></td>
        <td>$<?php echo $detalle['precio_unitario']; ?></td>
        <td>$<?php echo $detalle['cantidad'] * $detalle['precio_unitario']; ?></td>
    </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="3"><strong>Total</strong></td>
        <td><strong>$<?php echo $total; ?></strong></td>
    </tr>
</table>

<a href="/tienda_ropa/publico/index.php">Volver al catálogo</a>

<?php include '../plantillas/pie.php'; ?>