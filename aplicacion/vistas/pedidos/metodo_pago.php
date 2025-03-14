<!-- aplicacion/vistas/pedidos/metodo_pago.php -->
<?php include '../plantillas/cabecera.php'; ?>

<h1>Selecciona tu Método de Pago</h1>
<form method="POST" action="/tienda_ropa/publico/index.php?accion=procesar_pago">
    <input type="hidden" name="pedido_id" value="<?php echo $_GET['id']; ?>">
    
    <label>
        <input type="radio" name="metodo_pago" value="yape" required> Yape
    </label>
    <br>
    <label>
        <input type="radio" name="metodo_pago" value="efectivo"> Efectivo (contra entrega)
    </label>
    <br>
    <button type="submit">Continuar</button>
</form>

<?php include '../plantillas/pie.php'; ?>