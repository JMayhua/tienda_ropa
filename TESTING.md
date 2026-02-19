# TESTING.md - Guía de Pruebas

## Tabla de Contenidos

1. [Introducción](#introducción)
2. [Configuración del Ambiente de Testing](#configuración-del-ambiente-de-testing)
3. [Pruebas Funcionales](#pruebas-funcionales)
4. [Pruebas de Seguridad](#pruebas-de-seguridad)
5. [Pruebas de Performance](#pruebas-de-performance)
6. [Checklist de Testing](#checklist-de-testing)

---

## Introducción

Este documento describe cómo hacer pruebas exhaustivas de **Tienda Ropa** antes de deplegar a producción.

### Alcance de Pruebas

```
FUNCIONALIDAD
├─ Flujos de usuario (happy path)
├─ Casos edge (valores extremos)
├─ Manejo de errores
└─ Validación de datos

SEGURIDAD
├─ Autenticación
├─ Autorización (RBAC)
├─ Inyección SQL
├─ XSS
└─ CSRF

PERFORMANCE
├─ Tiempo de respuesta
├─ Uso de memoria
├─ Queries optimizadas
└─ Caché

COMPATIBILIDAD
├─ Navegadores
├─ Dispositivos
└─ Bases de datos
```

---

## Configuración del Ambiente de Testing

### 1. BD de Prueba Separada

```bash
# Crear BD de test
mysql -u root -p < BASE\ DE\ DATOS/tienda_ropa.sql

# O renombrar:
# tienda_produccion  (BD actual)
# tienda_test        (BD para pruebas)
```

### 2. Credenciales de Test

```php
// test-config.php
define('DB_HOST', 'localhost');
define('DB_NAME', 'tienda_test');  // BD de prueba
define('DB_USER', 'root');
define('DB_PASSWORD', '');

// Usar esta config en tests
```

### 3. Datos de Test (Fixtures)

```php
// tests/fixtures/usuarios.sql
INSERT INTO usuarios (nombre, email, password, rol) VALUES
('Cliente Test', 'cliente@test.com', '$2y$10$...bcrypt...', 'cliente'),
('Admin Test', 'admin@test.com', '$2y$10$...bcrypt...', 'admin');
```

### 4. Variables de Prueba Preparadas

```bash
# Crear cuenta de test
Email:    cliente@test.com
Password: TestPassword123!

# Crear cuenta admin
Email:    admin@test.com
Password: AdminPassword123!
```

---

## Pruebas Funcionales

### TC-01: Flujo de Registro

#### Precondición
- Navegador limpio (sin cookies)
- BD de test inicializada

#### Steps

```gherkin
Given Usuario en página de registro
When Ingresa nombre, email, password válidos
And Hace click en "Registrarse"
Then Ve mensaje "Registro exitoso"
And Es redirigido a login
And Puede hacer login con nuevas credenciales
```

#### Caso Negativo

```gherkin
Given Usuario en página de registro
When Intenta registrase con email ya usado
Then Ve error "Email ya registrado"
And Permanece en formulario de registro
```

#### Steps de Testing

```
1. http://localhost/Tienda_ropa/publico/index.php?accion=registrarse
2. Llenar:
   - Nombre: "Test User"
   - Email: "newuser@test.com"
   - Password: "SecurePass123!"
3. Click "Registrarse"
4. Verificar:
   - Redirigido a login
   - Mensaje "Registro exitoso"
5. Nuevamente:
   - Email: "newuser@test.com"
   - Password: "SecurePass123!"
6. Click "Iniciar Sesión"
7. Verificar:
   - Redirigido a catalogo
   - Nombre en header
```

---

### TC-02: Flujo de Compra Completo

#### Precondición
- Usuario cliente logueado
- Productos disponibles en BD
- Stock > 0

#### Steps

```gherkin
Given Usuario en catálogo
When Hace click en producto
And Ve detalle de producto
When Hace click "Añadir al Carrito"
Then Ve mensaje "Producto añadido"
And Carrito muestra 1 item

When Va a Ver Carrito
Then Ve producto con cantidad y subtotal
And Total es correcto (precio * cantidad)

When Hace click "Finalizar Compra"
Then Pedido es creado en BD
And Carrito se vacía
And Es redirigido a seleccionar método de pago

When Selecciona "Yape"
Then Ve QR de pago
And Pedido estado = 'pendiente'
```

#### Verificaciones en BD

```sql
-- Producto en carrito fue reducido de stock
SELECT stock FROM productos WHERE id = 2;
-- Verificar: stock original - 1

-- Pedido fue creado
SELECT * FROM pedidos 
WHERE usuario_id = 1 
ORDER BY fecha DESC LIMIT 1;
-- Verificar: estado='pendiente', total=correcto

-- Detalles de pedido fueron creados
SELECT * FROM detalles_pedido 
WHERE pedido_id = (SELECT MAX(id) FROM pedidos);
-- Verificar: producto_id, cantidad, precio_unitario
```

---

### TC-03: Panel de Admin

#### Precondición
- Usuario admin logueado

#### Steps

```
1. http://localhost/Tienda_ropa/publico/index.php?accion=panel_admin
2. Verificar:
   - Total Productos = 10 (o número actual)
   - Total Pedidos = N
   - Total Usuarios = 2 (o actual)
   
3. Click "Gestionar Productos"
4. Verificar:
   - Tabla con todos los productos
   - Columnas: ID, Nombre, Precio, Stock, Acciones
   
5. Click editar en producto
6. Cambiar precio: 19.99 → 24.99
7. Click "Actualizar"
8. Verificar: Producto actualizado en tabla
9. BD:
   SELECT precio FROM productos WHERE id=1;
   -- Debe ser 24.99
```

---

### TC-04: Casos Edge (Manejo de Errores)

#### Cantidad Negativa
```
1. Abrir carrito en modo debug
2. Modificar cantidad: 1 → -5
3. Intentar finalizar compra
4. Verificar: Error "Cantidad debe ser positiva"
```

#### ID Inválido
```
1. http://localhost/?accion=mostrar_producto&id=9999
2. Verificar: Error 404 o "Producto no encontrado"

1. http://localhost/?accion=mostrar_producto&id=abc
2. Verificar: Sin ejecución de script, error en pantalla
```

#### Campos Vacíos
```
1. Formulario de login
2. Dejar campos vacíos
3. Click Iniciar Sesión
4. Verificar: Mensaje de error
5. BD: Sin intento de login registrado (o fallido)
```

#### Stock Insuficiente
```
1. Producto con stock = 1
2. Usuario A: Añade al carrito, finaliza compra → OK
3. Usuario B: Intenta agregar mismo producto
4. Verificar: Error "Stock insuficiente"
5. BD: Stock de producto = 0 (no menos)
```

---

## Pruebas de Seguridad

### SN-01: Inyección SQL

#### Test

```
1. Login form
2. Email: ' OR '1'='1
3. Password: anything
4. Click "Iniciar Sesión"
5. Verificar: Error "Credenciales incorrectas"
        NO: Acceso permitido
   BD log:
   -- Prepared statement previene ejecución de código SQL
```

#### Comando para validar

```php
// En ControladorAutenticacion.php antes de iniciar sesión
error_log("Email recibido: " . $_POST['email']);

// Debe mostrar:
// "Email recibido: ' OR '1'='1"
// NO como código SQL, como string literal
```

---

### SN-02: XSS Almacenado

#### Test en Descripción de Producto

```
1. Admin → Gestionar Productos → Editar
2. Descripción: <script>alert('XSS')</script>
3. Click Actualizar
4. Cliente → Ver catálogo → Click producto
5. Verificar: 
   NO SE EJECUTA script
   Se muestra como texto: "<script>alert('XSS')</script>"
```

#### Verificação del Código Fuente

```
1. Abrir DevTools (F12)
2. Inspeccionar elemento descripción
3. Debe mostrar: &lt;script&gt;alert('XSS')&lt;/script&gt;
   NO: <script>alert('XSS')</script>
```

---

### SN-03: CSRF (No Implementado - Test para Mejora)

#### Vulnerabilidad Actual

```
1. Usuario logueado en navegador A en tienda-ropa.com
2. En una pestaña, abre sitio malicioso: evil.com
3. evil.com contiene:
   <img src="tienda-ropa.com/comprar.php?producto=999&cantidad=1000">
4. Resultado: Compra no autorizada ❌

Solución: Implementar CSRF tokens en POST forms
```

---

### SN-04: Session Fixation

#### Test

```
1. Abrir navegador 1: localhost/Tienda_ropa
2. Browser console: console.log(document.cookie)
3. Anotar PHPSESSID: abc123
4. Abrir navegador 2
5. En console: document.cookie = "PHPSESSID=abc123"
6. Intentar acceder a /carrito
7. Verificar:
   - Sesión rechazada (nueva sesión después de login)
   - No se reutiliza sesión anterior
```

---

### SN-05: Validación de Contraseña Débil

#### Test para Mejora Futura

```
1. Registro en fortaleza de password
2. Probar:
   Password = "123"        → Error "Muy corta"
   Password = "123456"     → Error "Sin mayúscula"
   Password = "abcDEF"     → Error "Sin número"
   Password = "abCD123"    → Error "Sin carácter especial"
   Password = "abCD123!"   → OK ✅
```

---

### SN-06: Rate Limiting (No Implementado)

#### Test Manual

```
# Script para simular ataque de fuerza bruta
for i in {1..100}; do
  curl -X POST \
    -d "email=admin@test.com&password=wrong" \
    http://localhost/Tienda_ropa/publico/index.php?accion=iniciar_sesion
done

# Verificar:
# - IP debería ser bloqueada después de N intentos fallidos
# - Retorna: HTTP 429 Too Many Requests
# Actualmente: Sin limitación (vulnerable a brute force)
```

---

## Pruebas de Performance

### PF-01: Load Testing Catálogo

#### Test Manual

```bash
# Apache Bench (herramienta integrada)
ab -n 1000 -c 10 http://localhost/Tienda_ropa/publico/index.php?accion=catalogo

# Resultado esperado:
# Requests per second: > 100 RPS
# Time per request: < 50ms
# Failed requests: 0
```

#### Monitoreo

```
1. Abrir Terminal 2: MySQL console
   SHOW PROCESSLIST;
   -- Monitorear queries simultáneas
   
2. Abrir Terminal 3: Monitor de PHP
   tail -f /var/log/php/error.log
   -- Buscar timeouts o errores
```

---

### PF-02: Query Performance

#### Productos Lentos

```sql
-- Verificar índices
EXPLAIN SELECT * FROM productos WHERE categoria = 'Camisetas';
-- Si no usa índice, agregarlo:
CREATE INDEX idx_productos_categoria ON productos(categoria);
```

#### Login Lento

```sql
-- Verificar búsqueda de usuario
EXPLAIN SELECT * FROM usuarios WHERE email = 'test@test.com';
-- Email debe ser UNIQUE (actúa como índice)
```

---

### PF-03: Caché (Futuro)

#### Sin Caché

```
Request 1: SELECT * FROM productos  → 50ms
Request 2: SELECT * FROM productos  → 50ms
Request 3: SELECT * FROM productos  → 50ms
Total: 150ms
```

#### Con Caché Redis

```
Request 1: SELECT * FROM productos → 50ms, guardar en Redis
Request 2: Lectura desde Redis     → 1ms ✅
Request 3: Lectura desde Redis     → 1ms ✅
Total: 52ms (100x más rápido)
```

---

## Checklist de Testing

### Antes de cada Deploy

- [ ] **Funcionalidad**
  - [ ] Registro funciona
  - [ ] Login funciona
  - [ ] Catálogo carga productos
  - [ ] Carrito añade/elimina items
  - [ ] Compra crea pedido
  - [ ] Panel admin accesible solo para admin
  
- [ ] **Seguridad**
  - [ ] SQL injection no funciona
  - [ ] XSS no ejecuta scripts
  - [ ] Session ID se regenera tras login
  - [ ] Usuario cliente no accede a /admin
  - [ ] Contraseñas hasheadas en BD
  
- [ ] **Datos**
  - [ ] Stock actualizado tras compra
  - [ ] Pedido forma correctamente
  - [ ] Detalles pedido linked a productos
  - [ ] Usuario no puede acceder pedidos de otros
  
- [ ] **Performance**
  - [ ] Catálogo carga < 1s
  - [ ] Login responde < 500ms
  - [ ] Checkout < 2s
  - [ ] Sin queries N+1
  
- [ ] **Compatibilidad**
  - [ ] Chrome/Edge/Firefox últimas versiones
  - [ ] Mobile responsive
  - [ ] MySQL 5.7+ y MariaDB 10.x
  
- [ ] **Documentación**
  - [ ] README actualizado
  - [ ] Cambios de proceso documentados
  - [ ] CHANGELOG actualizado

---

### Antes de Aumentar a Producción

- [ ] Backup de BD creado
- [ ] Script de rollback probado
- [ ] Logs configurados
- [ ] Monitoreo activo
- [ ] Equipo en standby primeras 24h
- [ ] Plan de respuesta a incidentes

---

## Test Success Criteria

```
✅ PASS si:
- Todos los TC-XX ejecutan exitosamente
- Todos los SN-XX fallan como se espera  (seguridad funciona)
- Performance dentro de SLAs
- Cero errores en logs

❌ FAIL si:
- UI muestra errores
- BD inconsistencia
- Seguridad comprometed
- Performance abajo de thresholds
```

---

**Última actualización**: Marzo 2025
