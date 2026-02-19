# SECURITY.md - Guía de Seguridad

## Descripción General de Seguridad

Este documento detalla las medidas de seguridad implementadas en **Tienda Ropa** y las mejores prácticas para mantener la aplicación segura.

---

## 1. Autenticación y Contraseñas

### Hashing Seguro de Contraseñas

**Implementación**: `password_hash()` con algoritmo PASSWORD_DEFAULT (bcrypt)

```php
// Registro
$hash = password_hash($contrasena, PASSWORD_DEFAULT);
// Genera: $2y$10$Qty.Jw62ifydA7/c60JIpeZM.OGWkvZsseNORs.EfTfaeYB2gPp1C

// Verificación
if (password_verify($contrasena_ingresada, $hash)) {
    // Contraseña correcta
}
```

**Por qué bcrypt**:
- ✅ Salting automático (cada hash único)
- ✅ Función lenta (resistente a fuerza bruta)
- ✅ Adaptativo (costo computacional aumenta con tiempo)
- ✅ Resistente a timing attacks (comparación time-constant)

**❌ NUNCA usar**:
- MD5 (rainbow tables públicas)
- SHA1 (colisiones encontradas)
- base64_encode (reversible)
- Contraseña en texto plano

### Validación de Contraseñas

```php
public static function validarFortalezaContrasena($password) {
    $errores = [];
    
    if (strlen($password) < 8) {
        $errores[] = 'Mínimo 8 caracteres';
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $errores[] = 'Debe contener mayúscula';
    }
    if (!preg_match('/[a-z]/', $password)) {
        $errores[] = 'Debe contener minúscula';
    }
    if (!preg_match('/[0-9]/', $password)) {
        $errores[] = 'Debe contener dígito';
    }
    if (!preg_match('/[!@#$%^&*]/', $password)) {
        $errores[] = 'Debe contener carácter especial';
    }
    
    return empty($errores) ? true : $errores;
}
```

### Regeneración de ID de Sesión

```php
session_regenerate_id(true);  // true = destruir antigua sesión
```

**Previene**: Session Fixation Attack
- Atacante crea sesión ID conocido
- Fuerza usuario a usar ese ID
- Después de login, regeneramos → sesión vieja inútil

---

## 2. Prevención de Inyección SQL

### Prepared Statements (✅ Implementado)

```php
// ✅ CORRECTO - Con placeholders
$query = "SELECT * FROM usuarios WHERE email = :email AND rol = :rol";
$stmt = $db->prepare($query);
$stmt->execute([
    ':email' => $email,
    ':rol' => 'admin'
]);

// El motor BD parsea query, luego sustituye datos
// Datos nunca se interpretan como código
```

### Ataques SQL Prevenidos

```
Input malicioso: ' OR '1'='1
Query insegura: SELECT * FROM usuarios WHERE email = '' OR '1'='1'
Resultado: Retorna TODOS los usuarios ❌

Query segura: SELECT * FROM usuarios WHERE email = :email
Bind: [':email' => "' OR '1'='1"]
Resultado: Busca usuario con email literal: "' OR '1'='1" ✅
Encontrado: false (ningún usuario con ese email)
```

### Validación de Entrada Adicional

```php
// Campo ID debe ser entero
$id = intval($_GET['id']);  // '5&codigo=hack' → 5

// Validar email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    throw new Exception('Email inválido');
}

// Whitelist de categorías permitidas
$categoriasValidas = ['Camisetas', 'Pantalones', 'Zapatos'];
if (!in_array($categoria, $categoriasValidas)) {
    throw new Exception('Categoría no válida');
}
```

---

## 3. Prevención de XSS (Cross-Site Scripting)

### Escaping de Salida (✅ Parcialmente Implementado)

```php
<!-- ✅ CORRECTO -->
<?php echo htmlspecialchars($usuario['nombre'], ENT_QUOTES, 'UTF-8'); ?>
<!-- Convierte: <script> → &lt;script&gt; -->

<!-- ❌ INCORRECTO -->
<?php echo $usuario['nombre']; ?>
<!-- Si nombre = '<script>alert(1)</script>', se ejecuta -->
```

### Contextos Diferentes

```php
// HTML context
echo htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');

// URL context (en href)
echo 'href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '"';

// JavaScript context (más complejo)
$js_string = json_encode($texto);  // Mejor que htmlspecialchars

// CSS context (validar/sanitizar)
$color = preg_match('/^#[0-9A-F]{6}$/', $color) ? $color : '#000';
```

### Ataques XSS Prevenidos

```
1. Stored XSS (en BD)
   Atacante: Producto descripcion = "<script>robar cookie</script>"
   Admin edita producto → script se ejecuta ❌
   
   Solución: Escapar al mostrar:
   <?php echo htmlspecialchars($producto['descripcion']); ?>

2. Reflected XSS (en URL)
   URL: /buscar.php?q=<img src=x onerror=alert(1)>
   Página: Resultados de búsqueda para: <img src=x onerror=alert(1)>
   Script se ejecuta ❌
   
   Solución: 
   echo htmlspecialchars($_GET['q'], ENT_QUOTES, 'UTF-8');
   // Muestra: &lt;img src=x onerror=alert(1)&gt;

3. DOM XSS (JavaScript lado cliente)
   JavaScript inseguro:
   document.getElementById('resultado').innerHTML = userInput;  // ❌
   
   Seguro:
   document.getElementById('resultado').textContent = userInput;  // ✅
```

---

## 4. Control de Acceso (RBAC)

### Verificación de Rol en Controladores

```php
class ControladorAdmin {
    private function verificarAdmin() {
        if (!isset($_SESSION['usuario']) || 
            $_SESSION['usuario']['rol'] !== 'admin') {
            header('Location: /Tienda_ropa/publico/index.php');
            exit();
        }
    }
    
    public function panel() {
        $this->verificarAdmin();  // Guard antes de lógica
        // ... código admin
    }
}
```

### Niveles de Autorización

```
                    Recurso
                      │
          ┌───────────┼───────────┐
          │           │           │
    "Público"   "Cliente"    "Admin"
    (sin auth)  (autenticado) (rol=admin)
    
    catalogo()      ✅         ✅         ✅
    ver_carrito()   ❌         ✅         ✅
    panel_admin()   ❌         ❌         ✅
    listar_usuarios()❌        ❌         ✅
```

### Implementación de Autorización a Nivel BD

```php
// Mejora futura: Verificar en BD antes de ejecutar
public function obtenerPedidosDeUsuario($usuarioId) {
    // Verificar que usuario solicitando = propietario del pedido
    $query = "SELECT p.* FROM pedidos p
              WHERE p.usuario_id = :usuario_id
              AND p.usuario_id = :usuario_actual";
    
    $stmt = $db->prepare($query);
    $stmt->execute([
        ':usuario_id' => $usuarioId,
        ':usuario_actual' => $_SESSION['usuario_id']
    ]);
    
    return $stmt->fetchAll();
    // Si usuarios no coinciden, resultado vacío
}
```

---

## 5. Protección CSRF (Cross-Site Request Forgery)

### Tokens CSRF (No Implementado - Mejora Futura)

```php
// config.php
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// En formularios
<form method="POST" action="...">
    <input type="hidden" name="csrf_token" 
           value="<?php echo $_SESSION['csrf_token']; ?>">
    <!-- Otros campos -->
</form>

// En Controllers (verificación)
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    throw new Exception('CSRF validation failed');
}
```

### Cómo protestaría sin token:

```
1. Atacante crea página maliciosa (attacker.com)
2. Usuario autenticado en tienda-ropa.com
3. Usuario visita attacker.com
4. Página ejecuta:
   <img src="tienda-ropa.com/comprar.php?producto=1&cantidad=1000">
5. Navegador envía request CON COOKIES de tienda-ropa.com
6. Compra se realiza sin consentimiento del usuario ❌

Con CSRF token:
5. Request necesita token en el body o header
6. Página maliciosa no conoce el token
7. Request falla ✅
```

---

## 6. Validación de Entrada

### Niveles de Validación

```
┌─────────────────────────────────────┐
│   1. Cliente (JavaScript)           │
│   Feedback inmediato, UX mejor      │
│   ❌ No es seguridad real            │
└─────────────────────────────────────┘
         │ Puede ser burlada
         ▼
┌─────────────────────────────────────┐
│   2. Controller (PHP)               │
│   Valida tipos, rangos, formato     │
│   ✅ Protección contra input básico  │
└─────────────────────────────────────┘
         │ Puede ser burlada
         ▼
┌─────────────────────────────────────┐
│   3. Modelo (PHP)                   │
│   Valida reglas de negocio          │
│   Verifica constraints BD           │
│   ✅ Protección contra lógica maliciosa
└─────────────────────────────────────┘
         │ Constrains fuerzan
         ▼
┌─────────────────────────────────────┐
│   4. Base de Datos (SQL)            │
│   PRIMARY KEY, UNIQUE, FOREIGN KEY  │
│   CHECK, ENUM, CONSTRAINTS          │
│   ✅ Última línea de defensa         │
└─────────────────────────────────────┘
```

### Ejemplos de Validación

```php
// Controller
public function registrar() {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validar no vacío
    if (empty($nombre) || empty($email) || empty($password)) {
        throw new Exception('Campos requeridos');
    }
    
    // Validar email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Email inválido');
    }
    
    // Validar longitud
    if (strlen($nombre) > 100) {
        throw new Exception('Nombre muy largo');
    }
    
    // Pasar al modelo (que valida aún más)
    $resultado = $this->modelo->registrar($nombre, $email, $password);
}

// Modelo
public function registrar($nombre, $email, $password) {
    // Validación de negocio
    if ($this->emailYaRegistrado($email)) {
        return false;
    }
    
    // Hash seguro
    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    // BD solo acepta INSERT si:
    // - email UNIQUE (constraint)
    // - password NOT NULL
    // - nombre NOT NULL
    // - rol ENUM válido
    $stmt = $db->prepare("INSERT INTO usuarios...");
    $stmt->execute([...]);  // Prepared statement
}
```

---

## 7. Manejo Seguro de Errores

### En Desarrollo

```php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

**Muestra**:
- Stack traces completos
- Nombres de variables
- Rutas de archivos
- Información de BD

✅ Útil para debugging

---

### En Producción

```php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '/var/log/php/error.log');
```

**Ventajas**:
- ✅ No expone información a atacantes
- ✅ Errores guardados en servidor (logs)
- ✅ Usuario ve mensaje genérico
- ❌ Debugging requiere acceso a servidor

### Mensaje Genérico para Usuario

```php
try {
    // Operación que podría fallar
} catch (Exception $e) {
    // Log completo en servidor
    error_log("Error registrando usuario: " . $e->getMessage());
    
    // Mensaje seguro al usuario
    $_SESSION['error'] = "Error en el proceso. Por favor, intenta de nuevo.";
    
    // No revelar: stack trace, nombres de BD, rutas, etc.
}
```

---

## 8. Gestión Segura de Sesiones

### Configuración Segura

```php
// config.php
ini_set('session.use_only_cookies', 1);     // No accept session via URL
ini_set('session.cookie_httponly', 1);      // No acceso desde JS
ini_set('session.cookie_secure', 1);        // Solo HTTPS (en producción)
ini_set('session.cookie_samesite', 'Strict'); // Previene CSRF
ini_set('session.gc_maxlifetime', 1800);    // 30 minutos
```

### Destrucción Segura de Sesión

```php
public function cerrarSesion() {
    // Limpiar datos de sesión
    $_SESSION = [];
    
    // Destruir cookie de sesión
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }
    
    // Destruir sesión
    session_destroy();
}
```

---

## 9. Protección de Archivos Subidos

### ❌ Vulnerabilidades

```php
// VULNERABLE: Permitir cualquier extensión
move_uploaded_file($_FILES['imagen']['tmp_name'], 
                   'publico/recursos/imagenes/' . $_FILES['imagen']['name']);
// Atacante sube: shell.php
// Luego accede: http://localhost/publico/recursos/imagenes/shell.php
// ❌ Ejecuta PHP en servidor
```

### ✅ Protección Implementada

```php
public function cargarImagen($file) {
    // 1. Validar extensión
    $extensonesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($extension, $extensonesPermitidas)) {
        throw new Exception('Extensión no permitida');
    }
    
    // 2. Validar MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    
    if (!in_array($mime, ['image/jpeg', 'image/png', 'image/gif'])) {
        throw new Exception('Archivo no es imagen válida');
    }
    
    // 3. Generar nombre aleatorio (evita sobrescrituras)
    $nuevoNombre = uniqid('img_') . '.' . $extension;
    
    // 4. Guardar en directorio protegido
    // NO permitir ejecución de PHP
    // Directorio fuera del web root, o con .htaccess
    
    move_uploaded_file($file['tmp_name'], 
                      '/var/uploads/' . $nuevoNombre);
    
    // 5. Guardar ruta relativa en BD
    return 'https://cdn.tienda-ropa.com/imagenes/' . $nuevoNombre;
}
```

### .htaccess para Prevenir Ejecución de PHP

```apache
# publico/recursos/imagenes/.htaccess
<Files *>
    SetHandler default-handler
</Files>

# O más específico:
<FilesMatch "\.php$">
    Deny from all
</FilesMatch>

# Deshabilitar ejecución de scripts
php_flag engine off
```

---

## 10. Headers de Seguridad (Futuro)

```php
// config.php o middleware
header("X-Content-Type-Options: nosniff");           // Previene Content-Type sniffing
header("X-Frame-Options: DENY");                    // Previene clickjacking
header("Content-Security-Policy: default-src 'self'"); // Previene inline scripts
header("Strict-Transport-Security: max-age=31536000"); // Força HTTPS
header("X-XSS-Protection: 1; mode=block");          // Protección XSS browser
```

---

## 11. Logging de Seguridad

### Eventos a Registrar

```php
error_log("[AUTH] Login exitoso: usuario_id=" . $usuario['id']);
error_log("[AUTH] Intento de login fallido: email=" . $email . " ip=" . $_SERVER['REMOTE_ADDR']);
error_log("[ADMIN] Admin " . $_SESSION['usuario_id'] . " eliminó producto " . $productoId);
error_log("[ADMIN] Admin " . $_SESSION['usuario_id'] . " cambió rol de usuario " . $usuarioId);
error_log("[SECURITY] Acceso denegado: usuario " . $_SESSION['usuario_id'] . " intentó acceder a " . $_GET['accion']);
```

### Análisis de Logs

```bash
# Ver intentos de login fallidos
grep "\[AUTH\] Intento de login fallido" /var/log/php/error.log

# Contar por IP
grep "Intento de login fallido" /var/log/php/error.log | cut -d' ' -f14 | sort | uniq -c
```

---

## 12. Checklist de Seguridad

### Antes de Producción

- [ ] Cambiar credenciales default de BD
- [ ] Deshabilitar display_errors en producción
- [ ] Habilitar HTTPS
- [ ] Configurar CORS si hay API
- [ ] Implementar CSRF tokens
- [ ] Configurar rate limiting (login attempts)
- [ ] Backup automático de BD
- [ ] Monitoreo de logs de seguridad
- [ ] Validación de entrada en todos los endpoints
- [ ] Escape de salida en todas vistas
- [ ] Review de código por alguien más

### Chequeos Periódicos

- [ ] Analizar logs de acceso
- [ ] Buscar comportamientos sospechosos (bruteforcé, SQL injection attempts)
- [ ] Actualizar dependencias PHP
- [ ] Auditoría de cambios recientes
- [ ] Verificar integridad de archivos

---

## 13. Respuesta a Incidentes

### Si Sospochas Compromiso

```bash
1. Aislar servidor (desconectar de internet)
2. No apagar (para preservar logs en memoria)
3. Crear backup de discos para análisis
4. Revisar logs:
   - /var/log/apache2/access.log
   - /var/log/php/error.log
   - /var/log/auth.log (accesos a sistema)
5. Buscar archivos maliciosos:
   find /var/www -name "*.php" -newermt "2025-03-18" -ls
6. Buscar cuentas sospechosas en BD:
   SELECT * FROM usuarios WHERE created_at > '2025-03-18';
7. Cambiar credenciales de BD
8. Reiniciar servicios
9. Monitorear en las siguientes horas
```

---

**Última actualización**: Marzo 2025
