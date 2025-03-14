<!-- aplicacion/vistas/carrito/pago_yape.php -->
<?php require_once __DIR__ . '/../plantillas/cabecera.php'; ?>

<h1>Pago con Yape</h1>
<p>Total a pagar: S/ <?php echo number_format($_SESSION['total_pedido'], 2); ?></p>

<?php if (isset($_SESSION['error_pago'])): ?>
    <div class="error"><?php echo $_SESSION['error_pago']; unset($_SESSION['error_pago']); ?></div>
<?php endif; ?>

<!-- Mostrar número de Yape o código QR -->
<div class="info-pago">
    <h2>Realiza el pago a:</h2>
    <p>Número de Yape: <strong>999 888 777</strong></p>
    <p>O escanea el siguiente código QR:</p>
    <img src="../publico/recursos/imagenes/qr_23.png" alt="Código QR de Yape" style="width: 200px;">
</div>

<!-- Formulario para subir comprobante -->
<form action="/Tienda_ropa/publico/index.php?accion=procesar_pago_yape" method="post" enctype="multipart/form-data">
    <label for="comprobante">Subir comprobante de pago:</label>
    <input type="file" id="comprobante" name="comprobante" accept="image/*" required>

    <button type="submit">Enviar comprobante</button>
</form>

<?php require_once __DIR__ . '/../plantillas/pie.php'; ?>