<?php
/**
 * VelocityCash Conversions Class
 * Handle conversion optimization, A/B testing, and personalization
 */

class VelocityCash_Conversions {
    
    public function __construct() {
        // A/B Testing hooks
        add_action('wp_head', array($this, 'run_ab_tests'));
        add_action('wp_ajax_velocitycash_track_conversion', array($this, 'track_conversion'));
        add_action('wp_ajax_nopriv_velocitycash_track_conversion', array($this, 'track_conversion'));
        
        // Exit intent popup
        add_action('wp_footer', array($this, 'add_exit_intent_popup'));
        
        // Dynamic pricing
        add_filter('woocommerce_product_get_price', array($this, 'dynamic_pricing'), 10, 2);
    }
    
    /**
     * Run A/B tests on page load
     */
    public function run_ab_tests() {
        if (!get_option('velocitycash_ab_testing_enabled', 'yes') === 'yes') {
            return;
        }
        
        // Example A/B test: Hero headline variations
        $test_name = 'hero_headline_test';
        $variants = array(
            'control' => 'Descubre Cómo Generar $10,000+ al Mes con IA',
            'variant_a' => 'Genera $10K+ al Mes con IA Sin Experiencia Técnica',
            'variant_b' => 'Sistema Probado Para Ganar $10,000/Mes Usando IA'
        );
        
        $assigned_variant = $this->assign_variant($test_name, $variants);
        
        // Store in session for later tracking
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['ab_tests'][$test_name] = $assigned_variant;
        
        // Output JavaScript to apply variant
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var heroHeadline = document.querySelector('.hero-headline');
            if (heroHeadline) {
                heroHeadline.textContent = '<?php echo esc_js($variants[$assigned_variant]); ?>';
            }
        });
        </script>
        <?php
    }
    
    /**
     * Assign user to A/B test variant
     */
    private function assign_variant($test_name, $variants) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'velocitycash_ab_tests';
        
        // Check if user already has assigned variant
        $user_id = get_current_user_id();
        $user_ip = $_SERVER['REMOTE_ADDR'];
        
        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT variant FROM $table_name 
             WHERE test_name = %s AND (user_id = %d OR user_id = 0)
             ORDER BY timestamp DESC LIMIT 1",
            $test_name,
            $user_id
        ));
        
        if ($existing) {
            return $existing;
        }
        
        // Assign random variant
        $variant_keys = array_keys($variants);
        $assigned_variant = $variant_keys[array_rand($variant_keys)];
        
        // Save to database
        $wpdb->insert(
            $table_name,
            array(
                'test_name' => $test_name,
                'variant' => $assigned_variant,
                'user_id' => $user_id,
                'converted' => 0
            )
        );
        
        return $assigned_variant;
    }
    
    /**
     * Track conversion for A/B test
     */
    public function track_conversion() {
        check_ajax_referer('velocitycash_nonce', 'nonce');
        
        if (!isset($_SESSION)) {
            session_start();
        }
        
        $test_name = sanitize_text_field($_POST['test_name']);
        $variant = isset($_SESSION['ab_tests'][$test_name]) ? $_SESSION['ab_tests'][$test_name] : 'control';
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'velocitycash_ab_tests';
        
        // Update conversion
        $wpdb->update(
            $table_name,
            array('converted' => 1),
            array(
                'test_name' => $test_name,
                'variant' => $variant,
                'user_id' => get_current_user_id()
            )
        );
        
        wp_send_json_success();
    }
    
    /**
     * Get A/B test results
     */
    public static function get_ab_test_results($test_name) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'velocitycash_ab_tests';
        
        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT variant, 
                    COUNT(*) as total_views,
                    SUM(converted) as conversions,
                    (SUM(converted) / COUNT(*)) * 100 as conversion_rate
             FROM $table_name
             WHERE test_name = %s
             GROUP BY variant",
            $test_name
        ));
        
        return $results;
    }
    
    /**
     * Add exit intent popup
     */
    public function add_exit_intent_popup() {
        // Only on product pages and not for users who already purchased
        if (!is_product() || $this->user_has_purchased()) {
            return;
        }
        
        // CSS for exit intent popup is in custom.js
        // This just outputs the modal structure if needed
    }
    
    /**
     * Check if user has made a purchase
     */
    private function user_has_purchased() {
        if (!is_user_logged_in()) {
            return false;
        }
        
        $customer = new WC_Customer(get_current_user_id());
        return $customer->get_order_count() > 0;
    }
    
    /**
     * Dynamic pricing based on user behavior
     */
    public function dynamic_pricing($price, $product) {
        // Example: Show lower price for returning visitors
        if (!isset($_COOKIE['velocitycash_returning'])) {
            // First visit - set cookie
            setcookie('velocitycash_returning', '1', time() + (86400 * 30), '/');
            return $price;
        }
        
        // Returning visitor - could show discount
        // For now, just return normal price
        // You can implement dynamic pricing logic here
        
        return $price;
    }
    
    /**
     * Personalize content based on user data
     */
    public static function get_personalized_content($default_content, $user_data = array()) {
        // Example personalization
        if (!is_user_logged_in()) {
            return $default_content;
        }
        
        $user = wp_get_current_user();
        
        // Replace placeholders
        $personalized = str_replace('{first_name}', $user->first_name, $default_content);
        $personalized = str_replace('{last_name}', $user->last_name, $personalized);
        
        return $personalized;
    }
    
    /**
     * Calculate conversion rate
     */
    public static function calculate_conversion_rate($period = 30) {
        global $wpdb;
        
        $date_from = date('Y-m-d H:i:s', strtotime("-{$period} days"));
        
        // Get visitors
        $conversions_table = $wpdb->prefix . 'velocitycash_conversions';
        $visitors = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(DISTINCT user_id) FROM $conversions_table 
             WHERE event_type = 'page_view' AND timestamp >= %s",
            $date_from
        ));
        
        // Get purchasers
        $orders = wc_get_orders(array(
            'limit' => -1,
            'date_created' => '>=' . strtotime($date_from),
            'status' => array('wc-completed', 'wc-processing')
        ));
        
        $purchasers = count(array_unique(array_map(function($order) {
            return $order->get_customer_id();
        }, $orders)));
        
        if ($visitors == 0) {
            return 0;
        }
        
        return round(($purchasers / $visitors) * 100, 2);
    }
    
    /**
     * Get top converting products
     */
    public static function get_top_converting_products($limit = 5) {
        global $wpdb;
        
        $orders = wc_get_orders(array(
            'limit' => -1,
            'status' => array('wc-completed', 'wc-processing'),
            'date_created' => '>=' . strtotime('-30 days')
        ));
        
        $product_sales = array();
        
        foreach ($orders as $order) {
            foreach ($order->get_items() as $item) {
                $product_id = $item->get_product_id();
                
                if (!isset($product_sales[$product_id])) {
                    $product_sales[$product_id] = array(
                        'name' => $item->get_name(),
                        'quantity' => 0,
                        'revenue' => 0
                    );
                }
                
                $product_sales[$product_id]['quantity'] += $item->get_quantity();
                $product_sales[$product_id]['revenue'] += $item->get_total();
            }
        }
        
        // Sort by revenue
        uasort($product_sales, function($a, $b) {
            return $b['revenue'] - $a['revenue'];
        });
        
        return array_slice($product_sales, 0, $limit, true);
    }
    
    /**
     * Upsell suggestion engine
     */
    public static function get_upsell_suggestions($product_id) {
        // Get products frequently bought together
        global $wpdb;
        
        // This is a simplified version
        // In production, you'd use more sophisticated algorithms
        
        $product = wc_get_product($product_id);
        $category_ids = $product->get_category_ids();
        
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 3,
            'post__not_in' => array($product_id),
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $category_ids
                )
            ),
            'meta_query' => array(
                array(
                    'key' => '_price',
                    'value' => $product->get_price(),
                    'compare' => '>',
                    'type' => 'NUMERIC'
                )
            )
        );
        
        $upsells = new WP_Query($args);
        
        return $upsells->posts;
    }
}
