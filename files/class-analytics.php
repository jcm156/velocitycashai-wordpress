<?php
/**
 * VelocityCash Analytics Class
 * Track conversions, page views, and user behavior
 */

class VelocityCash_Analytics {
    
    public function __construct() {
        // Track page views
        add_action('wp_head', array($this, 'track_page_view'));
        
        // Track add to cart
        add_action('woocommerce_add_to_cart', array($this, 'track_add_to_cart'), 10, 6);
        
        // Track product views
        add_action('woocommerce_after_single_product', array($this, 'track_product_view'));
        
        // Track checkout steps
        add_action('woocommerce_before_checkout_form', array($this, 'track_checkout_start'));
        
        // Add tracking scripts to footer
        add_action('wp_footer', array($this, 'add_tracking_scripts'));
    }
    
    /**
     * Track page view in database
     */
    public function track_page_view() {
        if (is_admin() || !get_option('velocitycash_tracking_enabled', 'yes') === 'yes') {
            return;
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'velocitycash_conversions';
        
        $wpdb->insert(
            $table_name,
            array(
                'user_id' => get_current_user_id(),
                'event_type' => 'page_view',
                'event_data' => json_encode(array(
                    'url' => $_SERVER['REQUEST_URI'],
                    'referrer' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
                    'user_agent' => $_SERVER['HTTP_USER_AGENT']
                )),
                'source' => $this->get_traffic_source()
            )
        );
    }
    
    /**
     * Track add to cart event
     */
    public function track_add_to_cart($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'velocitycash_conversions';
        
        $product = wc_get_product($product_id);
        
        $wpdb->insert(
            $table_name,
            array(
                'user_id' => get_current_user_id(),
                'event_type' => 'add_to_cart',
                'event_data' => json_encode(array(
                    'product_id' => $product_id,
                    'product_name' => $product->get_name(),
                    'price' => $product->get_price(),
                    'quantity' => $quantity
                )),
                'source' => $this->get_traffic_source()
            )
        );
        
        // Fire Google Analytics event
        if (get_option('velocitycash_ga4_id')) {
            ?>
            <script>
            if (typeof gtag !== 'undefined') {
                gtag('event', 'add_to_cart', {
                    'items': [{
                        'item_id': '<?php echo $product_id; ?>',
                        'item_name': '<?php echo esc_js($product->get_name()); ?>',
                        'price': <?php echo $product->get_price(); ?>,
                        'quantity': <?php echo $quantity; ?>
                    }]
                });
            }
            </script>
            <?php
        }
    }
    
    /**
     * Track product view
     */
    public function track_product_view() {
        global $product, $wpdb;
        $table_name = $wpdb->prefix . 'velocitycash_conversions';
        
        if (!$product) return;
        
        $wpdb->insert(
            $table_name,
            array(
                'user_id' => get_current_user_id(),
                'event_type' => 'product_view',
                'event_data' => json_encode(array(
                    'product_id' => $product->get_id(),
                    'product_name' => $product->get_name(),
                    'price' => $product->get_price()
                )),
                'source' => $this->get_traffic_source()
            )
        );
    }
    
    /**
     * Track checkout start
     */
    public function track_checkout_start() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'velocitycash_conversions';
        
        $cart_total = WC()->cart->get_cart_contents_total();
        
        $wpdb->insert(
            $table_name,
            array(
                'user_id' => get_current_user_id(),
                'event_type' => 'checkout_start',
                'event_data' => json_encode(array(
                    'cart_total' => $cart_total,
                    'items_count' => WC()->cart->get_cart_contents_count()
                )),
                'source' => $this->get_traffic_source()
            )
        );
    }
    
    /**
     * Get traffic source
     */
    private function get_traffic_source() {
        if (isset($_GET['utm_source'])) {
            return sanitize_text_field($_GET['utm_source']);
        }
        
        if (isset($_SERVER['HTTP_REFERER'])) {
            $referrer = $_SERVER['HTTP_REFERER'];
            
            if (strpos($referrer, 'google.com') !== false) return 'google';
            if (strpos($referrer, 'facebook.com') !== false) return 'facebook';
            if (strpos($referrer, 'instagram.com') !== false) return 'instagram';
            if (strpos($referrer, 'twitter.com') !== false) return 'twitter';
            if (strpos($referrer, 'linkedin.com') !== false) return 'linkedin';
            
            return 'referral';
        }
        
        return 'direct';
    }
    
    /**
     * Add tracking scripts to footer
     */
    public function add_tracking_scripts() {
        // Google Analytics 4
        $ga4_id = get_option('velocitycash_ga4_id');
        if ($ga4_id) {
            ?>
            <!-- Google Analytics 4 -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr($ga4_id); ?>"></script>
            <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '<?php echo esc_js($ga4_id); ?>', {
                'send_page_view': true,
                'custom_map': {
                    'dimension1': 'traffic_source',
                    'dimension2': 'user_type'
                }
            });
            </script>
            <?php
        }
        
        // Facebook Pixel
        $fb_pixel = get_option('velocitycash_facebook_pixel');
        if ($fb_pixel) {
            ?>
            <!-- Facebook Pixel -->
            <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '<?php echo esc_js($fb_pixel); ?>');
            fbq('track', 'PageView');
            </script>
            <noscript>
                <img height="1" width="1" style="display:none"
                src="https://www.facebook.com/tr?id=<?php echo esc_attr($fb_pixel); ?>&ev=PageView&noscript=1"/>
            </noscript>
            <?php
        }
        
        // Custom conversion tracking for specific pages
        if (is_product()) {
            global $product;
            ?>
            <script>
            // Track product page view
            if (typeof fbq !== 'undefined') {
                fbq('track', 'ViewContent', {
                    content_ids: ['<?php echo $product->get_id(); ?>'],
                    content_name: '<?php echo esc_js($product->get_name()); ?>',
                    content_type: 'product',
                    value: <?php echo $product->get_price(); ?>,
                    currency: '<?php echo get_woocommerce_currency(); ?>'
                });
            }
            
            if (typeof gtag !== 'undefined') {
                gtag('event', 'view_item', {
                    'items': [{
                        'item_id': '<?php echo $product->get_id(); ?>',
                        'item_name': '<?php echo esc_js($product->get_name()); ?>',
                        'price': <?php echo $product->get_price(); ?>
                    }]
                });
            }
            </script>
            <?php
        }
        
        if (is_checkout()) {
            ?>
            <script>
            // Track checkout initiation
            if (typeof fbq !== 'undefined') {
                fbq('track', 'InitiateCheckout');
            }
            
            if (typeof gtag !== 'undefined') {
                gtag('event', 'begin_checkout');
            }
            </script>
            <?php
        }
    }
    
    /**
     * Get conversion funnel stats
     */
    public static function get_funnel_stats($days = 30) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'velocitycash_conversions';
        
        $date_from = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        
        $stats = array(
            'page_views' => $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM $table_name WHERE event_type = 'page_view' AND timestamp >= %s",
                $date_from
            )),
            'product_views' => $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM $table_name WHERE event_type = 'product_view' AND timestamp >= %s",
                $date_from
            )),
            'add_to_cart' => $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM $table_name WHERE event_type = 'add_to_cart' AND timestamp >= %s",
                $date_from
            )),
            'checkout_start' => $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM $table_name WHERE event_type = 'checkout_start' AND timestamp >= %s",
                $date_from
            ))
        );
        
        // Calculate conversion rates
        if ($stats['page_views'] > 0) {
            $stats['product_view_rate'] = round(($stats['product_views'] / $stats['page_views']) * 100, 2);
            $stats['add_to_cart_rate'] = round(($stats['add_to_cart'] / $stats['page_views']) * 100, 2);
            $stats['checkout_rate'] = round(($stats['checkout_start'] / $stats['page_views']) * 100, 2);
        }
        
        return $stats;
    }
}
