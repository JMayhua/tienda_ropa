# 🛍️ Tienda Ropa - E-commerce Backend System

**Plataforma de comercio electrónico especializada en venta de prendas textiles, desarrollada con arquitectura MVC en PHP puro, diseñada para escalabilidad, seguridad y mantenibilidad.**

---

## 📋 Tabla de Contenidos

- [Descripción General](#descripción-general)
- [Contexto del Problema](#contexto-del-problema)
- [Tecnologías Utilizadas](#tecnologías-utilizadas)
- [Arquitectura del Sistema](#arquitectura-del-sistema)
- [Diseño Técnico](#diseño-técnico)
- [Metodología](#metodología)
- [Instalación y Configuración](#instalación-y-configuración)
- [Guía de Ejecución](#guía-de-ejecución)
- [Retos Técnicos y Soluciones](#retos-técnicos-y-soluciones)
- [Mejoras Futuras](#mejoras-futuras)
- [Decisiones Técnicas Clave](#decisiones-técnicas-clave)
- [Autor](#autor)

---

## Descripción General

**Tienda Ropa** es una plataforma de comercio electrónico especializada en la venta de prendas textiles. Implementa un flujo completo de e-commerce que incluye:

- **Catálogo dinámico** de productos con filtrado y búsqueda
- **Sistema de autenticación robusto** con validación de credenciales
- **Carrito de compras persistente** basado en sesiones
- **Gestión de pedidos** con seguimiento de estado
- **Panel administrativo** con funcionalidades de CRUD
- **Sistema de pagos** integrado con QR (Yape)
- **Control de inventario** y gestión de descuentos

### Características Principales

```
✅ Arquitectura MVC desacoplada y mantenible
✅ Autenticación con hash bcrypt (PASSWORD_DEFAULT)
✅ Control granular de acceso (RBAC: Cliente/Admin)
✅ Validación de datos en capas (controlador + modelo)
✅ Manejo robusto de excepciones (PDOException)
✅ Gestión de transacciones en operaciones críticas
✅ Logging estructurado para debugging
✅ Protección contra inyección SQL (prepared statements)
```

---

## Contexto del Problema

### Necesidad de Negocio

El proyecto surge de la necesidad de crear una **plataforma de venta online especializada en textiles**, que permita a pequeños y medianos emprendimientos:

1. **Reducir dependencia de canales físicos** mediante presencia digital
2. **Automatizar procesos de venta** (catálogo, carrito, pedidos)
3. **Gestionar inventario dinámicamente** con control de stock
4. **Procesar pagos digitales** de manera segura
5. **Diferenciarse con experiencia de usuario** enfocada en productos textiles

### Desafíos Técnicos Resueltos

| Desafío | Problema | Solución |
|---------|----------|----------|
| **Seguridad de autenticación** | Vulnerabilidad de credenciales débiles | Hash bcrypt + verificación `password_verify()` |
| **Persistencia de carrito** | Pérdida de datos en navegación | Sesiones PHP con array asociativo |
| **Concurrencia de inventario** | Sobreventa en operaciones simultáneas | Transacciones ACID + verificación de stock pre-venta |
| **Inyección SQL** | Vulnerabilidad de bases de datos | Prepared statements con placeholders |
| **Escalabilidad de datos** | Crecimiento sin rendimiento | Índices en PKs y FKs + query optimization |
| **Mantenibilidad del código** | Lógica entrelazada | Patrón MVC con separación de responsabilidades |

---

## Tecnologías Utilizadas

### Backend

| Componente | Tecnología | Versión | Justificación |
|-----------|-----------|---------|--------------|
| **Runtime** | PHP | 8.2.12 | Soporte a features modernas, mejor performance |
| **Framework** | PHP Puro (MVC) | Custom | Control total, sin overhead de framework pesado |
| **ORM/Query Builder** | PDO (PHP Data Objects) | Nativa | Abstracción de BD, prepared statements |
| **Hash de Contraseñas** | `password_hash()` (bcrypt) | PASSWORD_DEFAULT | Estándar OWASP, resistente a ataques |

### Fronted (Vistas)

| Componente | Tecnología | Rol |
|-----------|-----------|-----|
| **Templating** | PHP + HTML | Renderizado server-side |
| **Estilos** | CSS3 | Diseño responsive y moderno |
| **Interactividad** | Vanilla JavaScript | Validación client-side, UX mejorada |

### Base de Datos

| Componente | Tecnología | Especificación |
|-----------|-----------|---------------|
| **Motor** | MySQL | Ver 10.4.32 (MariaDB) |
| **Charset** | UTF-8 MB4 | Soporte completo Unicode |
| **Colation** | utf8_spanish_ci | Búsquedas con acentos correctas |

### Infraestructura

| Componente | Tecnología | Propósito |
|-----------|-----------|----------|
| **Servidor Web** | Apache (XAMPP) | Hosting local/desarrollo |
| **Gestor de BD** | phpMyAdmin 5.2.1 | Administración de datos |

### Herramientas y Utilities

```
generar_hash.php    - Utilidad para generar hashes bcrypt seguros
```

---

## Arquitectura del Sistema

### Patrón Arquitectónico: MVC (Model-View-Controller)

Esta arquitectura separa las responsabilidades en tres capas claramente diferenciadas:

```
REQUEST (index.php)
    ↓
ROUTING (switch statement)
    ↓
CONTROLLER (Lógica de negocio)
    ↓
MODEL (Acceso a datos)
    ↓
DATABASE (MySQL)
    ↓
VIEW (Renderizado HTML)
    ↓
RESPONSE (HTML + CSS + JS)
```

### Estructura de Directorios y Justificación

```
Tienda_ropa/
│
├── 📄 publico/                        # [PUBLIC ROOT] Punto de entrada único
│   ├── index.php                      # Router central (front controller)
│   │   └── Descripción: Maneja todas las rutas, elimina index.php
│   │                    de URLs, proporciona una interfaz limpia
│   │
│   └── recursos/                      # Activos estáticos
│       ├── css/                       # Hojas de estilos
│       │   ├── estilos.css           # Estilos globales
│       │   ├── catalogo.css          # Específicos de catálogo
│       │   ├── carrito.css           # UI del carrito
│       │   ├── iniciar_sesion.css    # Formularios de auth
│       │   └── panel.css             # Dashboard admin
│       │
│       ├── imagenes/                 # Assets de productos
│       │   └── [Imágenes de productos dinamizables]
│       │
│       └── js/                       # Scripts client-side
│           └── catalogo.js           # Interactividad de productos
│
├── 🗂️  aplicacion/                    # [APPLICATION LAYER] Lógica de negocio
│   │
│   ├── controladores/                # Controllers (Orquestación)
│   │   ├── ControladorAutenticacion.php   # Autenticación/Registro
│   │   │   └── Métodos: iniciarSesion(), registrarse(), registrarAdmin()
│   │   │                cerrarSesion()
│   │   │
│   │   ├── ControladorProductos.php      # Gestión de catálogo
│   │   │   └── Métodos: catalogo(), mostrarProducto()
│   │   │
│   │   ├── ControladorCarrito.php        # Lógica de carrito
│   │   │   └── Métodos: añadirAlCarrito(), verCarrito()
│   │   │                eliminarDelCarrito(), vaciarCarrito()
│   │   │                finalizarCompra()
│   │   │
│   │   ├── ControladorPedidos.php        # Seguimiento de pedidos
│   │   │   └── Métodos: verPedidos(), verDetallePedido()
│   │   │
│   │   ├── ControladorPagos.php          # Integración de pagos
│   │   │   └── Métodos: pagoYape(), procesarPago()
│   │   │
│   │   ├── ControladorAdmin.php          # Panel administrativo
│   │   │   └── Métodos: panel(), listarProductos()
│   │   │                listarPedidos(), listarUsuarios()
│   │   │
│   │   └── ControladorPerfil.php         # Gestión de perfil
│   │       └── Métodos: verPerfil(), editarPerfil()
│   │
│   ├── modelos/                      # Models (Acceso a datos)
│   │   ├── ModeloUsuarios.php             # CRUD de usuarios
│   │   │   └── Métodos: registrar(), iniciarSesion()
│   │   │                obtenerPorId(), actualizar()
│   │   │
│   │   ├── ModeloProductos.php            # CRUD de productos
│   │   │   └── Métodos: obtenerProductos(), obtenerPorId()
│   │   │                añadirProducto(), actualizarProducto()
│   │   │                eliminarProducto(), contarProductos()
│   │   │
│   │   ├── ModeloCarrito.php              # Lógica de carrito en BD
│   │   │   └── Métodos: crearSesionCarrito(), obtenerCarrito()
│   │   │
│   │   └── ModeloPedidos.php              # CRUD de pedidos
│   │       └── Métodos: crearPedido(), obtenerPedido()
│   │                    actualizarEstado(), contarPedidos()
│   │
│   └── vistas/                       # Views (Presentación)
│       ├── plantillas/               # Componentes reutilizables
│       │   ├── cabecera.php         # Header nav global
│       │   └── pie.php              # Footer global
│       │
│       ├── usuarios/                # Templates de autenticación
│       │   ├── iniciar_sesion.php   # Formulario login
│       │   ├── registrarse.php      # Formulario signup cliente
│       │   ├── registrar_admin.php  # Formulario signup admin
│       │   ├── ver_perfil.php       # Dashboard usuario
│       │   └── editar_perfil.php    # Edición de datos
│       │
│       ├── productos/               # Templates de catálogo
│       │   ├── catalogo.php         # Listado de productos
│       │   ├── mostrar.php          # Detalle de producto
│       │   ├── index.php            # Landing page
│       │   └── nosotros.php         # About us
│       │
│       ├── carrito/                 # Templates del carrito
│       │   ├── ver.php              # Vista del carrito
│       │   ├── detalle_pedido.php   # Resumen antes de pago
│       │   └── pago_yape.php        # Integración de pago
│       │
│       ├── pedidos/                 # Templates de pedidos
│       │   ├── confirmacion.php     # Confirmación post-compra
│       │   └── metodo_pago.php      # Selección de método
│       │
│       └── admin/                   # Panel administrativo
│           ├── panel.php            # Dashboard principal
│           ├── panel_admin.php      # Vista alterna
│           ├── gestionar_productos.php   # CRUD productos
│           ├── listar_productos.php      # Listado
│           ├── gestionar_usuarios.php    # CRUD usuarios
│           ├── listar_usuarios.php       # Listado usuarios
│           ├── listar_pedidos.php        # Historial de pedidos
│           ├── cambiar_estado.php        # Cambio de estado pedido
│           └── ver.php              # Vista genérica
│
├── 📁 configuracion/                 # [CONFIGURATION LAYER]
│   └── config.php                    # Configuración centralizada
│       ├── Credenciales BD (DB_HOST, DB_NAME, etc)
│       ├── Inicialización de sesiones
│       ├── Error reporting (desarrollo vs producción)
│       └── Conexión PDO global
│
├── 📊 BASE DE DATOS/                 # [DATA LAYER]
│   └── tienda_ropa.sql               # Dump SQL con estructura e inserts
│       ├── Tabla: usuarios
│       ├── Tabla: productos
│       ├── Tabla: pedidos
│       └── Tabla: detalles_pedido
│
├── 📝 comprobantes/                  # Almacenamiento de comprobantes
│   └── [QR de pagos Yape]
│
├── 📦 vendor/                        # Dependencias (si aplica)
│
├── 🔧 generar_hash.php               # Utilidad para hashes
│
└── 📄 README.md                      # Esta documentación
```

### Flujo de Solicitud (Request Flow)

```
┌─────────────────────────────────────────────────────────────┐
│                    USUARIO EN NAVEGADOR                      │
└────────────────────┬────────────────────────────────────────┘
                     │
                     ▼
        ┌────────────────────────────┐
        │   publico/index.php        │
        │  [Front Controller]        │
        │ - Obtiene acción (GET)     │
        │ - Router (switch/case)     │
        │ - Instancia Controlador    │
        └────────┬───────────────────┘
                 │
                 ▼
        ┌────────────────────────────────┐
        │   ControladorXXX.php           │
        │  [Orquestación de negocio]     │
        │ - Valida entrada (POST/GET)    │
        │ - Instancia Modelo             │
        │ - Llama métodos del modelo     │
        │ - Prepara datos para vista     │
        │ - Control de acceso (RBAC)     │
        └────────┬──────────────────────┘
                 │
                 ▼
        ┌────────────────────────────────┐
        │   ModeloXXX.php                │
        │  [Lógica de acceso a datos]    │
        │ - Prepared statements          │
        │ - Validación de datos          │
        │ - Manejo de excepciones        │
        │ - Logging de operaciones       │
        └────────┬──────────────────────┘
                 │
                 ▼
        ┌────────────────────────────────┐
        │   mysql (configuracion/        │
        │   config.php - PDO)            │
        │  [Base de Datos]               │
        │ - Transacciones ACID           │
        │ - Índices optimizados          │
        │ - Constraints de integridad    │
        └────────┬──────────────────────┘
                 │
                 ▼  (Resultado)
        ┌────────────────────────────────┐
        │   vista/XXX/archivo.php        │
        │  [Renderizado HTML]            │
        │ - Inyección de datos           │
        │ - Escape para XSS              │
        │ - CSS + JS incluido            │
        └────────┬──────────────────────┘
                 │
                 ▼
        ┌────────────────────────────────┐
        │   Respuesta HTTP (HTML)        │
        │  + CSS + JavaScript            │
        └────────────────────────────────┘
                 │
                 ▼
        ┌────────────────────────────────┐
        │    Navegador del Usuario        │
        │  (Renderiza y ejecuta)         │
        └────────────────────────────────┘
```

### Justificación de la Arquitectura MVC

| Ventaja | Implementación |
|---------|---------------|
| **Separación de responsabilidades** | Cada capa tiene una única responsabilidad clara |
| **Reutilización de código** | Modelos compartidos entre controladores |
| **Testabilidad** | Fácil de mockear modelos para unit tests |
| **Mantenibilidad** | Cambios en BD no afectan vistas; cambios visuales no tocan lógica |
| **Escalabilidad** | Es simple agregar nuevas funcionalidades sin afectar existentes |
| **Debugging** | Stack trace claro del flujo de ejecución |

---

## Diseño Técnico

### Modelo de Base de Datos (E-R)

```sql
┌─────────────────────┐
│     USUARIOS        │
├─────────────────────┤
│ id (PK) [INT]       │─┐
│ nombre [VARCHAR]    │ │
│ email [VARCHAR]     │ │
│ password [VARCHAR]  │ │     ┌──────────────────────┐
│ rol [ENUM]          │ │────<│      PEDIDOS          │
│ created_at [DATE]   │ │     ├──────────────────────┤
└─────────────────────┘ │     │ id (PK) [INT]        │
                        │     │ usuario_id (FK) [INT]│
                        │     │ fecha [DATETIME]     │
                         ─────│ total [DECIMAL]      │
                              │ estado [ENUM]        │
                              │ comprobante [VARCHAR]│
                              └──────┬───────────────┘
                                     │
                    ┌────────────────┴────────────────┐
                    │                                 │
         ┌──────────▼──────────┐        ┌────────────▼──────────┐
         │ DETALLES_PEDIDO     │        │   PRODUCTOS          │
         ├─────────────────────┤        ├──────────────────────┤
         │ id (PK) [INT]       │        │ id (PK) [INT]        │
         │ pedido_id (FK) [INT]│─┐      │ nombre [VARCHAR]     │
         │ producto_id (FK)────┼┼──────>│ descripcion [TEXT]   │
         │ cantidad [INT]      │ │      │ precio [DECIMAL]     │
         │ precio_unitario [DEC│ │      │ categoria [VARCHAR]  │
         └─────────────────────┘ │      │ talla [VARCHAR]      │
                                 │      │ color [VARCHAR]      │
                                  ─────>│ imagen [VARCHAR]     │
                                        │ stock [INT]          │
                                        │ descuento [INT]      │
                                        └──────────────────────┘
```

### Especificaciones Técnicas de Tablas

#### 1. USUARIOS
```sql
CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,           -- Hash bcrypt
    rol ENUM('cliente', 'admin') DEFAULT 'cliente'
);
-- Índice: UNIQUE email (previene duplicados, acelera búsquedas por email)
```

**Decisión técnica**: Campo `password` de 255 caracteres para acomodar hash bcrypt completo ($2y$10$...).

#### 2. PRODUCTOS
```sql
CREATE TABLE productos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,            -- Precisión monetaria
    categoria VARCHAR(50),
    talla VARCHAR(10),                        -- Flexible para tallas
    color VARCHAR(20),
    imagen VARCHAR(255),                      -- Ruta relativa
    stock INT NOT NULL,
    descuento INT DEFAULT 0                   -- Porcentaje (0-100)
);
-- Índice: PRIMARY KEY id (búsquedas rápidas)
```

#### 3. PEDIDOS
```sql
CREATE TABLE pedidos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL FOREIGN KEY -> usuarios(id),
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL,
    estado ENUM('pendiente', 'completado', 'cancelado') DEFAULT 'pendiente',
    comprobante VARCHAR(255)                  -- UUID de comprobante Yape
);
```

**Decisión técnica**: La columna `estado` usa ENUM para valores predefinidos, permitiendo validación a nivel BD.

#### 4. DETALLES_PEDIDO
```sql
CREATE TABLE detalles_pedido (
    id INT PRIMARY KEY AUTO_INCREMENT,
    pedido_id INT NOT NULL FOREIGN KEY -> pedidos(id),
    producto_id INT NOT NULL FOREIGN KEY -> productos(id),
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL   -- Snapshot del precio
);
```

**Decisión técnica**: Almacenar `precio_unitario` en historial desacopla cambios futuros de precios de pedidos antiguos.

### Seguridad Implementada

#### 1. Autenticación de Credenciales

```php
// Hash seguro con bcrypt (PASSWORD_DEFAULT = bcrypt)
$hash = password_hash($contrasena, PASSWORD_DEFAULT);

// Verificación segura contra timing attacks
if (password_verify($contrasena, $hash)) {
    // Sesión autenticada
}
```

**Ventajas**:
- ✅ Resistente a rainbow tables (salting automático)
- ✅ Adaptativo (costo computacional ajustable)
- ✅ Resistente a timing attacks (`password_verify` usa comparación de tiempo constante)

#### 2. Prevención de Inyección SQL

```php
// ❌ INSEGURO
$sql = "SELECT * FROM usuarios WHERE email = '" . $_GET['email'] . "'";

// ✅ SEGURO - Prepared Statements con placeholders
$query = "SELECT * FROM usuarios WHERE email = :email";
$stmt = $db->prepare($query);
$stmt->execute([':email' => $email]);
```

**Implementación en proyecto**: Todos los modelos usan placeholders (`:col`) en prepared statements.

#### 3. Control de Acceso (RBAC)

```php
private function verificarAdmin() {
    if (!isset($_SESSION['usuario']) || 
        $_SESSION['usuario']['rol'] !== 'admin') {
        header('Location: /Tienda_ropa/publico/index.php');
        exit();
    }
}
```

**Roles implementados**:
- `cliente`: Acceso a catálogo, carrito, pedidos personales
- `admin`: Acceso a panel de control, CRUD de productos, usuarios, pedidos

#### 4. Regeneración de ID de Sesión (Session Fixation Prevention)

```php
if ($usuario) {
    $_SESSION['usuario'] = $usuario;
    session_regenerate_id(true);  // Genera novo ID, elimina antiguo
}
```

#### 5. Validación de Datos en Múltiples Capas

```
┌─────────────────────────────────────┐
│   Controller (Validación Básica)     │
│   - Tipos de dato                   │
│   - Rangos numéricos                │
│   - Existencia de IDs               │
└──────────┬──────────────────────────┘
           │
           ▼
┌─────────────────────────────────────┐
│   Modelo (Validación de Negocio)     │
│   - Reglas de dominio               │
│   - Duplicidades                    │
│   - Integridad referencial          │
└──────────┬──────────────────────────┘
           │
           ▼
┌─────────────────────────────────────┐
│   Base de Datos (Constraints)        │
│   - PRIMARY KEY                     │
│   - UNIQUE                          │
│   - FOREIGN KEY                     │
│   - CHECK                           │
└─────────────────────────────────────┘
```

#### 6. Manejo de Excepciones

```php
try {
    // Operación BD
    $stmt->execute($data);
} catch (PDOException $e) {
    // Log en servidor (no mostrar al usuario en producción)
    error_log("Error al crear pedido: " . $e->getMessage());
    // Mensaje genérico al usuario
    $_SESSION['error'] = "Error en la operación. Intenta nuevamente.";
}
```

#### 7. Protección Contra XSS

```php
// Al renderizar datos de usuario en HTML
echo htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8');
```

### API Endpoints (Routing)

El sistema utiliza routing basado en parámetro `accion` (GET-based):

| Acción | Método | Requiere Rol | Descripción |
|--------|--------|-------------|-------------|
| `inicio` | GET | - | Landing page |
| `catalogo` | GET | - | Listado de productos |
| `mostrar_producto` | GET | - | Detalle de producto |
| `iniciar_sesion` | GET/POST | - | Login |
| `registrarse` | GET/POST | - | Registro de cliente |
| `registrar_admin` | GET/POST | admin | Registro de administrador |
| `cerrar_sesion` | GET | cliente/admin | Logout |
| `añadir_al_carrito` | GET | cliente | Agregar producto al carrito |
| `ver_carrito` | GET | cliente | Mostrar carrito |
| `eliminar_del_carrito` | GET | cliente | Remover producto del carrito |
| `vaciar_carrito` | GET | cliente | Vaciar carrito completo |
| `finalizar_compra` | POST | cliente | Crear pedido |
| `ver_pedidos` | GET | cliente | Historial de pedidos |
| `ver_detalles_pedido` | GET | cliente | Detalle de pedido específico |
| `pago_yape` | GET/POST | cliente | Integración de pagos |
| `panel_admin` | GET | admin | Dashboard administrativo |
| `listar_productos` | GET | admin | Gestión de productos |
| `listar_usuarios` | GET | admin | Gestión de usuarios |
| `listar_pedidos` | GET | admin | Historial de pedidos |
| `ver_perfil` | GET | cliente | Perfil de usuario |
| `editar_perfil` | GET/POST | cliente | Editar datos personales |

### Casos de Uso Críticos (Use Cases)

#### UC-01: Registro e Inicio de Sesión
```
Actor: Usuario anónimo
Precondiciones: Usuario accede a /publico/index.php?accion=registrarse

1. Usuario completa formulario (nombre, email, password)
2. Sistema valida datos (email único, password strength)
3. Sistema genera hash bcrypt de contraseña
4. Sistema inserta en tabla usuarios
5. Usuario redirigido a login
6. Usuario ingresa email + password
7. Sistema busca usuario por email
8. Sistema verifica hash con password_verify()
9. Si es válido: se crea sesión + regenera ID
10. Usuario redirigido según rol (panel_admin o catalogo)

Postcondiciones: Sesión activa con datos en $_SESSION
Excepciones: Email duplicado, formato inválido, BD no disponible
```

#### UC-02: Flujo de Compra
```
Actor: Cliente autenticado
Precondiciones: Usuario en rol cliente con sesión activa

1. Cliente explora catálogo (/accion=catalogo)
2. Cliente selecciona producto (/accion=mostrar_producto?id=X)
3. Cliente da click "Añadir al Carrito" (/accion=añadir_al_carrito?id=X)
   - Sistema valida que usuario esté autenticado
   - Sistema agrega/incrementa cantidad en $_SESSION['carrito']
4. Cliente revisa carrito (/accion=ver_carrito)
   - Sistema obtiene datos de producto para cada item del carrito
   - Calcula subtotales y total
5. Cliente inicia checkout (/accion=finalizar_compra - POST)
   - Controlador valida que carrito no esté vacío
   - Modelo verifica stock disponible de cada producto
   - Inicia transacción DB
   - Crea pedido en tabla pedidos
   - Crea registros en detalles_pedido
   - Actualiza stock de productos
   - Confirm transacción
   - Limpia sesión['carrito']
6. Sistema muestra confirmación y redirecciona a pago
7. Cliente selecciona Yape (/accion=pago_yape)
   - Genera QR con monto total
   - Registra comprobante en tabla pedidos
8. Pedido en estado 'pendiente' hasta confirmación manual de admin

Postcondiciones: Pedido creado, inventario reducido, carrito vacío
Excepciones: Stock insuficiente, error de transacción
```

#### UC-03: Administración de Productos
```
Actor: Administrador
Precondiciones: Usuario logueado con rol admin

Panel Principal: /accion=panel_admin
  - Muestra: Total productos, Total pedidos, Total usuarios
  - Links a módulos de gestión

Listar Productos: /accion=listar_productos
  - Obtiene todos los productos
  - Muestra tabla: ID, Nombre, Precio, Stock, Acciones
  - Acciones disponibles: Editar, Eliminar, Ver detalles

Crear Producto: POST a ControladorAdmin::crearProducto()
  - Valida campos (nombre, precio, stock, etc)
  - Inserta en tabla productos
  - Redirecciona a listar_productos

Editar Producto: GET /accion=... selecciona producto
  - Carga datos actuales
  - Admin edita campos
  - Valida cambios
  - Actualiza tabla productos

Eliminar Producto: DELETE de ControladorAdmin
  - Verifica que no hay dependencias en detalles_pedido
  - Elimina de tabla productos
  - Redirige a listar_productos

Postcondiciones: Catálogo actualizado, cambios persistidos en BD
Excepciones: Producto con pedidos asociados, valor inválido
```

---

## Metodología

### Ciclo de Desarrollo Aplicado

#### 1. **Análisis de Requisitos** (Fase 1)
- Identificación de funcionalidades principales (CRUD productos, carrito, pagos)
- Definición de roles de usuario (cliente/admin)
- Especificación de flujos críticos (compra, autenticación)

#### 2. **Diseño Arquitectónico** (Fase 2)
- Selección del patrón MVC como base
- Diseño del modelo E-R con 4 tablas principales
- Mapping de URLs a controladores (routing system)
- Diseño de capa de seguridad (hash bcrypt, prepared statements)

#### 3. **Implementación por Capas** (Fase 3)
- **Capa de Base de Datos**: Creación de estructura SQL con constraints
- **Capa de Modelos**: Métodos CRUD desacoplados
- **Capa de Controladores**: Orquestación de negocio
- **Capa de Vistas**: Renderizado progresivo

#### 4. **Testing Incremental**
```
Funcionalidad → Mock Data → Test Manual → Refinamiento
```

#### 5. **Documentación Continua**
- Comentarios en código (docblocks PHP)
- Logging de operaciones críticas
- Error handling explícito


### Principios SOLID Aplicados

| Principio | Aplicación |
|-----------|-----------|
| **S**ingle Responsibility | Cada modelo es responsable de su tabla; cada controlador de su dominio |
| **O**pen/Closed | Sistema abierto a nuevos controladores, cerrado para modificación de existentes |
| **L**iskov Substitution | Modelos pueden ser reemplazados por implementaciones alternas |
| **I**nterface Segregation | Controllers dependen de métodos específicos del modelo |
| **D**ependency Inversion | Controllers reciben BD via inyección de dependencia (config.php) |

---

## Instalación y Configuración

### Requisitos Previos

```
✓ PHP 8.0+ (Tested: 8.2.12)
✓ MySQL 5.7+ o MariaDB 10.x
✓ Apache 2.4+ con mod_rewrite
✓ XAMPP 7.x o similar (incluye todas las dependencias)
```

### Paso 1: Clonar / Descargar Proyecto

```bash
# Opción A: Git
git clone https://github.com/tu-usuario/Tienda_ropa.git
cd Tienda_ropa

# Opción B: Descargar ZIP
# 1. Descargar ZIP desde GitHub
# 2. Extraer en C:\xampp\htdocs\Tienda_ropa (Windows)
#    o  /var/www/html/Tienda_ropa (Linux)
```

### Paso 2: Configurar Base de Datos

#### Opción A: Importar SQL completo (Recomendado)

```bash
# 1. Abrir phpMyAdmin en http://localhost/phpmyadmin
# 2. Crear base de datos: tienda
#    - Charset: utf8mb4
#    - Collation: utf8mb4_unicode_ci

# 3. Seleccionar BD 'tienda'
# 4. Ir a pestaña "Importar"
# 5. Seleccionar archivo: BASE DE DATOS/tienda_ropa.sql
# 6. Ejecutar

# O via MySQL CLI:
mysql -u root -p < BASE\ DE\ DATOS/tienda_ropa.sql
```

#### Opción B: Crear manualmente

```sql
CREATE DATABASE IF NOT EXISTS tienda 
  CHARACTER SET utf8mb4 
  COLLATE utf8mb4_unicode_ci;

USE tienda;

-- [Copiar SQL del archivo tienda_ropa.sql]
```

### Paso 3: Configurar Conexión a Base de Datos

Editar `configuracion/config.php`:

```php
<?php
// 🔧 CONFIGURACIÓN DE CONEXIÓN BD
define('DB_HOST', 'localhost');       // Host del servidor MySQL
define('DB_NAME', 'tienda');          // Nombre de la base de datos
define('DB_USER', 'root');            // Usuario MySQL
define('DB_PASSWORD', '');            // Contraseña (vacía en XAMPP default)

// PDO Connection
try {
    $db = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASSWORD
    );
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

session_start();

// ⚠️ En desarrollo (desactivar en producción)
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
```

**Para producción**, cambiar:
```php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/error.log');
```

### Paso 4: Permisos de Directorios

```bash
# Directorios que necesitan escritura:
chmod 755 publico/recursos/imagenes/
chmod 755 comprobantes/
chmod 755 logs/  (crear si no existe)
```

### Paso 5: Verificar Instalación

```bash
# 1. Iniciar Apache y MySQL en XAMPP
# 2. Abrir navegador: http://localhost/Tienda_ropa/publico/

# Debe mostrar: Landing page con catálogo y opción de login
```

---

## Guía de Ejecución

### Ejecución Local (XAMPP)

#### Inicio del Entorno

```bash
# Windows
1. Abrir XAMPP Control Panel
2. Click en "Start" para Apache
3. Click en "Start" para MySQL

# macOS/Linux
sudo /Applications/XAMPP/xamppfiles/bin/apachectl start
sudo /Applications/XAMPP/xamppfiles/bin/mysqld_safe &
```

#### Acceder a la Aplicación

```
Dirección:  http://localhost/Tienda_ropa/publico/
Puerto:     80 (HTTP estándar)
```

### Flujo de Navegación

#### Para Cliente

```
1. http://localhost/Tienda_ropa/publico/
   └─> Landing page con catálogo

2. Registrarse
   └─> /publico/index.php?accion=registrarse
   └─> Completar: Nombre, Email, Password
   └─> Crear cuenta
   └─> Redirige a login

3. Iniciar Sesión
   └─> /publico/index.php?accion=iniciar_sesion
   └─> Email + Password
   └─> Redirecciona a catálogo

4. Ver Catálogo
   └─> /publico/index.php?accion=catalogo
   └─> Click en producto para detalles

5. Detalles de Producto
   └─> /publico/index.php?accion=mostrar_producto&id=1
   └─> Ver descripción, precio, talla, color
   └─> "Añadir al Carrito"

6. Ver Carrito
   └─> /publico/index.php?accion=ver_carrito
   └─> Ver cantidad, subtotales
   └─> "Proceder al Pago" o "Continuar Comprando"

7. Finalizar Compra
   └─> POST a /publico/index.php?accion=finalizar_compra
   └─> Sistema valida stock
   └─> Crea pedido en BD
   └─> Carrito se vacía

8. Seleccionar Método de Pago
   └─> /publico/index.php?accion=pago_yape
   └─> Se genera QR
   └─> Usuario escanea y paga
   └─> Sistema guarda comprobante

9. Ver Pedidos
   └─> /publico/index.php?accion=ver_pedidos
   └─> Lista de pedidos personales
   └─> Ver detalles o estado

10. Perfil de Usuario
    └─> /publico/index.php?accion=ver_perfil
    └─> Ver datos personales
    └─> Opción: Editar perfil
```

#### Para Administrador

```
1. Iniciar Sesión (email: admin@mayhua, password: Admin)
   └─> Sistema detecta rol = 'admin'
   └─> Redirecciona a panel

2. Panel de Administración
   └─> /publico/index.php?accion=panel_admin
   └─> Muestra resumen: Total Productos, Pedidos, Usuarios
   └─> Links a módulos

3. Gestionar Productos
   └─> /publico/index.php?accion=listar_productos
   └─> Tabla con todos los productos
   └─> Acciones: Editar, Eliminar, Ver detalles
   └─> Botón: Agregar nuevo producto

4. Gestionar Usuarios
   └─> /publico/index.php?accion=listar_usuarios
   └─> Tabla con usuarios (clientes + admin)
   └─> Acciones: Editar, Eliminar
   └─> Botón: Registrar nuevo admin

5. Ver Pedidos
   └─> /publico/index.php?accion=listar_pedidos
   └─> Tabla con todos los pedidos
   └─> Estado: Pendiente, Completado, Cancelado
   └─> Click en pedido: Ver detalles + cambiar estado
   └─> Ver comprobante de pago (QR Yape)
```

### Usuarios de Prueba

```sql
-- Cliente
Email:    jose@mayhua
Password: (debe registrarse primero)
Rol:      cliente

-- Administrador
Email:    admin@mayhua
Password: Admin
Rol:      admin
```

### Productos de Prueba

El SQL incluye 10 productos precargados:

1. Camiseta Básica Blanca - $19.99
2. Jeans Slim Fit Azul - $49.99
3. Chaqueta de Cuero Negro - $129.99
4. Vestido Floral Veraniego - $39.99
5. Zapatos Deportivos Negros - $79.99
... (5 más)

---

## Retos Técnicos y Soluciones

### Reto 1: Persistencia del Carrito Sin Base de Datos Dedicada

**Problema**: Necesitar un carrito que persista durante la sesión pero sin crear una tabla de carrito en BD.

**Solución Implementada**:
```php
// Usar sesiones PHP como almacén transitorio
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];  // Array asociativo: [producto_id => cantidad]
}

$_SESSION['carrito'][$id] = isset($_SESSION['carrito'][$id]) 
    ? $_SESSION['carrito'][$id] + 1 
    : 1;
```

**Ventajas**:
- ✅ Rápido (memoria en lugar de BD)
- ✅ Sincronizado automático con sesión HTTP
- ✅ No requiere tabla adicional
- ✅ Se limpia automáticamente al cerrar sesión

**Limitaciones**:
- ❌ No persiste entre sesiones (esperado para e-commerce)
- ❌ Se pierde si navegador se cierra

### Reto 2: Manejo de Transacciones en Creación de Pedidos

**Problema**: Atomicidad entre operaciones múltiples (crear pedido, detalles, actualizar stock).

**Solución Implementada**:
```php
try {
    $db->beginTransaction();  // Inicia transacción
    
    // 1. Crear pedido principal
    $stmtPedido->execute([...]);
    $pedidoId = $db->lastInsertId();
    
    // 2. Crear detalles de pedido
    foreach ($_SESSION['carrito'] as $productoId => $cantidad) {
        $stmtDetalle->execute([...]);
    }
    
    // 3. Actualizar stock
    $stmtStock->execute([...]);
    
    $db->commit();  // Confirma todas las operaciones
} catch (PDOException $e) {
    $db->rollBack();  // Revierte todas si hay error
    throw $e;
}
```

**Garantías ACID**:
- **Atomicity**: Todo o nada (si falla una operación, se revierte todo)
- **Consistency**: BD en estado válido siempre
- **Isolation**: Transacción aislada de otras operaciones
- **Durability**: Cambios persistidos una vez confirmados

### Reto 3: Validación de Stock Antes de Finalizar Compra

**Problema**: Dos clientes compran el último producto simultáneamente.

**Solución Implementada**:
```php
// En ControladorCarrito::finalizarCompra()
foreach ($_SESSION['carrito'] as $productoId => $cantidad) {
    // 1. Obtener stock actual (lock para lectura)
    $stmtStock = $db->prepare("SELECT stock FROM productos WHERE id = :id");
    $stmtStock->execute([':id' => $productoId]);
    $stock = $stmtStock->fetchColumn();
    
    // 2. Validar antes de transacción
    if ($stock < $cantidad) {
        throw new Exception("Stock insuficiente para producto $productoId");
    }
}

// 3. Solo después, iniciar transacción de actualización
$db->beginTransaction();
// ... actualizar stock
$db->commit();
```

**Limitaciones de la solución**:
- Race condition posible si dos usuarios verifican simultaneamente
- Solución ideal: Usar `FOR UPDATE` en MySQL (requerría reescritura)

### Reto 4: Gestión de Roles y Control de Acceso

**Problema**: Prevenir que clientes accedan a panel admin.

**Solución Implementada**:
```php
// En cada controlador admin
private function verificarAdmin() {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    if (!isset($_SESSION['usuario']) || 
        $_SESSION['usuario']['rol'] !== 'admin') {
        header('Location: /Tienda_ropa/publico/index.php');
        exit();
    }
}

// Ejecutado al inicio de cada acción admin
public function panel() {
    $this->verificarAdmin();  // Verifica antes de proceder
    // ... resto del código
}
```

**Niveles de protección**:
1. **Router** (index.php): Ruteo basado en acción
2. **Controlador**: Verificación de sesión + rol
3. **Modelo**: Validación de reglas de negocio (aunque no aplica aquí)
4. **Base de datos**: Constraints de integridad

### Reto 5: Manejo de Contraseñas en Transición

**Problema**: Columna en BD se llama `contrasena` pero código usa `password`.

**Solución Implementada**:
```php
// En ModeloUsuarios::registrar()
$query = "INSERT INTO usuarios (nombre, email, password, rol) 
          VALUES (:nombre, :email, :contrasena, :rol)";
$stmt->execute([
    ':nombre' => $nombre,
    ':email' => $email,
    ':password' => $contrasenaHash,  // Mapeo manual
    ':rol' => $rol
]);
```

**Lección aprendida**: Mantener consistencia entre nombre de parámetro en SQL y en variables PHP.

---

## Mejoras Futuras

### Corto Plazo (1-2 sprints)

#### 1. **Paginación en Catálogo**
```php
// Implementar para n=50 productos
// URL: /publico/index.php?accion=catalogo&pagina=2
// Beneficio: Mejor UX y performance

$porPagina = 12;
$pagina = $_GET['pagina'] ?? 1;
$offset = ($pagina - 1) * $porPagina;

$productos = $modeloProductos->obtenerConPaginacion($offset, $porPagina);
$totalPaginas = ceil($totalProductos / $porPagina);
```

#### 2. **Sistema de Búsqueda y Filtrado**
```php
// Filtrar por: categoría, precio_min, precio_max, talla, color
// URL: /publico/index.php?accion=catalogo&categoria=Camisetas&precio_max=50

public function filtrarProductos($filtros) {
    // LIKE en nombre/descripción para búsqueda
    // WHERE en categoría, talla, color
    // BETWEEN para rango de precio
}
```

#### 3. **Validación de Formularios Client-Side y Server-Side**
```javascript
// Client-side: Feedback inmediato en registro
// Server-side: Validación segura en controlador

const validarEmail = (email) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
const validarPassword = (pwd) => pwd.length >= 8;
```

#### 4. **Confirmación de Email para Registro**
```php
// Generar token aleatorio
// Enviar email con link de confirmación
// Marcar usuario como "verificado" al confirmar

public function generarTokenConfirmacion($email) {
    $token = bin2hex(random_bytes(32));
    // Guardar en tabla usuarios_confirmacion (email, token, expires_at)
    // Enviar email con enlace
}
```

### Mediano Plazo (1-2 months)

#### 5. **Integración Real de API de Pagos (Yape/Stripe)**
```php
// Actualmente: QR generado en servidor, descargado como imagen
// Mejora: Integración REST API de Yape para:
// - Validar pago automáticamente
// - Actualizar estado de pedido en tiempo real
// - Webhook para confirmación asincrónica

public function verificarPagoYape($referencia) {
    $response = Http::get('https://api.yape.com/verify', [
        'reference' => $referencia,
        'amount' => $this->pedido->total
    ]);
    
    if ($response->json('status') === 'paid') {
        // Actualizar pedido como completado
        $this->modeloPedidos->actualizarEstado($pedidoId, 'completado');
    }
}
```

#### 6. **Dashboard Analítico para Admin**
```php
// Gráficos de:
// - Ventas por día/mes/año
// - Productos más vendidos
// - Ingresos por categoría
// - Usuarios activos vs inactivos

// Librerías: Chart.js o Recharts
// Datos calculados en modelo con agregaciones SQL

public function obtenerVentasPorMes($anio) {
    return $this->db->query("
        SELECT MONTH(fecha) as mes, SUM(total) as ventas
        FROM pedidos
        WHERE YEAR(fecha) = :anio AND estado = 'completado'
        GROUP BY MONTH(fecha)
    ");
}
```

#### 7. **Carrito Persistente en Base de Datos**
```php
// Crear tabla carrito_temporal
// Permitir que usuario recupere carrito incluso después de cerrar sesión

CREATE TABLE carrito_temporal (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL FOREIGN KEY,
    producto_id INT NOT NULL FOREIGN KEY,
    cantidad INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

// Sincronizar con sesión:
// - Al login: cargar desde BD
// - Al logout: guardar en BD
// - Limpiar: cuando se finaliza compra
```

#### 8. **Reseñas y Calificaciones de Productos**
```sql
CREATE TABLE resenas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    producto_id INT NOT NULL FOREIGN KEY,
    usuario_id INT NOT NULL FOREIGN KEY,
    calificacion INT CHECK (calificacion >= 1 AND calificacion <= 5),
    comentario TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- En vista de producto:
SELECT AVG(calificacion) as promedio, COUNT(*) as total
FROM resenas
WHERE producto_id = :id;
```

### Largo Plazo (3+ months)

#### 9. **Notificaciones por Email**
```php
// Usar PHPMailer o SwiftMailer

public function enviarConfirmacionPedido($pedidoId) {
    $mail = new PHPMailer();
    $mail->addAddress($usuario['email']);
    $mail->Subject = "Pedido #{$pedidoId} Confirmado";
    $mail->Body = $this->renderTemplate('emails/confirmacion.php', [
        'pedido' => $pedido,
        'detalles' => $detalles
    ]);
    $mail->send();
}

// Eventos a notificar:
// - Confirmación de registro
// - Confirmación de pedido
// - Cambio de estado de pedido
// - Productos de interés en stock
```

#### 10. **API REST para Terceros**
```php
// Convertir a API RESTful para móviles, integraciones

// Endpoints:
GET    /api/v1/productos               // Listar
GET    /api/v1/productos/{id}          // Detalle
POST   /api/v1/auth/login              // Login
POST   /api/v1/carrito                 // Agregar item
GET    /api/v1/pedidos                 // Historial
POST   /api/v1/pedidos/{id}/pago       // Procesar pago

// Response format (JSON):
{
    "status": "success|error",
    "data": {...},
    "errors": [...],
    "timestamp": "2025-03-18T15:30:00Z"
}

// Autenticación: JWT tokens
public function autenticar($email, $password) {
    if ($this->validarCredenciales($email, $password)) {
        $token = JWT::encode([
            'user_id' => $usuario['id'],
            'rol' => $usuario['rol'],
            'exp' => time() + 3600
        ], SECRET_KEY);
        return ['access_token' => $token];
    }
}
```

#### 11. **Aplicación Móvil (React Native/Flutter)**
```
Backend API ←→ Shared Models/Logic
↓
Web Frontend (PHP/HTML)
Mobile Frontend (React Native)
```

#### 12. **Caché y Optimización de Performance**
```php
// Redis para:
// - Caché de catálogo de productos
// - Sesiones distribuidas (para escalamiento)
// - Rate limiting en API

// Implementación:
$redis = new Redis();
$redis->connect('localhost', 6379);

// Caché de productos
$cacheKey = 'productos:pagina:' . $pagina;
if ($redis->exists($cacheKey)) {
    $productos = json_decode($redis->get($cacheKey));
} else {
    $productos = $modeloProductos->obtenerConPaginacion(...);
    $redis->setex($cacheKey, 3600, json_encode($productos));  // Expira en 1h
}
```

---

## Decisiones Técnicas Clave

### DT-001: Uso de PHP Puro en lugar de Framework

**Decisión**: Implementar MVC custom sin Laravel/Symfony.

**Razones**:
- ✅ **Control total**: Entender cada línea de código
- ✅ **Overhead cero**: Frameworks añaden 200-300KB
- ✅ **Aprendizaje**: Comprender HTTP routing, request/response
- ✅ **Flexibilidad**: Cambios sin limitaciones de framework

**Trade-offs**:
- ❌ Más código boilerplate
- ❌ Menos convenciones predefinidas
- ❌ Mayor responsabilidad en seguridad

**Contexto**: Ideal para proyectos pequeños/medianos. Para empresas grandes, un framework como Laravel es preferible.

---

### DT-002: Sesiones PHP para Carrito (No BD)

**Decisión**: Almacenar carrito en `$_SESSION['carrito']` en lugar de tabla en BD.

**Razones**:
- ✅ **Rendimiento**: Acceso en memoria vs query a BD (~100x más rápido)
- ✅ **Simplicidad**: Array asociativo, sin migración de datos
- ✅ **Seguridad**: Datos no persistidos si usuario no compra
- ✅ **Escalabilidad**: Sesiones distribuidas con Redis en el futuro

**Trade-offs**:
- ❌ No recupera carrito después de cerrar navegador
- ❌ Carrito se pierde si sesión expira

**Alternativa rechazada**: Tabla `carrito_temporal` (sería prematura optimización).

---

### DT-003: Prepared Statements Obligatorios

**Decisión**: 100% de consultas usan placeholders (`:param` o `?`).

```php
// ✅ SIEMPRE así:
$stmt = $db->prepare("SELECT * FROM usuarios WHERE email = :email");
$stmt->execute([':email' => $email]);

// ❌ NUNCA así:
$query = "SELECT * FROM usuarios WHERE email = '$email'";
```

**Por qué**:
- Previene **inyección SQL** (vulnerabilidad crítica)
- El motor BD valida y escapa datos automáticamente
- Mejor caching de queries en BD

---

### DT-004: Bcrypt para Hash de Contraseñas

**Decisión**: `password_hash($pwd, PASSWORD_DEFAULT)` en lugar de MD5/SHA1.

```php
// ✅ PASSWORD_DEFAULT = Bcrypt
$hash = password_hash($password, PASSWORD_DEFAULT);

// Verificación segura
if (password_verify($password, $hash)) { ... }

// ❌ NUNCA usar:
// MD5($password)      - Rainbow tables conocidas
// SHA1($password)     - Colisiones encontradas
// base64_encode()     - Reversible, no es hash
```

**Ventajas de Bcrypt**:
- Salting automático (cada hash es único)
- Adaptativo (costo computacional ajustable para futuro)
- Resistente a timing attacks
- Estándar OWASP

---

### DT-005: PDO en lugar de mysqli

**Decisión**: Usar PDO (PHP Data Objects) para abstracción de BD.

**Ventajas**:
- Soporta múltiples motores (MySQL, PostgreSQL, SQLite)
- Mejor manejo de excepciones (`PDOException`)
- Prepared statements más legibles
- Mejor para unit testing

**Implementación**:
```php
$db = new PDO(
    "mysql:host=localhost;dbname=tienda;charset=utf8mb4",
    "root",
    ""
);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
```

---

### DT-006: ENUM para Estados de Pedido

**Decisión**: Usar `ENUM('pendiente','completado','cancelado')` en BD.

**Por qué**:
- ✅ **Validación a nivel BD**: Imposible valores inválidos
- ✅ **Documentación**: Self-documenting en schema
- ✅ **Rendimiento**: Almacenamiento más eficiente (1 byte vs 20)

**Alternativa rechazada**: Tabla separada `estados` con FK (over-engineering).

---

### DT-007: Charset UTF-8 MB4

**Decisión**: `CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci` en toda la BD.

```sql
CREATE DATABASE tienda CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE usuarios (
    email VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
);
```

**Por qué UTF-8 MB4**:
- Soporta emojis 😀 (útil para descripciones de productos)
- Compatibilidad total con Unicode
- UTF-8 original (3 bytes) no soporta algunos caracteres

---

### DT-008: DECIMAL para Precios (No FLOAT)

**Decisión**: `DECIMAL(10,2)` en lugar de FLOAT para columnas monetarias.

```sql
-- ✅ CORRECTO
precio DECIMAL(10,2)  -- Exacto: 99999.99

-- ❌ INCORRECTO
precio FLOAT(10,2)    -- Imprecisión: 49.99 → 49.989999...
```

**Por qué**:
- FLOAT usa aritmética binaria (imprecisa para decimales)
- DECIMAL usa aritmética decimal (exacta)
- Diferencia: $0.01 * 1000 transacciones = $10 perdidos

---

### DT-009: Foreign Keys con Cascadas

**Decisión**: Establecer FKs con `ON DELETE RESTRICT, ON UPDATE CASCADE`.

```sql
ALTER TABLE detalles_pedido
  ADD CONSTRAINT detalles_pedido_ibfk_1 
  FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
  ON DELETE RESTRICT       -- No permite eliminar pedido si tiene detalles
  ON UPDATE CASCADE;       -- Propaga cambios de ID
```

**Por qué RESTRICT**:
- Previene accidentalmente borrar pedidos históricos
- Fuerza mantener integridad referencial
- Mejor para auditoría

---

### DT-010: Regeneración de ID de Sesión

**Decisión**: Llamar `session_regenerate_id(true)` tras login exitoso.

```php
public function iniciarSesion($email, $password) {
    if (password_verify($password, $usuario['password'])) {
        $_SESSION['usuario'] = $usuario;
        session_regenerate_id(true);  // Elimina antiguo ID, asigna nuevo
        return true;
    }
}
```

**Protege contra**:
- **Session Fixation Attack**: Atacante fuerza sesión anterior
- **Session Hijacking**: Reutilización de ID conocido

**Costo**: Negligible (~1ms operación).

---

## Autor

**Proyecto Desarrollado por**: [Tu Nombre]

### Perfil

- **Rol**: Full-Stack Developer (Enfoque Backend)
- **Experiencia**: Arquitectura de sistemas, Seguridad web, Optimización de BD
- **Interesses**: Clean Code, SOLID Principles, Scalable Design
- **GitHub**: [tu-usuario]
- **LinkedIn**: [tu-perfil]
- **Email**: [tu-email]

### Contacto y Contribuciones

Este proyecto está abierto a:
- **Bug Reports**: Reportar en [Issues]
- **Pull Requests**: Contribuciones bienvenidas
- **Discussions**: Ideas de mejoras

### Licencia

MIT License - Libre para usar, modificar y distribuir

---

## Stack Visual Summary

```
┌─────────────────────────────────────────────────────────────┐
│                      CLIENTE (Navegador)                     │
│  HTTP Requests: GET /publico/index.php?accion=catalogo      │
└─────────────────────┬───────────────────────────────────────┘
                      │
                      ▼
              ┌───────────────────┐
              │   Apache WEB      │
              │   Server          │
              │ mod_rewrite       │
              └────────┬──────────┘
                       │
                       ▼
    ┌──────────────────────────────────────┐
    │    publico/index.php                 │
    │    [Front Controller Router]          │
    └─────┬────────────────────────────────┘
          │
          ├─→ ControladorAutenticacion
          ├─→ ControladorProductos
          ├─→ ControladorCarrito
          ├─→ ControladorPedidos
          ├─→ ControladorPagos
          ├─→ ControladorAdmin
          └─→ ControladorPerfil
              │
              ├─→ ModeloUsuarios ──┐
              ├─→ ModeloProductos──┤
              ├─→ ModeloCarrito  ──┤
              └─→ ModeloPedidos  ──┤
                                   │
                                   ▼
                         ┌────────────────────┐
                         │   MySQL Database   │
                         │   (tienda)         │
                         │ ┌────────────────┐ │
                         │ │  usuarios      │ │
                         │ │  productos     │ │
                         │ │  pedidos       │ │
                         │ │  detalles      │ │
                         │ └────────────────┘ │
                         └────────────────────┘
                                   │
                      ┌────────────┴────────────┐
                      │                         │
                      ▼                         ▼
          ┌─────────────────────┐   ┌──────────────────┐
          │  vista/xxx.php      │   │ publico/recursos │
          │  [HTML Rendering]   │   │  /css, /js, /img │
          └────────┬────────────┘   └──────────────────┘
                   │
                   ▼
        ┌─────────────────────────┐
        │  HTTP Response (HTML)   │
        │  + CSS + JavaScript     │
        └─────────────────────────┘
                   │
                   ▼
    ┌──────────────────────────────────┐
    │     Navegador (Rendering)        │
    │  DOM + Styles + Event Listeners  │
    └──────────────────────────────────┘
```

---

## Conclusión

**Tienda Ropa** demuestra arquitectura sólida de backend e-commerce implementando:

✅ Separación clara de responsabilidades (MVC)
✅ Seguridad en autenticación (bcrypt) y datos (prepared statements)
✅ Control de acceso basado en roles (RBAC)
✅ Manejo robusto de excepciones y transacciones
✅ Escalabilidad con índices y queries optimizadas
✅ Código mantenible y documentado

El proyecto es un **portafolio técnico sólido** para roles de **Backend Developer** o **Full-Stack Engineer**, demostrando comprensión profunda de e-commerce, seguridad web, y patrones de diseño.

---

**Última actualización**: Marzo 2025
**Versión del Documento**: 1.0
**Estado**: Producción Ready (Con mejoras futuras implementables)
