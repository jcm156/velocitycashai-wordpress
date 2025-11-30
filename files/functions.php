<?php
/**
 * VelocityCash AI Child Theme Functions
 * 
 * @package VelocityCash_AI
 * @version 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * =============================================================================
 * CONFIGURACIÓN BÁSICA
 * =============================================================================
 */

// Enqueue parent and child theme styles
function velocitycash_enqueue_styles() {
    // Parent theme styles
    wp_enqueue_style('astra-parent-style', get_template_directory_uri() . '/style.css', array(), '1.0.0');
    
    // Child theme styles
    wp_enqueue_style('velocitycash-child-style', get_stylesheet_directory_uri() . '/style.css', array('astra-parent-style'), '1.0.0');
    
    // Google Fonts - Inter
    wp_enqueue_style('google-fonts-inter', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap', array(), null);
    
    // Google Fonts - JetBrains Mono
    wp_enqueue_style('google-fonts-jetbrains', 'https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap', array(), null);
    
    // Custom JavaScript
    wp_enqueue_script('velocitycash-scripts', get_stylesheet_directory_uri() . '/assets/js/custom.js', array('jquery'), '1.0.0', true);
    
    // Localizar script para AJAX
    wp_localize_script('velocitycash-scripts', 'velocitycashAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('velocitycash_nonce'),
        'n8nWebhook' => get_option('velocitycash_n8n_webhook', 'https://jcm156.app.n8n.cloud/webhook/')
    ));
}
add_action('wp_enqueue_scripts', 'velocitycash_enqueue_styles');

/**
 * =============================================================================
 * THEME SUPPORT
 * =============================================================================
 */
function velocitycash_theme_support() {
    // Add WooCommerce support
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    
    // Add editor styles
    add_theme_support('editor-styles');
    
    // Add responsive embeds
    add_theme_support('responsive-embeds');
    
    // Add custom logo
    add_theme_support('custom-logo', array(
        'height' => 60,
        'width' => 240,
        'flex-height' => true,
        'flex-width' => true,
    ));
}
add_action('after_setup_theme', 'velocitycash_theme_support');

/**
 * =============================================================================
 * REGISTRO DE SIDEBARS Y WIDGET AREAS
 * =============================================================================
 */
function velocitycash_register_sidebars() {
    // Sidebar del blog
    register_sidebar(array(
        'name' => __('Blog Sidebar', 'velocitycash-child'),
        'id' => 'blog-sidebar',
        'description' => __('Aparece en posts de blog', 'velocitycash-child'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    
    // Footer widgets
    for ($i = 1; $i <= 4; $i++) {
        register_sidebar(array(
            'name' => sprintf(__('Footer Widget %d', 'velocitycash-child'), $i),
            'id' => 'footer-widget-' . $i,
            'description' => sprintf(__('Footer widget area %d', 'velocitycash-child'), $i),
            'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="footer-widget-title">',
            'after_title' => '</h4>',
        ));
    }
}
add_action('widgets_init', 'velocitycash_register_sidebars');

/**
 * =============================================================================
 * CUSTOM POST TYPES
 * =============================================================================
 */
function velocitycash_register_cpts() {
    // Testimonios
    register_post_type('testimonial', array(
        'labels' => array(
            'name' => __('Testimonios', 'velocitycash-child'),
            'singular_name' => __('Testimonio', 'velocitycash-child'),
            'add_new' => __('Añadir Nuevo', 'velocitycash-child'),
            'add_new_item' => __('Añadir Nuevo Testimonio', 'velocitycash-child'),
            'edit_item' => __('Editar Testimonio', 'velocitycash-child'),
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'menu_icon' => 'dashicons-format-quote',
        'show_in_rest' => true,
    ));
    
    // Casos de Estudio
    register_post_type('case_study', array(
        'labels' => array(
            'name' => __('Casos de Estudio', 'velocitycash-child'),
            'singular_name' => __('Caso de Estudio', 'velocitycash-child'),
            'add_new' => __('Añadir Nuevo', 'velocitycash-child'),
            'add_new_item' => __('Añadir Nuevo Caso', 'velocitycash-child'),
            'edit_item' => __('Editar Caso', 'velocitycash-child'),
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon' => 'dashicons-chart-line',
        'show_in_rest' => true,
    ));
}
add_action('init', 'velocitycash_register_cpts');

/**
 * =============================================================================
 * WOOCOMMERCE CUSTOMIZATION
 * =============================================================================
 */

// Cambiar número de productos por fila
function velocitycash_loop_columns() {
    return 3;
}
add_filter('loop_shop_columns', 'velocitycash_loop_columns');

// Añadir badge de garantía en productos
function velocitycash_add_guarantee_badge() {
    echo '<div class="guarantee-badge-product">
        <img src="' . get_stylesheet_directory_uri() . '/assets/images/90-day-guarantee.svg" alt="Garantía 90 días" />
        <span>Garantía de Devolución 90 Días</span>
    </div>';
}
add_action('woocommerce_before_add_to_cart_button', 'velocitycash_add_guarantee_badge');

// Añadir value stack debajo del precio
function velocitycash_add_value_stack() {
    global $product;
    
    $product_id = $product->get_id();
    $bonuses = get_post_meta($product_id, '_velocitycash_bonuses', true);
    
    if (!empty($bonuses)) {
        echo '<div class="product-value-stack">';
        echo '<h3>Incluye Estos Bonuses GRATIS:</h3>';
        echo '<ul class="value-stack-list">';
        
        foreach ($bonuses as $bonus) {
            echo '<li>';
            echo '<span class="bonus-name">' . esc_html($bonus['name']) . '</span>';
            echo '<span class="bonus-value">Valor: $' . esc_html($bonus['value']) . '</span>';
            echo '</li>';
        }
        
        echo '</ul>';
        echo '</div>';
    }
}
add_action('woocommerce_single_product_summary', 'velocitycash_add_value_stack', 25);

// Countdown timer en productos
function velocitycash_product_countdown() {
    global $product;
    
    $end_date = get_post_meta($product->get_id(), '_velocitycash_offer_end', true);
    
    if ($end_date) {
        echo '<div class="product-countdown-wrapper">';
        echo '<p class="countdown-label">⚡ OFERTA TERMINA EN:</p>';
        echo '<div class="countdown-timer" data-end="' . esc_attr($end_date) . '">';
        echo '<div class="countdown-segment"><span class="days">00</span><span class="label">Días</span></div>';
        echo '<div class="countdown-segment"><span class="hours">00</span><span class="label">Horas</span></div>';
        echo '<div class="countdown-segment"><span class="minutes">00</span><span class="label">Min</span></div>';
        echo '<div class="countdown-segment"><span class="seconds">00</span><span class="label">Seg</span></div>';
        echo '</div>';
        echo '</div>';
    }
}
add_action('woocommerce_single_product_summary', 'velocitycash_product_countdown', 15);

/**
 * =============================================================================
 * N8N WEBHOOK INTEGRATION
 * =============================================================================
 */

// Enviar datos a n8n cuando hay nueva compra
function velocitycash_send_to_n8n_purchase($order_id) {
    $order = wc_get_order($order_id);
    
    $webhook_url = get_option('velocitycash_n8n_webhook', 'https://jcm156.app.n8n.cloud/webhook/') . 'purchase';
    
    $data = array(
        'event' => 'purchase',
        'order_id' => $order_id,
        'customer_email' => $order->get_billing_email(),
        'customer_name' => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
        'total' => $order->get_total(),
        'currency' => $order->get_currency(),
        'products' => array(),
        'timestamp' => current_time('mysql')
    );
    
    foreach ($order->get_items() as $item) {
        $product = $item->get_product();
        $data['products'][] = array(
            'name' => $product->get_name(),
            'quantity' => $item->get_quantity(),
            'price' => $product->get_price()
        );
    }
    
    wp_remote_post($webhook_url, array(
        'body' => json_encode($data),
        'headers' => array('Content-Type' => 'application/json')
    ));
}
add_action('woocommerce_thankyou', 'velocitycash_send_to_n8n_purchase');

// Enviar datos a n8n cuando hay nuevo registro
function velocitycash_send_to_n8n_registration($user_id) {
    $user = get_userdata($user_id);
    
    $webhook_url = get_option('velocitycash_n8n_webhook', 'https://jcm156.app.n8n.cloud/webhook/') . 'registration';
    
    $data = array(
        'event' => 'registration',
        'user_id' => $user_id,
        'user_email' => $user->user_email,
        'user_name' => $user->display_name,
        'timestamp' => current_time('mysql')
    );
    
    wp_remote_post($webhook_url, array(
        'body' => json_encode($data),
        'headers' => array('Content-Type' => 'application/json')
    ));
}
add_action('user_register', 'velocitycash_send_to_n8n_registration');

// Enviar datos a n8n cuando alguien descarga lead magnet
function velocitycash_send_to_n8n_lead_magnet() {
    check_ajax_referer('velocitycash_nonce', 'nonce');
    
    $email = sanitize_email($_POST['email']);
    $magnet_id = sanitize_text_field($_POST['magnet_id']);
    
    $webhook_url = get_option('velocitycash_n8n_webhook', 'https://jcm156.app.n8n.cloud/webhook/') . 'lead-magnet';
    
    $data = array(
        'event' => 'lead_magnet',
        'email' => $email,
        'magnet_id' => $magnet_id,
        'url' => $_POST['url'],
        'timestamp' => current_time('mysql')
    );
    
    $response = wp_remote_post($webhook_url, array(
        'body' => json_encode($data),
        'headers' => array('Content-Type' => 'application/json')
    ));
    
    if (!is_wp_error($response)) {
        wp_send_json_success(array('message' => 'Gracias! Revisa tu email.'));
    } else {
        wp_send_json_error(array('message' => 'Error al procesar. Intenta de nuevo.'));
    }
}
add_action('wp_ajax_velocitycash_lead_magnet', 'velocitycash_send_to_n8n_lead_magnet');
add_action('wp_ajax_nopriv_velocitycash_lead_magnet', 'velocitycash_send_to_n8n_lead_magnet');

// Carrito abandonado - tracking
function velocitycash_track_cart_abandonment() {
    if (!is_user_logged_in() && WC()->cart->get_cart_contents_count() > 0) {
        $cart_data = array(
            'items' => WC()->cart->get_cart(),
            'total' => WC()->cart->get_cart_contents_total(),
            'timestamp' => current_time('timestamp')
        );
        
        setcookie('velocitycash_cart', json_encode($cart_data), time() + (86400 * 7), '/');
    }
}
add_action('wp_footer', 'velocitycash_track_cart_abandonment');

/**
 * =============================================================================
 * CONVERSION TRACKING
 * =============================================================================
 */

// Añadir conversion pixel en página de gracias
function velocitycash_conversion_tracking($order_id) {
    $order = wc_get_order($order_id);
    ?>
    <script>
    // Facebook Pixel
    if (typeof fbq !== 'undefined') {
        fbq('track', 'Purchase', {
            value: <?php echo $order->get_total(); ?>,
            currency: '<?php echo $order->get_currency(); ?>',
            content_ids: [<?php 
                $product_ids = array();
                foreach ($order->get_items() as $item) {
                    $product_ids[] = $item->get_product_id();
                }
                echo implode(',', $product_ids);
            ?>],
            content_type: 'product'
        });
    }
    
    // Google Analytics 4
    if (typeof gtag !== 'undefined') {
        gtag('event', 'purchase', {
            transaction_id: '<?php echo $order_id; ?>',
            value: <?php echo $order->get_total(); ?>,
            currency: '<?php echo $order->get_currency(); ?>',
            items: [
                <?php foreach ($order->get_items() as $item): 
                    $product = $item->get_product();
                ?>
                {
                    item_id: '<?php echo $product->get_id(); ?>',
                    item_name: '<?php echo $product->get_name(); ?>',
                    price: <?php echo $product->get_price(); ?>,
                    quantity: <?php echo $item->get_quantity(); ?>
                },
                <?php endforeach; ?>
            ]
        });
    }
    </script>
    <?php
}
add_action('woocommerce_thankyou', 'velocitycash_conversion_tracking', 20);

/**
 * =============================================================================
 * SHORTCODES PERSONALIZADOS
 * =============================================================================
 */

// Shortcode para countdown timer
function velocitycash_countdown_shortcode($atts) {
    $atts = shortcode_atts(array(
        'date' => '',
        'text' => 'Oferta termina en:'
    ), $atts);
    
    ob_start();
    ?>
    <div class="scarcity-bar">
        <span><?php echo esc_html($atts['text']); ?></span>
        <div class="countdown-timer" data-end="<?php echo esc_attr($atts['date']); ?>">
            <div class="countdown-segment">
                <span class="days">00</span>
                <span class="countdown-label">Días</span>
            </div>
            <div class="countdown-segment">
                <span class="hours">00</span>
                <span class="countdown-label">Horas</span>
            </div>
            <div class="countdown-segment">
                <span class="minutes">00</span>
                <span class="countdown-label">Min</span>
            </div>
            <div class="countdown-segment">
                <span class="seconds">00</span>
                <span class="countdown-label">Seg</span>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('countdown', 'velocitycash_countdown_shortcode');

// Shortcode para lead magnet box
function velocitycash_lead_magnet_shortcode($atts) {
    $atts = shortcode_atts(array(
        'title' => 'Descarga Gratis',
        'description' => 'Consigue acceso instantáneo',
        'magnet_id' => '',
        'button_text' => 'Descargar Ahora'
    ), $atts);
    
    ob_start();
    ?>
    <div class="lead-magnet-box">
        <h3><?php echo esc_html($atts['title']); ?></h3>
        <p><?php echo esc_html($atts['description']); ?></p>
        <form class="lead-magnet-form" data-magnet="<?php echo esc_attr($atts['magnet_id']); ?>">
            <input type="email" name="email" placeholder="Tu mejor email" required />
            <button type="submit" class="btn btn-secondary"><?php echo esc_html($atts['button_text']); ?></button>
        </form>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('lead_magnet', 'velocitycash_lead_magnet_shortcode');

// Shortcode para pricing table
function velocitycash_pricing_shortcode($atts) {
    ob_start();
    ?>
    <div class="pricing-section">
        <div class="pricing-grid">
            <!-- Pricing cards se añaden dinámicamente desde productos WooCommerce -->
            <?php
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => 4,
                'meta_key' => '_velocitycash_display_order',
                'orderby' => 'meta_value_num',
                'order' => 'ASC'
            );
            
            $products = new WP_Query($args);
            
            while ($products->have_posts()) : $products->the_post();
                global $product;
                $featured = get_post_meta($product->get_id(), '_velocitycash_featured', true);
                ?>
                <div class="pricing-card <?php echo $featured ? 'featured' : ''; ?>">
                    <?php if ($featured): ?>
                        <div class="pricing-badge">MÁS POPULAR</div>
                    <?php endif; ?>
                    
                    <h3 class="pricing-name"><?php echo $product->get_name(); ?></h3>
                    <div class="pricing-price">$<?php echo $product->get_price(); ?></div>
                    <p class="pricing-period">Pago único</p>
                    
                    <div class="pricing-description">
                        <?php echo $product->get_short_description(); ?>
                    </div>
                    
                    <a href="<?php echo $product->get_permalink(); ?>" class="btn btn-primary btn-full">
                        Conseguir Acceso Ahora
                    </a>
                </div>
                <?php
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('pricing_table', 'velocitycash_pricing_shortcode');

/**
 * =============================================================================
 * ADMIN CUSTOMIZATION
 * =============================================================================
 */

// Añadir custom fields a productos
function velocitycash_add_product_custom_fields() {
    global $post;
    
    echo '<div class="options_group">';
    
    // Featured product
    woocommerce_wp_checkbox(array(
        'id' => '_velocitycash_featured',
        'label' => __('Producto Destacado', 'velocitycash-child'),
        'description' => __('Marcar como el más popular', 'velocitycash-child')
    ));
    
    // Display order
    woocommerce_wp_text_input(array(
        'id' => '_velocitycash_display_order',
        'label' => __('Orden de Visualización', 'velocitycash-child'),
        'type' => 'number',
        'custom_attributes' => array('step' => '1', 'min' => '0')
    ));
    
    // Offer end date
    woocommerce_wp_text_input(array(
        'id' => '_velocitycash_offer_end',
        'label' => __('Fin de Oferta', 'velocitycash-child'),
        'type' => 'datetime-local',
        'description' => __('Mostrar countdown hasta esta fecha', 'velocitycash-child')
    ));
    
    echo '</div>';
}
add_action('woocommerce_product_options_general_product_data', 'velocitycash_add_product_custom_fields');

// Guardar custom fields
function velocitycash_save_product_custom_fields($post_id) {
    $featured = isset($_POST['_velocitycash_featured']) ? 'yes' : 'no';
    update_post_meta($post_id, '_velocitycash_featured', $featured);
    
    if (isset($_POST['_velocitycash_display_order'])) {
        update_post_meta($post_id, '_velocitycash_display_order', sanitize_text_field($_POST['_velocitycash_display_order']));
    }
    
    if (isset($_POST['_velocitycash_offer_end'])) {
        update_post_meta($post_id, '_velocitycash_offer_end', sanitize_text_field($_POST['_velocitycash_offer_end']));
    }
}
add_action('woocommerce_process_product_meta', 'velocitycash_save_product_custom_fields');

/**
 * =============================================================================
 * PERFORMANCE OPTIMIZATION
 * =============================================================================
 */

// Desactivar emojis
function velocitycash_disable_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
}
add_action('init', 'velocitycash_disable_emojis');

// Defer parsing of JavaScript
function velocitycash_defer_parsing_js($url) {
    if (is_admin()) return $url;
    if (false === strpos($url, '.js')) return $url;
    if (strpos($url, 'jquery.min.js')) return $url;
    return str_replace(' src', ' defer src', $url);
}
add_filter('script_loader_tag', 'velocitycash_defer_parsing_js', 10);

// Remove query strings from static resources
function velocitycash_remove_query_strings($src) {
    if (strpos($src, '?ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('style_loader_src', 'velocitycash_remove_query_strings', 10, 2);
add_filter('script_loader_src', 'velocitycash_remove_query_strings', 10, 2);

/**
 * =============================================================================
 * SECURITY HARDENING
 * =============================================================================
 */

// Remove WordPress version from header
remove_action('wp_head', 'wp_generator');

// Disable XML-RPC
add_filter('xmlrpc_enabled', '__return_false');

// Remove RSD link
remove_action('wp_head', 'rsd_link');

// Remove wlwmanifest link
remove_action('wp_head', 'wlwmanifest_link');

/**
 * =============================================================================
 * SETTINGS PAGE
 * =============================================================================
 */
function velocitycash_add_settings_page() {
    add_options_page(
        'VelocityCash Settings',
        'VelocityCash',
        'manage_options',
        'velocitycash-settings',
        'velocitycash_settings_page'
    );
}
add_action('admin_menu', 'velocitycash_add_settings_page');

function velocitycash_settings_page() {
    ?>
    <div class="wrap">
        <h1>VelocityCash AI - Configuración</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('velocitycash_settings');
            do_settings_sections('velocitycash-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function velocitycash_register_settings() {
    register_setting('velocitycash_settings', 'velocitycash_n8n_webhook');
    register_setting('velocitycash_settings', 'velocitycash_mailchimp_key');
    register_setting('velocitycash_settings', 'velocitycash_facebook_pixel');
    register_setting('velocitycash_settings', 'velocitycash_ga4_id');
    
    add_settings_section(
        'velocitycash_integration_section',
        'Integraciones',
        null,
        'velocitycash-settings'
    );
    
    add_settings_field(
        'velocitycash_n8n_webhook',
        'n8n Webhook Base URL',
        'velocitycash_n8n_webhook_field',
        'velocitycash-settings',
        'velocitycash_integration_section'
    );
    
    add_settings_field(
        'velocitycash_mailchimp_key',
        'Mailchimp API Key',
        'velocitycash_mailchimp_key_field',
        'velocitycash-settings',
        'velocitycash_integration_section'
    );
    
    add_settings_field(
        'velocitycash_facebook_pixel',
        'Facebook Pixel ID',
        'velocitycash_facebook_pixel_field',
        'velocitycash-settings',
        'velocitycash_integration_section'
    );
    
    add_settings_field(
        'velocitycash_ga4_id',
        'Google Analytics 4 ID',
        'velocitycash_ga4_id_field',
        'velocitycash-settings',
        'velocitycash_integration_section'
    );
}
add_action('admin_init', 'velocitycash_register_settings');

function velocitycash_n8n_webhook_field() {
    $value = get_option('velocitycash_n8n_webhook', 'https://jcm156.app.n8n.cloud/webhook/');
    echo '<input type="text" name="velocitycash_n8n_webhook" value="' . esc_attr($value) . '" class="regular-text" />';
}

function velocitycash_mailchimp_key_field() {
    $value = get_option('velocitycash_mailchimp_key', '');
    echo '<input type="text" name="velocitycash_mailchimp_key" value="' . esc_attr($value) . '" class="regular-text" />';
}

function velocitycash_facebook_pixel_field() {
    $value = get_option('velocitycash_facebook_pixel', '');
    echo '<input type="text" name="velocitycash_facebook_pixel" value="' . esc_attr($value) . '" class="regular-text" />';
}

function velocitycash_ga4_id_field() {
    $value = get_option('velocitycash_ga4_id', '');
    echo '<input type="text" name="velocitycash_ga4_id" value="' . esc_attr($value) . '" class="regular-text" />';
}
