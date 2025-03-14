<?php
// configuracion/config.php
define('DB_HOST', 'localhost');
define('DB_NAME', 'tienda');
define('DB_USER', 'root');
define('DB_PASSWORD', '');

// Crear la conexión a la base de datos
try {
    $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Habilitar excepciones para errores de PDO
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}

// Configuración de sesiones
session_start(); // Asegúrate de iniciar la sesión
//ini_set('session.cookie_httponly', 1);
//ini_set('session.use_only_cookies', 1);
//ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));

// Para depuración (solo en desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// En producción, desactiva la visualización de errores y usa un log
// ini_set('display_errors', 0);
// ini_set('log_errors', 1);
// ini_set('error_log', __DIR__ . '/../logs/error.log');