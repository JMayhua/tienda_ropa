# ARQUITECTURA.md - Diseño Técnico Detallado

## Tabla de Contenidos

1. [Introducción](#introducción)
2. [Diagrama de Arquitectura General](#diagrama-de-arquitectura-general)
3. [Patrón MVC Profundizado](#patrón-mvc-profundizado)
4. [Flujos de Ejecución](#flujos-de-ejecución)
5. [Capas de la Aplicación](#capas-de-la-aplicación)
6. [Patrones de Diseño](#patrones-de-diseño)
7. [Gestión de Dependencias](#gestión-de-dependencias)
8. [Escalabilidad Horizontal](#escalabilidad-horizontal)

---

## Introducción

Este documento detalla la arquitectura técnica de **Tienda Ropa**, explicando las decisiones de diseño, patrones implementados, y cómo las diferentes capas se interconectan.

### Objetivos Arquitectónicos

```
┌────────────────────────────────────┐
│ Mantenibilidad                      │
│ - Código limpio y autodocumentado  │
│ - Separación clara de concerns     │
│ - Bajo acoplamiento alto cohesión  │
├────────────────────────────────────┤
│ Seguridad                           │
│ - Protección contra inyección SQL  │
│ - Hashing seguro de contraseñas    │
│ - Control de acceso basado en rol  │
├────────────────────────────────────┤
│ Rendimiento                         │
│ - Querys optimizadas con índices   │
│ - Sesiones en memoria              │
│ - Caché de datos estáticos         │
├────────────────────────────────────┤
│ Escalabilidad                       │
│ - Arquitectura sin estado           │
│ - Base de datos normalizada         │
│ - Fácil de distribuir en múltiples  │
│   servidores                        │
└────────────────────────────────────┘
```

---

## Diagrama de Arquitectura General

### Vista de Capas (Layered Architecture)

```
┌─────────────────────────────────────────────────────────┐
│                  PRESENTATION LAYER                      │
│  ┌─────────────────────────────────────────────────┐   │
│  │ HTML Templates (vistas/usuarios/, etc)          │   │
│  │ CSS Styles (publico/recursos/css/)              │   │
│  │ JavaScript (publico/recursos/js/)               │   │
│  │ Error Handling & Session Management             │   │
│  └─────────────────────────────────────────────────┘   │
└──────────────────────┬─────────────────────────────────┘
                       │ HTTP Response
                       │
                       ▼
┌─────────────────────────────────────────────────────────┐
│                  BUSINESS LOGIC LAYER                    │
│  ┌─────────────────────────────────────────────────┐   │
│  │ Controllers (aplicacion/controladores/)         │   │
│  │ - Routing & Request Handling                    │   │
│  │ - Input Validation                              │   │
│  │ - Authorization (RBAC)                          │   │
│  │ - Model Orchestration                           │   │
│  │ - Response Preparation                          │   │
│  └─────────────────────────────────────────────────┘   │
└──────────────────────┬─────────────────────────────────┘
                       │ Business Rules
                       │
                       ▼
┌─────────────────────────────────────────────────────────┐
│                 DATA ACCESS LAYER                        │
│  ┌─────────────────────────────────────────────────┐   │
│  │ Models (aplicacion/modelos/)                    │   │
│  │ - Database Operations (CRUD)                    │   │
│  │ - Query Building                                │   │
│  │ - Result Mapping                                │   │
│  │ - Transaction Management                        │   │
│  │ - Logger Integration                            │   │
│  └─────────────────────────────────────────────────┘   │
└──────────────────────┬─────────────────────────────────┘
                       │ SQL Queries
                       │
                       ▼
┌─────────────────────────────────────────────────────────┐
│                  PERSISTENCE LAYER                       │
│  ┌─────────────────────────────────────────────────┐   │
│  │ MySQL/MariaDB Database                          │   │
│  │ - Table: usuarios                               │   │
│  │ - Table: productos                              │   │
│  │ - Table: pedidos                                │   │
│  │ - Table: detalles_pedido                        │   │
│  │ - Indexes, Constraints, Triggers                │   │
│  └─────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────┘
```

### Flujo Horizontal (Por Módulo)

```
                      ┌─────────────────┐
                      │  publico/       │
                      │  index.php      │
                      │ (Router)        │
                      └────────┬────────┘
                               │
                ┌──────────┬───┼───┬──────────┐
                │          │       │          │
         ┌──────▼──┐ ┌────▼──┐ ┌──▼─────┐ ┌─▼──────┐
         │Controlador│Controlador│Controlador│Controlador
         │Autent.   │Productos  │Carrito   │Pagos
         └──────┬──┘ └────┬──┘ └──┬─────┘ └─┬──────┘
                │         │       │         │
         ┌──────▼──────────▼───────▼─────────▼────┐
         │       Models Layer                      │
         │  ┌────────────────────────────────┐   │
         │  │ ModeloUsuarios                │   │
         │  │ ModeloProductos               │   │
         │  │ ModeloCarrito                 │   │
         │  │ ModeloPedidos                 │   │
         │  └────────────────────────────────┘   │
         └──────────────┬───────────────────────┘
                        │
                ┌───────▼────────┐
                │  PDO Connection │
                │  MySQL Database │
                └─────────────────┘
```

---

## Patrón MVC Profundizado

### Responsabilidades de Cada Componente

#### Model (aplicacion/modelos/)

**Responsabilidad Principal**: Represente datos y lógica de negocio.

```php
class ModeloProductos {
    // Acceso directo a base de datos
    public function obtenerProductos() { ... }
    
    // Validación de reglas de negocio
    public function validarStock($productoId, $cantidad) { ... }
    
    // Transformación de datos
    public function aplicarDescuento($precio, $porcentaje) { ... }
    
    // Log de operaciones críticas
    public function registrarMovimientoStock($productoId, $cantidad) { ... }
}
```

**No Responsables De**:
- ❌ Renderizar HTML
- ❌ Validar requests HTTP
- ❌ Gestionar sesiones
- ❌ Redireccionamientos

**Interfaz Pública**:
```
obtenerProductos()          → Array
obtenerPorId($id)           → Array|false
contarProductos()           → int
añadirProducto(...)         → bool
actualizarProducto($id,...) → bool
eliminarProducto($id)       → bool
verificarStock($id, $qty)   → bool
```

---

#### Controller (aplicacion/controladores/)

**Responsabilidad Principal**: Orquestar request → Model → View.

```php
class ControladorProductos {
    public function catalogo() {
        // 1. Validar parámetros GET/POST
        $pagina = intval($_GET['pagina'] ?? 1);
        
        // 2. Validar permisos
        if (!$this->usuarioAutorizado()) {
            header('Location: login');
            exit;
        }
        
        // 3. Instanciar y llamar modelo
        $modelo = new ModeloProductos();
        $productos = $modelo->obtenerProductos();
        
        // 4. Preparar datos para vista
        $totalProductos = count($productos);
        
        // 5. Renderizar vista
        require __DIR__ . '/../vistas/productos/catalogo.php';
    }
}
```

**Flujo de Control**:
```
1. Recibir Request
   ↓
2. Validar Input
   ↓
3. Verificar Autenticación
   ↓
4. Verificar Autorización
   ↓
5. Llamar Modelo con datos validados
   ↓
6. Capturar respuesta del modelo
   ↓
7. Preparar datos para la vista
   ↓
8. Incluir vista (require)
   └→ Vista accede a variables locales
   ↓
9. Salida HTML
```

---

#### View (aplicacion/vistas/)

**Responsabilidad Principal**: Renderizar presentación basada en datos.

```php
<!-- vistas/productos/catalogo.php -->
<div class="productos-grid">
    <?php foreach ($productos as $producto): ?>
        <div class="producto-card">
            <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
            <p>$<?php echo number_format($producto['precio'], 2); ?></p>
            <button onclick="addCart(<?php echo $producto['id']; ?>)">
                Comprar
            </button>
        </div>
    <?php endforeach; ?>
</div>
```

**Buenas Prácticas**:
- ✅ Escapar datos del usuario: `htmlspecialchars()`
- ✅ Usar lógica mínima (bucles, condicionales)
- ✅ No hacer queries directas a BD
- ✅ Reutilizar templates con `include`

---

### Ciclo de Vida de una Solicitud: Ejemplo Práctico

**Caso**: Usuario hace click en "Comprar" en producto con ID=3

```
┌─ PASO 1: HTTP Request ─────────────────────────────────────┐
│ GET /publico/index.php?accion=añadir_al_carrito&id=3       │
│ Headers: {session_id: abc123}                              │
└────────────────────────────────────────────────────────────┘
           │
           ▼
┌─ PASO 2: Front Controller (index.php) ─────────────────────┐
│ require_once 'config.php'  // Conexión BD + sesión         │
│ require_once controladores/*                               │
│ $accion = $_GET['accion']  // 'añadir_al_carrito'         │
│ switch ($accion) {                                         │
│     case 'añadir_al_carrito':                             │
│         $controlador = new ControladorCarrito();           │
│         $controlador->añadirAlCarrito($_GET['id']);        │
│ }                                                          │
└────────────────────────────────────────────────────────────┘
           │
           ▼
┌─ PASO 3: Controller Logic ─────────────────────────────────┐
│                                                             │
│ public function añadirAlCarrito($id) {                     │
│     // 3.1: Validar sesión y autenticación                │
│     if (!isset($_SESSION['usuario_id'])) {                │
│         header('Location: ...?accion=iniciar_sesion');     │
│         exit;                                              │
│     }                                                      │
│                                                             │
│     // 3.2: Validar entrada                                │
│     $id = intval($id);  // Sanitizar                      │
│     if ($id <= 0) {                                        │
│         $_SESSION['error'] = 'ID inválido';               │
│         header('Location: ...?accion=catalogo');           │
│         exit;                                              │
│     }                                                      │
│                                                             │
│     // 3.3: Instanciar modelo                              │
│     $modeloProducto = new ModeloProductos();               │
│                                                             │
│     // 3.4: Validar que producto existe                    │
│     $producto = $modeloProducto->obtenerPorId($id);        │
│     if (!$producto) {                                      │
│         $_SESSION['error'] = 'Producto no encontrado';     │
│         header('Location: ...?accion=catalogo');           │
│         exit;                                              │
│     }                                                      │
│                                                             │
│     // 3.5: Actualizar sesión del carrito                  │
│     if (!isset($_SESSION['carrito'])) {                    │
│         $_SESSION['carrito'] = [];                         │
│     }                                                      │
│                                                             │
│     if (isset($_SESSION['carrito'][$id])) {               │
│         $_SESSION['carrito'][$id]++;  // Incrementar       │
│     } else {                                               │
│         $_SESSION['carrito'][$id] = 1;  // Añadir nuevo    │
│     }                                                      │
│                                                             │
│     // 3.6: Mensaje de éxito y redireccionar              │
│     $_SESSION['mensaje'] = 'Producto añadido al carrito';  │
│     header('Location: ...?accion=ver_carrito');            │
│     exit;                                                  │
│ }                                                          │
│                                                             │
└────────────────────────────────────────────────────────────┘
           │
           ▼
┌─ PASO 4: Response ─────────────────────────────────────────┐
│ HTTP 302 Redirect: Location: /publico/index.php?accion=    │
│ Set-Cookie: PHPSESSID=abc123; (actualizada)               │
│ Body: (vacío, es redirect)                                │
└────────────────────────────────────────────────────────────┘
           │
           ▼
┌─ PASO 5: Navegador ────────────────────────────────────────┐
│ Sigue redirect a: /publico/index.php?accion=ver_carrito    │
│ Nuevamente: GET con accion=ver_carrito                     │
│                                                             │
│ [Ciclo se repite: Controller → Model → View]              │
└────────────────────────────────────────────────────────────┘
           │
           ▼
┌─ PASO 6: View Rendering ──────────────────────────────────┐
│ ControladorCarrito::verCarrito() {                         │
│     $carrito = $_SESSION['carrito'];  // [3 => 2, 5 => 1]  │
│     $modeloProducto = new ModeloProductos();               │
│                                                             │
│     $productosCarrito = [];                                │
│     foreach ($carrito as $id => $cantidad) {               │
│         $producto = $modeloProducto->obtenerPorId($id);    │
│         $producto['cantidad'] = $cantidad;                 │
│         $producto['subtotal'] = $producto['precio'] *      │
│                                  $cantidad;               │
│         $productosCarrito[] = $producto;                   │
│     }                                                      │
│                                                             │
│     require __DIR__ . '/../vistas/carrito/ver.php';        │
│ }                                                          │
└────────────────────────────────────────────────────────────┘
           │
           ▼
┌─ PASO 7: HTML Response ────────────────────────────────────┐
│ Host: localhost                                            │
│ Connection: close                                          │
│ Content-Type: text/html; charset=utf-8                    │
│                                                             │
│ <!DOCTYPE html>                                            │
│ <html>                                                     │
│   <head>                                                   │
│     <title>Carrito</title>                                │
│     <link rel="stylesheet" href="...carrito.css">         │
│   </head>                                                  │
│   <body>                                                   │
│     <div class="carrito">                                  │
│       <h1>Tu Carrito de Compras</h1>                      │
│       <table>                                              │
│         <tr>                                               │
│           <td>Jeans Slim Fit (ID: 3)</td>                 │
│           <td>Cantidad: 2</td>                             │
│           <td>$99.98</td>                                  │
│         </tr>                                              │
│         ...                                                │
│       </table>                                             │
│       <a href="?accion=finalizar_compra">Ir al Pago</a>    │
│     </div>                                                 │
│   </body>                                                  │
│ </html>                                                    │
└────────────────────────────────────────────────────────────┘
           │
           ▼
┌─ PASO 8: Navegador Renderiza ──────────────────────────────┐
│ [Página visual del carrito con opciones de compra]         │
└────────────────────────────────────────────────────────────┘
```

---

## Flujos de Ejecución

### Flujo 1: Autenticación (Login)

```
USUARIO ABRE NAVEGADOR
    ↓
GET /publico/index.php?accion=iniciar_sesion
    ↓
ControladorAutenticacion::iniciarSesion()
    ├─ GET (método inicial): require_once vista login.php
    └─ View: Muestra formulario
    
USUARIO COMPLETA FORMULARIO Y ENVÍA
    ↓
POST /publico/index.php?accion=iniciar_sesion
    ├─ $_POST['email'] = 'jose@mayhua'
    └─ $_POST['password'] = 'senha123'
    ↓
ControladorAutenticacion::iniciarSesion()
    ├─ Valida que POST no esté vacío
    ├─ Extrae y sanitiza email y password
    └─ Llama ModeloUsuarios::iniciarSesion($email, password)
    ↓
ModeloUsuarios::iniciarSesion()
    ├─ Prepara query: 
    │  "SELECT * FROM usuarios WHERE email = :email"
    ├─ Ejecuta con prepared statement (placeholder :email)
    ├─ Fetch usuario
    ├─ Verifica password: password_verify(input, hash)
    │  ├─ hash en BD: $2y$10$Qty.Jw62ifydA7/c60JIpeZM...
    │  ├─ Compara de forma timing-safe
    │  └─ Retorna true/false
    └─ Retorna array usuario o false
    ↓
ControladorAutenticacion::iniciarSesion()
    ├─ SI credenciales válidas:
    │  ├─ $_SESSION['usuario'] = $usuario
    │  ├─ $_SESSION['usuario_id'] = $usuario['id']
    │  ├─ $_SESSION['rol'] = $usuario['rol']
    │  ├─ session_regenerate_id(true)  // Previene session fixation
    │  ├─ SI rol === 'admin': header 'Location: panel_admin'
    │  └─ SI rol === 'cliente': header 'Location: catalogo'
    │
    └─ SI credenciales inválidas:
       ├─ error_log("Intento fallido: jose@mayhua")
       ├─ $_SESSION['error'] = 'Credenciales incorrectas'
       └─ header 'Location: iniciar_sesion'
```

### Flujo 2: Crear Pedido (Finalizar Compra)

```
USUARIO EN CARRITO HACE CLIC EN "PAGAR"
    ├─ Verifica: $_SESSION['carrito'] no vacío
    └─ Valida: usuario autenticado
    ↓
POST /publico/index.php?accion=finalizar_compra
    ├─ $carrito = $_SESSION['carrito']
    │  └─ Ejemplo: [2 => 1, 3 => 2]  (Producto 2 x1, Producto 3 x2)
    └─ ControladorCarrito::finalizarCompra()
    ↓
VALIDACIONES EN CONTROLLER
    ├─ ¿Carrito vacío? → Error
    ├─ ¿Usuario logueado? → Redirigir a login
    └─ Total > 0? → Proceder
    ↓
ModeloPedidos::crearPedido(usuario_id, total, carrito)
    ├─ INICIA TRANSACCIÓN: $db->beginTransaction()
    │  └─ Punto de inicio: Puede rollback
    │
    ├─ CREA PEDIDO:
    │  ├─ INSERT INTO pedidos (usuario_id, fecha, total, estado)
    │  ├─ VALUES (1, NOW(), 79.97, 'pendiente')
    │  └─ $pedidoId = $db->lastInsertId()  // ID = 7
    │
    ├─ CREA DETALLES:
    │  ├─ Para cada item en carrito:
    │  │  ├─ Obtener producto: SELECT * FROM productos WHERE id=2
    │  │  ├─ Verificar stock: $producto['stock'] >= $cantidad
    │  │  │  └─ SI no hay stock: throw Exception → rollBack()
    │  │  ├─ Crear detalle:
    │  │  │  ├─ INSERT INTO detalles_pedido (pedido_id, producto_id, 
    │  │  │  │  cantidad, precio_unitario)
    │  │  │  └─ VALUES (7, 2, 1, 49.99)
    │  │  │
    │  │  └─ ACTUALIZAR STOCK:
    │  │     ├─ UPDATE productos 
    │  │     ├─ SET stock = stock - 1
    │  │     └─ WHERE id = 2
    │  │
    │  └─ [Repetir para productos 3, 5, etc...]
    │
    ├─ VALIDA INTEGRIDAD:
    │  ├─ ¿Total de detalles = carrito? → OK
    │  ├─ ¿Todos productos existen? → OK
    │  └─ ¿Stock no negativo? → OK
    │
    └─ CONFIRMA TRANSACCIÓN: $db->commit()
       └─ Todos cambios persisten en BD
    ↓
RETORNA AL CONTROLLER
    ├─ Pedido creado exitosamente
    ├─ $_SESSION['carrito'] = []  // Vacía carrito
    ├─ $_SESSION['mensaje'] = 'Pedido creado. Procede al pago.'
    └─ header 'Location: metodo_pago' o 'pago_yape'
    ↓
MOSTRAR MÉTODOS DE PAGO
    ├─ Vista: pedidos/metodo_pago.php
    ├─ Opciones: 
    │  ├─ Yape (QR)
    │  ├─ Tarjeta crédito
    │  └─ Transferencia bancaria
    └─ Usuario selecciona: Yape
    ↓
INTEGRACIÓN YAPE
    ├─ ControladorPagos::pagoYape()
    ├─ Obtiene monto total de pedido actual
    ├─ Genera QR con información de pago
    ├─ Genera archivo de comprobante
    ├─ Actualiza: UPDATE pedidos SET comprobante='uuid.png'
    └─ Vista muestra QR para escanear
    ↓
USUARIO ESCANEA QR Y PAGA EN APP YAPE
    ├─ BD aún muestra estado = 'pendiente'
    └─ Admin debe confirmarlo manualmente (o implementar webhook)
    ↓
CONFIRMACIÓN MANUAL (ADMIN)
    ├─ Admin entra a panel: panel_admin
    ├─ Selecciona: Listar Pedidos
    ├─ Ve pedido estado 'pendiente' con comprobante
    ├─ Verifica comprobante (QR válido, referencia)
    ├─ Hace clic: "Marcar como completado"
    └─ Pedido cambia a estado 'completado'
```

---

## Capas de la Aplicación

### 1. Capa de Routing

**Ubicación**: `publico/index.php`

**Responsabilidades**:
- Recibir y parsear request HTTP
- Determinar qué controlador instanciar
- Manejar errores de routing

```php
// Switch statement como router simple
switch ($accion) {
    case 'catalogo':
        $controlador = new ControladorProductos();
        $controlador->catalogo();
        break;
    case 'iniciar_sesion':
        $controlador = new ControladorAutenticacion();
        $controlador->iniciarSesion();
        break;
    // ... más casos
    default:
        require_once __DIR__ . '/../aplicacion/vistas/productos/index.php';
}
```

**Ventajas**:
- Simple y directo
- Fácil de entender

**Limitaciones**:
- No maneja URLs RESTful
- No soporta regex en rutas
- Case statement largo

**Mejora futura**: Implementar router class para abstraer esta lógica.

---

### 2. Capa de Controladores

**Ubicación**: `aplicacion/controladores/`

**Patrones Implementados**:

#### 2.1 Patrón Strategy (Autenticación)

```php
class ControladorAutenticacion {
    private $modelo;
    
    public function __construct() {
        $this->modelo = new ModeloUsuarios();
    }
    
    // Inyección de dependencia
    // Permite cambiar ModeloUsuarios por mock en tests
}
```

#### 2.2 Patrón Guard Clause (Autorización)

```php
public function panel() {
    // Guard: Si no es admin, sale temprano
    if (!isset($_SESSION['usuario']) || 
        $_SESSION['usuario']['rol'] !== 'admin') {
        header('Location: /Tienda_ropa/publico/index.php');
        exit();
    }
    
    // Rest del código solo ejecuta si pasó el guard
    // Código más legible, menos anidación
}
```

#### 2.3 Patrón Template Method (Ciclo de Vida)

```php
public function catalogo() {
    // 1. Obtener datos
    $productos = $this->modelo->obtenerProductos();
    
    // 2. Formatear para Vista
    $productosFormateados = array_map(function($p) {
        $p['precio_formateado'] = '$' . number_format($p['precio'], 2);
        return $p;
    }, $productos);
    
    // 3. Pasar a vista
    require_once __DIR__ . '/../vistas/productos/catalogo.php';
}
```

---

### 3. Capa de Modelos

**Ubicación**: `aplicacion/modelos/`

**Patrones**:

#### 3.1 Active Record (Simplificado)

```php
class ModeloProductos {
    private $conexion;
    
    // Constructor: Crea conexión PDO
    public function __construct() {
        $this->conexion = new PDO(...);
    }
    
    // CRUD Methods
    public function obtenerProductos() { ... }
    public function obtenerPorId($id) { ... }
    public function añadirProducto(...) { ... }
    public function actualizarProducto($id, ...) { ... }
    public function eliminarProducto($id) { ... }
}
```

#### 3.2 Query Builder Minimal

```php
private function construirQueryBusqueda($filtros) {
    $sql = "SELECT * FROM productos WHERE 1=1 ";
    $params = [];
    
    if (!empty($filtros['termino'])) {
        $sql .= "AND (nombre LIKE :termino OR descripcion LIKE :termino) ";
        $params[':termino'] = '%' . $filtros['termino'] . '%';
    }
    
    if (!empty($filtros['categoria'])) {
        $sql .= "AND categoria = :categoria ";
        $params[':categoria'] = $filtros['categoria'];
    }
    
    return [$sql, $params];
}
```

#### 3.3 Error Handling con Try-Catch

```php
public function obtenerPorId($id) {
    try {
        $query = "SELECT * FROM productos WHERE id = :id";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error DB: " . $e->getMessage());
        return false;  // Silenciar error para usuario
    }
}
```

---

### 4. Capa de Vistas

**Ubicación**: `aplicacion/vistas/`

**Estructura**:

```
vistas/
├── plantillas/           (Componentes reutilizables)
│   ├── cabecera.php     (Header global)
│   └── pie.php          (Footer global)
│
├── usuarios/            (Rutas autenticación)
├── productos/           (Catálogo)
├── carrito/             (Carrito y checkout)
├── pedidos/             (Confirmaciones)
├── admin/               (Panel administrativo)
└── componentes/         (Botones, cards, etc) - FUTURO
```

**Principios**:

1. **Minimal Logic**
```php
<!-- ✅ BIEN -->
<?php foreach ($productos as $p): ?>
    <div><?php echo htmlspecialchars($p['nombre']); ?></div>
<?php endforeach; ?>

<!-- ❌ MAL -->
<?php 
    $total = 0;
    $db = new PDO(...);
    $resultado = $db->query("SELECT * FROM productos WHERE...");
    foreach ($resultado as $p) {
        if ($p['descuento'] > 0) {
            $precio = $p['precio'] * (1 - $p['descuento']/100);
        }
        // ...
    }
?>
```

2. **Escape de datos**
```php
<!-- ✅ Contra XSS -->
<?php echo htmlspecialchars($usuario['nombre'], ENT_QUOTES, 'UTF-8'); ?>

<!-- ✅ Alternativa -->
<?php echo $usuario['nombre'] |> escape; // Si hubiera filtros PHP -->
```

3. **Usar variables del Controller**
```php
<!-- Controller pasa: $productos, $usuario, $carrito -->
<!-- Vista accede como: $productos, $usuario, $carrito -->

<?php require_once 'plantillas/cabecera.php'; ?>
<h1>Bienvenido <?php echo htmlspecialchars($usuario['nombre']); ?></h1>
```

---

### 5. Capa de Configuración

**Ubicación**: `configuracion/config.php`

**Responsabilidades**:

```php
<?php
// 1. Definiciones de constantes
define('DB_HOST', 'localhost');
define('DB_NAME', 'tienda');

// 2. Conexión PDO centralizada
$db = new PDO(...);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 3. Sesión
session_start();

// 4. Error reporting
ini_set('display_errors', 1);  // Desarrollo
// ini_set('display_errors', 0);  // Producción

// 5. Timezone
date_default_timezone_set('America/Lima');  // O la del proyecto

// 6. Headers de seguridad (si fuera necesario)
// header("X-Frame-Options: DENY");
// header("X-Content-Type-Options: nosniff");
?>
```

**Por qué centralizado**:
- ✅ Un solo lugar para cambiar credenciales
- ✅ $db disponible globalmente
- ✅ Fácil cambiar entre entornos (dev/prod)

---

## Patrones de Diseño

### 1. Singleton (Instancia Única de BD)

```php
// config.php
global $db;  // Variable global única
$db = new PDO(...);

// Controllers
class ControladorCarrito {
    public function __construct() {
        global $db;  // Acceso a singleton
        $this->db = $db;
    }
}
```

**Ventaja**: Una única conexión reutilizada.
**Desventaja**: Variables globales (testing complicado).

---

### 2. Model-View-Controller (MVC)

✅ Implementado en todo el proyecto.

---

### 3. Template Method

Ciclo repetido en controllers:

```
1. Validar entrada
2. Obtener datos modelo
3. Procesar datos
4. Incluir vista
```

---

### 4. Factory Pattern (Futuro)

```php
class ControllerFactory {
    public static function crear($tipo) {
        switch ($tipo) {
            case 'productos':
                return new ControladorProductos();
            case 'carrito':
                return new ControladorCarrito();
            // ...
        }
    }
}

// Uso: $ctrl = ControllerFactory::crear('productos');
```

---

### 5. Repository Pattern (Futuro)

```php
interface RepositorioProductos {
    public function obtenerTodos();
    public function obtenerPorId($id);
    public function crear(...);
    public function actualizar($id, ...);
}

class RepositorioProductosMysql implements RepositorioProductos {
    // Implementación actual del modelo
}

class RepositorioProductosMemoria implements RepositorioProductos {
    // Para testing
}
```

---

## Gestión de Dependencias

### Patrón Actual: Manual Instantiation

```php
class ControladorCarrito {
    public function __construct() {
        global $db;
        $this->db = $db;
        $this->modeloProducto = new ModeloProductos();  // Manual
    }
}
```

**Ventajas**:
- Simple, sin librería externa
- Fácil de entender

**Desventajas**:
- Tight coupling
- Testing complicado (no puedes mockear ModeloProductos)

### Patrón Mejorado: Inyección de Dependencias

```php
class ControladorCarrito {
    public function __construct(ModeloProductos $modeloProducto = null) {
        global $db;
        $this->db = $db;
        $this->modeloProducto = $modeloProducto ?? new ModeloProductos();
    }
}

// Uso normal
$ctrl = new ControladorCarrito();

// Testing
$modeloMock = Mock::create('ModeloProductos');
$ctrl = new ControladorCarrito($modeloMock);
```

---

## Escalabilidad Horizontal

### Hoy (Aplicación Monolítica)

```
     ┌─────────────────────────┐
     │    Navegadores          │
     └────────────┬────────────┘
                  │ HTTP
                  ▼
     ┌─────────────────────────┐
     │   Servidor Único        │
     │ (Apache + PHP)          │
     │                         │
     │ ├─ publico/             │
     │ ├─ aplicacion/          │
     │ ├─ configuracion/       │
     │ └─ [Session en memoria] │
     └────────────┬────────────┘
                  │ SQL
                  ▼
     ┌─────────────────────────┐
     │    MySQL (localhost)    │
     └─────────────────────────┘
```

### Mañana (Escalado Horizontal)

```
     ┌──────────────────────────────────┐
     │     Load Balancer (Nginx)        │
     │  Round-Robin entre 3 servidores  │
     └──┬──────────────────────┬───────┬┘
        │                      │       │
        ▼                      ▼       ▼
    ┌────────┐           ┌────────┐ ┌──────┐
    │Server 1│           │Server 2│ │Server│
    │(Apache)│           │(Apache)│ │3     │
    │+PHP    │           │+PHP    │ │(...)│
    │(Sesión │───────┬───│(Sesión │─┼───┐ │
    │Share)  │       │   │Redis)  │   │   │
    └────────┘       │   └────────┘ └──────┘
                     │
                     ▼
            ┌─────────────────┐
            │  Redis Cluster  │
            │  (Session Store)│
            └────────┬────────┘
                     │
                     ▼
            ┌─────────────────┐
            │  MySQL Master   │
            │  (Replication)  │
            │     + Slaves    │
            └─────────────────┘
```

### Cambios Necesarios

#### 1. Sesiones Distribuidas con Redis

```php
// config.php
ini_set('session.save_handler', 'redis');
ini_set('session.save_path', 'tcp://redis-server:6379');

// Ahora $_SESSION persiste en Redis, disponible en cualquier servidor
```

#### 2. Subida de Archivos Centralizada (S3 / NFS)

```php
// Hoy: Guardar en publico/recursos/imagenes/
// Mañana: Guardar en S3 o NFS compartida

// Si NFS:
$rutaCompartida = '/mnt/nfs/imagenes/';
move_uploaded_file($_FILES['imagen']['tmp_name'], 
                  $rutaCompartida . $nombreArchivo);

// Si S3:
$s3 = new S3Client(...);
$s3->putObject([
    'Bucket' => 'tienda-ropa-uploads',
    'Key' => 'productos/' . $nombreArchivo,
    'Body' => file_get_contents($_FILES['imagen']['tmp_name'])
]);
```

#### 3. Caché Distribuido

```php
// Redis para caché de productos (que no cambia frecuentemente)
$redis = new Redis();
$redis->connect('redis-server', 6379);

$cacheKey = 'productos:lista';
if ($redis->exists($cacheKey)) {
    $productos = json_decode($redis->get($cacheKey), true);
} else {
    $productos = $modelo->obtenerProductos();
    $redis->setex($cacheKey, 3600, json_encode($productos));  // 1h
}
```

#### 4. Base de Datos Replicada

```
Write queries → Master (localhost)
Read queries → Slave (por redundancia)

// En modelo:
private $dbWrite;   // Master
private $dbRead;    // Slave (o master si no hay replica)

public function obtenerProductos() {
    // Read query: usa Slave
    return $this->dbRead->query("SELECT * FROM productos");
}

public function añadirProducto(...) {
    // Write query: usa Master
    return $this->dbWrite->exec("INSERT INTO productos...");
}
```

---

**FIN DE ARQUITECTURA.MD**
