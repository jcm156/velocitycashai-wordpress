<?php
/**
 * Configuración de WordPress para VelocityCash AI
 * 
 * IMPORTANT: Antes de subir este archivo, genera tus propias keys en:
 * https://api.wordpress.org/secret-key/1.1/salt/
 * 
 * @package WordPress
 */

// ** Configuración de Base de Datos - obtener de SiteGround ** //
define( 'DB_NAME', 'velocitycashai_db' );
define( 'DB_USER', 'velocitycashai_user' );
define( 'DB_PASSWORD', 'TU_PASSWORD_AQUI' );  // CAMBIAR
define( 'DB_HOST', 'localhost' );
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 * CRITICAL: Genera tus propias keys en https://api.wordpress.org/secret-key/1.1/salt/
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'pon-tu-frase-unica-aqui' );
define( 'SECURE_AUTH_KEY',  'pon-tu-frase-unica-aqui' );
define( 'LOGGED_IN_KEY',    'pon-tu-frase-unica-aqui' );
define( 'NONCE_KEY',        'pon-tu-frase-unica-aqui' );
define( 'AUTH_SALT',        'pon-tu-frase-unica-aqui' );
define( 'SECURE_AUTH_SALT', 'pon-tu-frase-unica-aqui' );
define( 'LOGGED_IN_SALT',   'pon-tu-frase-unica-aqui' );
define( 'NONCE_SALT',       'pon-tu-frase-unica-aqui' );
/**#@-*/

/**
 * WordPress Database Table prefix.
 */
$table_prefix = 'vc_';

/**
 * =============================================================================
 * SECURITY HARDENING
 * =============================================================================
 */

// Deshabilitar edición de archivos desde el admin
define( 'DISALLOW_FILE_EDIT', true );

// Deshabilitar instalación de plugins/themes desde admin (opcional - comentar si necesitas instalar cosas)
// define( 'DISALLOW_FILE_MODS', true );

// Forzar SSL en admin
define( 'FORCE_SSL_ADMIN', true );

// Aumentar límite de memoria
define( 'WP_MEMORY_LIMIT', '256M' );
define( 'WP_MAX_MEMORY_LIMIT', '512M' );

// Deshabilitar revisiones de posts (performance)
define( 'WP_POST_REVISIONS', 5 );

// Auto-save cada 5 minutos en vez de cada 60 segundos
define( 'AUTOSAVE_INTERVAL', 300 );

// Vaciar papelera cada 7 días
define( 'EMPTY_TRASH_DAYS', 7 );

/**
 * =============================================================================
 * PERFORMANCE OPTIMIZATION
 * =============================================================================
 */

// Cache de objetos (si tienes Redis o Memcached en SiteGround)
// define( 'WP_CACHE', true );

// Comprimir scripts y estilos
define( 'COMPRESS_SCRIPTS', true );
define( 'COMPRESS_CSS', true );
define( 'ENFORCE_GZIP', true );

// Concatenar scripts (mejora performance)
define( 'CONCATENATE_SCRIPTS', true );

/**
 * =============================================================================
 * DEBUG (DESACTIVAR EN PRODUCCIÓN)
 * =============================================================================
 */

// En desarrollo
define( 'WP_DEBUG', false );
define( 'WP_DEBUG_LOG', false );
define( 'WP_DEBUG_DISPLAY', false );
@ini_set( 'display_errors', 0 );

// Cuando estés depurando problemas, cambiar a:
// define( 'WP_DEBUG', true );
// define( 'WP_DEBUG_LOG', true );
// define( 'WP_DEBUG_DISPLAY', false );

/**
 * =============================================================================
 * CUSTOM SETTINGS VELOCITYCASH
 * =============================================================================
 */

// URLs personalizadas
define( 'WP_HOME', 'https://velocitycashai.com' );
define( 'WP_SITEURL', 'https://velocitycashai.com' );

// Cookies personalizadas para seguridad adicional
define( 'COOKIEHASH', md5( 'velocitycashai-' . WP_SITEURL ) );
define( 'COOKIE_DOMAIN', '.velocitycashai.com' );

// Aumentar timeout para operaciones largas (útil para imports, etc)
define( 'WP_HTTP_BLOCK_EXTERNAL', false );

/**
 * =============================================================================
 * WOOCOMMERCE OPTIMIZATIONS
 * =============================================================================
 */

// Deshabilitar heartbeat API en admin (mejora performance)
define( 'WP_ADMIN_HEARTBEAT', 60 );

// Limitar heartbeat en frontend
define( 'WP_HEARTBEAT_INTERVAL', 120 );

/**
 * =============================================================================
 * FILESYSTEM METHOD
 * =============================================================================
 */

// Método directo para actualizaciones (SiteGround lo permite)
define( 'FS_METHOD', 'direct' );

/**
 * =============================================================================
 * CRON JOBS
 * =============================================================================
 */

// Desactivar WP-Cron y usar cron real del servidor (mejor performance)
// Solo habilitar esto si configuraste un cron job real en SiteGround
// define( 'DISABLE_WP_CRON', true );
// define( 'ALTERNATE_WP_CRON', true );

/**
 * =============================================================================
 * MULTISITE (si lo necesitas en el futuro)
 * =============================================================================
 */

// define( 'WP_ALLOW_MULTISITE', true );
// define( 'MULTISITE', true );
// define( 'SUBDOMAIN_INSTALL', false );
// define( 'DOMAIN_CURRENT_SITE', 'velocitycashai.com' );
// define( 'PATH_CURRENT_SITE', '/' );
// define( 'SITE_ID_CURRENT_SITE', 1 );
// define( 'BLOG_ID_CURRENT_SITE', 1 );

/**
 * =============================================================================
 * ABSOLUTE PATH
 * =============================================================================
 */
if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
