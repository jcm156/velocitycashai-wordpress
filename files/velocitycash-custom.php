<?php
/**
 * Plugin Name: VelocityCash Custom Functionality
 * Plugin URI: https://velocitycashai.com
 * Description: Sistema de conversión, analytics y webhooks para VelocityCash AI
 * Version: 1.0.0
 * Author: VelocityCash Team
 * Author URI: https://velocitycashai.com
 * License: GPL v2 or later
 * Text Domain: velocitycash-custom
 */

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

define('VELOCITYCASH_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('VELOCITYCASH_PLUGIN_URL', plugin_dir_url(__FILE__));
define('VELOCITYCASH_VERSION', '1.0.0');

/**
 * =============================================================================
 * INCLUDES
 * =============================================================================
 */
require_once VELOCITYCASH_PLUGIN_DIR . 'includes/class-analytics.php';
require_once VELOCITYCASH_PLUGIN_DIR . 'includes/class-webhooks.php';
require_once VELOCITYCASH_PLUGIN_DIR . 'includes/class-conversions.php';

/**
 * =============================================================================
 * INITIALIZATION
 * =============================================================================
 */
function velocitycash_init() {
    // Inicializar clases
    new VelocityCash_Analytics();
    new VelocityCash_Webhooks();
    new VelocityCash_Conversions();
}
add_action('plugins_loaded', 'velocitycash_init');

/**
 * =============================================================================
 * ACTIVATION HOOK
 * =============================================================================
 */
register_activation_hook(__FILE__, 'velocitycash_activate');

function velocitycash_activate() {
    // Crear tablas personalizadas si es necesario
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    
    // Tabla para tracking de conversiones
    $table_name = $wpdb->prefix . 'velocitycash_conversions';
    
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        user_id mediumint(9),
        event_type varchar(50) NOT NULL,
        event_data longtext,
        source varchar(255),
        timestamp datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id),
        KEY user_id (user_id),
        KEY event_type (event_type),
        KEY timestamp (timestamp)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    
    // Tabla para A/B testing
    $table_ab = $wpdb->prefix . 'velocitycash_ab_tests';
    
    $sql_ab = "CREATE TABLE IF NOT EXISTS $table_ab (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        test_name varchar(100) NOT NULL,
        variant varchar(50) NOT NULL,
        user_id mediumint(9),
        converted tinyint(1) DEFAULT 0,
        timestamp datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id),
        KEY test_name (test_name),
        KEY variant (variant)
    ) $charset_collate;";
    
    dbDelta($sql_ab);
    
    // Configuración por defecto
    add_option('velocitycash_n8n_webhook', 'https://jcm156.app.n8n.cloud/webhook/');
    add_option('velocitycash_tracking_enabled', 'yes');
    add_option('velocitycash_ab_testing_enabled', 'yes');
    
    // Flush rewrite rules
    flush_rewrite_rules();
}

/**
 * =============================================================================
 * DEACTIVATION HOOK
 * =============================================================================
 */
register_deactivation_hook(__FILE__, 'velocitycash_deactivate');

function velocitycash_deactivate() {
    flush_rewrite_rules();
}

/**
 * =============================================================================
 * ADMIN MENU
 * =============================================================================
 */
add_action('admin_menu', 'velocitycash_admin_menu');

function velocitycash_admin_menu() {
    add_menu_page(
        'VelocityCash Dashboard',
        'VelocityCash',
        'manage_options',
        'velocitycash-dashboard',
        'velocitycash_dashboard_page',
        'dashicons-chart-line',
        30
    );
    
    add_submenu_page(
        'velocitycash-dashboard',
        'Analytics',
        'Analytics',
        'manage_options',
        'velocitycash-analytics',
        'velocitycash_analytics_page'
    );
    
    add_submenu_page(
        'velocitycash-dashboard',
        'A/B Testing',
        'A/B Testing',
        'manage_options',
        'velocitycash-ab-testing',
        'velocitycash_ab_testing_page'
    );
    
    add_submenu_page(
        'velocitycash-dashboard',
        'Configuración',
        'Configuración',
        'manage_options',
        'velocitycash-settings',
        'velocitycash_settings_page'
    );
}

/**
 * =============================================================================
 * DASHBOARD PAGE
 * =============================================================================
 */
function velocitycash_dashboard_page() {
    global $wpdb;
    
    // Obtener estadísticas de los últimos 30 días
    $table_name = $wpdb->prefix . 'velocitycash_conversions';
    
    $stats = array(
        'total_conversions' => $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE timestamp >= DATE_SUB(NOW(), INTERVAL 30 DAY)"),
        'total_revenue' => 0, // Calcular desde WooCommerce
        'conversion_rate' => 0,
        'avg_order_value' => 0
    );
    
    // Obtener revenue de WooCommerce
    $orders = wc_get_orders(array(
        'limit' => -1,
        'date_created' => '>=' . (time() - (30 * 24 * 60 * 60)),
        'status' => array('wc-completed', 'wc-processing')
    ));
    
    foreach ($orders as $order) {
        $stats['total_revenue'] += $order->get_total();
    }
    
    if (count($orders) > 0) {
        $stats['avg_order_value'] = $stats['total_revenue'] / count($orders);
    }
    
    ?>
    <div class="wrap velocitycash-dashboard">
        <h1>VelocityCash AI - Dashboard</h1>
        
        <div class="velocitycash-stats-grid">
            <div class="stat-card">
                <h3>Conversiones (30d)</h3>
                <div class="stat-number"><?php echo number_format($stats['total_conversions']); ?></div>
            </div>
            
            <div class="stat-card">
                <h3>Revenue (30d)</h3>
                <div class="stat-number">$<?php echo number_format($stats['total_revenue'], 2); ?></div>
            </div>
            
            <div class="stat-card">
                <h3>Valor Promedio Pedido</h3>
                <div class="stat-number">$<?php echo number_format($stats['avg_order_value'], 2); ?></div>
            </div>
            
            <div class="stat-card">
                <h3>Total Pedidos</h3>
                <div class="stat-number"><?php echo count($orders); ?></div>
            </div>
        </div>
        
        <div class="velocitycash-recent-activity">
            <h2>Actividad Reciente</h2>
            <?php
            $recent = $wpdb->get_results("SELECT * FROM $table_name ORDER BY timestamp DESC LIMIT 10");
            
            if ($recent) {
                echo '<table class="wp-list-table widefat">';
                echo '<thead><tr><th>Evento</th><th>Usuario</th><th>Fecha</th></tr></thead>';
                echo '<tbody>';
                
                foreach ($recent as $event) {
                    $user = get_userdata($event->user_id);
                    $user_name = $user ? $user->display_name : 'Visitante';
                    
                    echo '<tr>';
                    echo '<td>' . esc_html($event->event_type) . '</td>';
                    echo '<td>' . esc_html($user_name) . '</td>';
                    echo '<td>' . esc_html($event->timestamp) . '</td>';
                    echo '</tr>';
                }
                
                echo '</tbody></table>';
            } else {
                echo '<p>No hay actividad reciente.</p>';
            }
            ?>
        </div>
    </div>
    
    <style>
    .velocitycash-dashboard {
        padding: 20px;
    }
    
    .velocitycash-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin: 30px 0;
    }
    
    .stat-card {
        background: white;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        text-align: center;
    }
    
    .stat-card h3 {
        margin: 0 0 15px 0;
        font-size: 14px;
        color: #666;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .stat-number {
        font-size: 32px;
        font-weight: 700;
        color: #0066FF;
    }
    
    .velocitycash-recent-activity {
        background: white;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-top: 30px;
    }
    </style>
    <?php
}

/**
 * =============================================================================
 * ANALYTICS PAGE
 * =============================================================================
 */
function velocitycash_analytics_page() {
    ?>
    <div class="wrap">
        <h1>Analytics Avanzado</h1>
        <p>Aquí se mostrarán gráficos detallados de conversiones, funnel analysis, y más.</p>
        <!-- Integrar Chart.js o similar para visualizaciones -->
    </div>
    <?php
}

/**
 * =============================================================================
 * A/B TESTING PAGE
 * =============================================================================
 */
function velocitycash_ab_testing_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'velocitycash_ab_tests';
    
    // Obtener tests activos
    $tests = $wpdb->get_results("
        SELECT test_name, variant, COUNT(*) as views,
               SUM(converted) as conversions,
               (SUM(converted) / COUNT(*)) * 100 as conversion_rate
        FROM $table_name
        GROUP BY test_name, variant
        ORDER BY test_name, variant
    ");
    
    ?>
    <div class="wrap">
        <h1>A/B Testing</h1>
        
        <?php if ($tests): ?>
            <table class="wp-list-table widefat">
                <thead>
                    <tr>
                        <th>Test</th>
                        <th>Variante</th>
                        <th>Vistas</th>
                        <th>Conversiones</th>
                        <th>Tasa de Conversión</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tests as $test): ?>
                        <tr>
                            <td><?php echo esc_html($test->test_name); ?></td>
                            <td><?php echo esc_html($test->variant); ?></td>
                            <td><?php echo number_format($test->views); ?></td>
                            <td><?php echo number_format($test->conversions); ?></td>
                            <td><?php echo number_format($test->conversion_rate, 2); ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay tests A/B activos actualmente.</p>
        <?php endif; ?>
        
        <h2>Crear Nuevo Test A/B</h2>
        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
            <input type="hidden" name="action" value="velocitycash_create_ab_test" />
            <?php wp_nonce_field('velocitycash_ab_test_nonce'); ?>
            
            <table class="form-table">
                <tr>
                    <th><label for="test_name">Nombre del Test</label></th>
                    <td><input type="text" name="test_name" id="test_name" class="regular-text" required /></td>
                </tr>
                <tr>
                    <th><label for="variant_a">Variante A</label></th>
                    <td><textarea name="variant_a" id="variant_a" rows="5" class="large-text"></textarea></td>
                </tr>
                <tr>
                    <th><label for="variant_b">Variante B</label></th>
                    <td><textarea name="variant_b" id="variant_b" rows="5" class="large-text"></textarea></td>
                </tr>
            </table>
            
            <?php submit_button('Crear Test A/B'); ?>
        </form>
    </div>
    <?php
}

/**
 * =============================================================================
 * SETTINGS PAGE
 * =============================================================================
 */
function velocitycash_settings_page() {
    if (isset($_POST['velocitycash_save_settings'])) {
        check_admin_referer('velocitycash_settings_nonce');
        
        update_option('velocitycash_n8n_webhook', sanitize_text_field($_POST['n8n_webhook']));
        update_option('velocitycash_mailchimp_key', sanitize_text_field($_POST['mailchimp_key']));
        update_option('velocitycash_facebook_pixel', sanitize_text_field($_POST['facebook_pixel']));
        update_option('velocitycash_ga4_id', sanitize_text_field($_POST['ga4_id']));
        update_option('velocitycash_tracking_enabled', isset($_POST['tracking_enabled']) ? 'yes' : 'no');
        
        echo '<div class="notice notice-success"><p>Configuración guardada correctamente.</p></div>';
    }
    
    $n8n_webhook = get_option('velocitycash_n8n_webhook', 'https://jcm156.app.n8n.cloud/webhook/');
    $mailchimp_key = get_option('velocitycash_mailchimp_key', '');
    $facebook_pixel = get_option('velocitycash_facebook_pixel', '');
    $ga4_id = get_option('velocitycash_ga4_id', '');
    $tracking_enabled = get_option('velocitycash_tracking_enabled', 'yes');
    
    ?>
    <div class="wrap">
        <h1>Configuración VelocityCash</h1>
        
        <form method="post">
            <?php wp_nonce_field('velocitycash_settings_nonce'); ?>
            
            <h2>Integraciones</h2>
            <table class="form-table">
                <tr>
                    <th><label for="n8n_webhook">n8n Webhook Base URL</label></th>
                    <td>
                        <input type="url" name="n8n_webhook" id="n8n_webhook" 
                               value="<?php echo esc_attr($n8n_webhook); ?>" class="regular-text" />
                        <p class="description">URL base de tus webhooks n8n</p>
                    </td>
                </tr>
                
                <tr>
                    <th><label for="mailchimp_key">Mailchimp API Key</label></th>
                    <td>
                        <input type="text" name="mailchimp_key" id="mailchimp_key" 
                               value="<?php echo esc_attr($mailchimp_key); ?>" class="regular-text" />
                    </td>
                </tr>
                
                <tr>
                    <th><label for="facebook_pixel">Facebook Pixel ID</label></th>
                    <td>
                        <input type="text" name="facebook_pixel" id="facebook_pixel" 
                               value="<?php echo esc_attr($facebook_pixel); ?>" class="regular-text" />
                    </td>
                </tr>
                
                <tr>
                    <th><label for="ga4_id">Google Analytics 4 ID</label></th>
                    <td>
                        <input type="text" name="ga4_id" id="ga4_id" 
                               value="<?php echo esc_attr($ga4_id); ?>" class="regular-text" />
                        <p class="description">Ejemplo: G-XXXXXXXXXX</p>
                    </td>
                </tr>
            </table>
            
            <h2>Opciones de Tracking</h2>
            <table class="form-table">
                <tr>
                    <th><label for="tracking_enabled">Activar Tracking</label></th>
                    <td>
                        <input type="checkbox" name="tracking_enabled" id="tracking_enabled" 
                               <?php checked($tracking_enabled, 'yes'); ?> />
                        <label for="tracking_enabled">Habilitar tracking de eventos y conversiones</label>
                    </td>
                </tr>
            </table>
            
            <input type="submit" name="velocitycash_save_settings" class="button button-primary" value="Guardar Cambios" />
        </form>
    </div>
    <?php
}

/**
 * =============================================================================
 * CUSTOM REST API ENDPOINTS
 * =============================================================================
 */
add_action('rest_api_init', function() {
    // Endpoint para tracking de eventos
    register_rest_route('velocitycash/v1', '/track', array(
        'methods' => 'POST',
        'callback' => 'velocitycash_track_event',
        'permission_callback' => '__return_true'
    ));
    
    // Endpoint para stats
    register_rest_route('velocitycash/v1', '/stats', array(
        'methods' => 'GET',
        'callback' => 'velocitycash_get_stats',
        'permission_callback' => function() {
            return current_user_can('manage_options');
        }
    ));
});

function velocitycash_track_event(WP_REST_Request $request) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'velocitycash_conversions';
    
    $event_type = sanitize_text_field($request->get_param('event_type'));
    $event_data = $request->get_param('event_data');
    $source = sanitize_text_field($request->get_param('source'));
    
    $wpdb->insert(
        $table_name,
        array(
            'user_id' => get_current_user_id(),
            'event_type' => $event_type,
            'event_data' => json_encode($event_data),
            'source' => $source
        )
    );
    
    return new WP_REST_Response(array('success' => true), 200);
}

function velocitycash_get_stats() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'velocitycash_conversions';
    
    $stats = array(
        'total_events' => $wpdb->get_var("SELECT COUNT(*) FROM $table_name"),
        'events_today' => $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE DATE(timestamp) = CURDATE()"),
        'events_this_week' => $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE YEARWEEK(timestamp) = YEARWEEK(NOW())")
    );
    
    return new WP_REST_Response($stats, 200);
}
