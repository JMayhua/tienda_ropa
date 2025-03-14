<?php
// aplicacion/vistas/admin/usuarios/listar_usuarios.php
require_once __DIR__ . '/../../plantillas/cabecera.php';
?>

<div class="contenedor">
    <h2>Lista de Usuarios</h2>
    <table class="tabla">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?php echo $usuario['id']; ?></td>
                <td><?php echo $usuario['nombre']; ?></td>
                <td><?php echo $usuario['email']; ?></td>
                <td><?php echo $usuario['rol']; ?></td>
                <td>
                    <a href="/Tienda_ropa/publico/index.php?accion=editar_usuario&id=<?php echo $usuario['id']; ?>" class="btn">Editar</a>
                    <a href="/Tienda_ropa/publico/index.php?accion=eliminar_usuario&id=<?php echo $usuario['id']; ?>" class="btn btn-eliminar">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
require_once __DIR__ . '/../../plantillas/pie.php';
?>