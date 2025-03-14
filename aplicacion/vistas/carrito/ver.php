<?php
// aplicacion/vistas/carrito/ver.php
require_once __DIR__ . '/../plantillas/cabecera.php';
?>

<style>
    .mensaje-carrito-vacio {
        text-align: center;
        font-size: 1.5em;
        color: #666;
        margin-top: 20px;
    }

    .titulo-carrito {
        text-align: center;
        margin-bottom: 20px;
    }

    .productos-carrito {
        display: flex;
        flex-direction: column;
        gap: 20px;
        margin-bottom: 20px;
    }

    .producto-carrito {
        display: flex;
        align-items: center;
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 8px;
        background-color: #f9f9f9;
    }

    .producto-img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        margin-right: 20px;
    }

    .producto-info {
        flex-grow: 1;
    }

    .btn-eliminar {
        background-color: #ff4d4d;
        color: white;
        padding: 8px 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-eliminar:hover {
        background-color: #cc0000;
    }

    .total-carrito {
        text-align: right;
        margin-top: 20px;
    }

    .btn-finalizar-compra {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-finalizar-compra:hover {
        background-color: #45a049;
    }
</style>

<?php
// Verificar si el carrito está vacío
if (empty($productosCarrito)) {
    echo "<p class='mensaje-carrito-vacio'>El carrito está vacío.</p>";
} else {
    echo "<h1 class='titulo-carrito'>Tu Carrito</h1>";
    echo "<div class='productos-carrito'>";

    foreach ($productosCarrito as $producto) {
        echo "<div class='producto-carrito'>";
        echo "<img src='" . htmlspecialchars($producto['imagen']) . "' alt='" . htmlspecialchars($producto['nombre']) . "' class='producto-img'>";
        echo "<div class='producto-info'>";
        echo "<h2>" . htmlspecialchars($producto['nombre']) . "</h2>";
        echo "<p>" . htmlspecialchars($producto['descripcion']) . "</p>";
        echo "<p>Cantidad: " . htmlspecialchars($producto['cantidad']) . "</p>";
        echo "<p>Precio unitario: $" . number_format($producto['precio'], 2) . "</p>";
        echo "<p>Subtotal: $" . number_format($producto['subtotal'], 2) . "</p>";
        echo "<a href='/Tienda_ropa/publico/index.php?accion=eliminar_del_carrito&id=" . $producto['id'] . "' class='btn-eliminar'>Eliminar</a>";
        echo "</div>";
        echo "</div>";
    }

    echo "</div>";

    // Calcular el total del carrito
    $totalCarrito = array_sum(array_column($productosCarrito, 'subtotal'));
    echo "<div class='total-carrito'>";
    echo "<h3>Total del carrito: $" . number_format($totalCarrito, 2) . "</h3>";
    echo "<form action='/Tienda_ropa/publico/index.php?accion=finalizar_compra' method='post'>";
    echo "<input type='submit' value='Finalizar Pedido' class='btn-finalizar-compra'>";
    echo "</form>";
    echo "</div>";
}

require_once __DIR__ . '/../plantillas/pie.php';
?>