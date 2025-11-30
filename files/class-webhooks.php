<?php
/**
 * VelocityCash Webhooks Class
 * Maneja todas las integraciones con n8n y otros servicios externos
 */

class VelocityCash_Webhooks {
    
    private $webhook_base_url;
    
    public function __construct() {
        $this->webhook_base_url = get_option('velocitycash_n8n_webhook', 'https://jcm156.app.n8n.cloud/webhook/');
        
        // Hook para enviar datos cuando hay eventos importantes
        add_action('woocommerce_new_order', array($this, 'send_new_order_webhook'), 10, 1);
        add_action('woocommerce_order_status_completed', array($this, 'send_order_completed_webhook'), 10, 1);
        add_action('user_register', array($this, 'send_user_registration_webhook'), 10, 1);
        add_action('comment_post', array($this, 'send_new_comment_webhook'), 10, 2);
        
        // AJAX handlers
        add_action('wp_ajax_velocitycash_test_webhook', array($this, 'test_webhook'));
        add_action('wp_ajax_velocitycash_send_cart_abandonment', array($this, 'send_cart_abandonment'));
        add_action('wp_ajax_nopriv_velocitycash_send_cart_abandonment', array($this, 'send_cart_abandonment'));
    }
    
    /**
     * Función genérica para enviar datos a n8n
     */
    private function send_to_n8n($endpoint, $data) {
        $url = trailingslashit($this->webhook_base_url) . $endpoint;
        
        $args = array(
            'body' => json_encode($data),
            'headers' => array(
                'Content-Type' => 'application/json',
            ),
            'timeout' => 15,
            'method' => 'POST'
        );
        
        $response = wp_remote_post($url, $args);
        
        if (is_wp_error($response)) {
            error_log('VelocityCash Webhook Error: ' . $response->get_error_message());
            return false;
        }
        
        return true;
    }
    
    /**
     * Webhook para nuevas órdenes
     */
    public function send_new_order_webhook($order_id) {
        $order = wc_get_order($order_id);
        
        if (!$order) return;
        
        $items = array();
        foreach ($order->get_items() as $item) {
            $product = $item->get_product();
            $items[] = array(
                'name' => $product->get_name(),
                'quantity' => $item->get_quantity(),
                'price' => $product->get_price(),
                'total' => $item->get_total()
            );
        }
        
        $data = array(
            'event' => 'new_order',
            'order_id' => $order_id,
            'order_number' => $order->get_order_number(),
            'status' => $order->get_status(),
            'customer' => array(
                'email' => $order->get_billing_email(),
                'first_name' => $order->get_billing_first_name(),
                'last_name' => $order->get_billing_last_name(),
                'phone' => $order->get_billing_phone(),
                'address' => array(
                    'street' => $order->get_billing_address_1(),
                    'city' => $order->get_billing_city(),
                    'country' => $order->get_billing_country(),
                    'postcode' => $order->get_billing_postcode()
                )
            ),
            'items' => $items,
            'totals' => array(
                'subtotal' => $order->get_subtotal(),
                'tax' => $order->get_total_tax(),
                'shipping' => $order->get_shipping_total(),
                'total' => $order->get_total()
            ),
            'currency' => $order->get_currency(),
            'payment_method' => $order->get_payment_method_title(),
            'timestamp' => current_time('mysql')
        );
        
        $this->send_to_n8n('new-order', $data);
        
        // También enviar a Mailchimp si está configurado
        $this->add_to_mailchimp_list($order->get_billing_email(), array(
            'FNAME' => $order->get_billing_first_name(),
            'LNAME' => $order->get_billing_last_name(),
            'PHONE' => $order->get_billing_phone()
        ), 'customers');
    }
    
    /**
     * Webhook para órdenes completadas
     */
    public function send_order_completed_webhook($order_id) {
        $order = wc_get_order($order_id);
        
        if (!$order) return;
        
        $data = array(
            'event' => 'order_completed',
            'order_id' => $order_id,
            'customer_email' => $order->get_billing_email(),
            'customer_name' => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
            'total' => $order->get_total(),
            'currency' => $order->get_currency(),
            'timestamp' => current_time('mysql')
        );
        
        $this->send_to_n8n('order-completed', $data);
    }
    
    /**
     * Webhook para nuevos registros de usuario
     */
    public function send_user_registration_webhook($user_id) {
        $user = get_userdata($user_id);
        
        if (!$user) return;
        
        $data = array(
            'event' => 'user_registration',
            'user_id' => $user_id,
            'username' => $user->user_login,
            'email' => $user->user_email,
            'display_name' => $user->display_name,
            'timestamp' => current_time('mysql')
        );
        
        $this->send_to_n8n('user-registration', $data);
        
        // Añadir a lista de Mailchimp de "nuevos usuarios"
        $this->add_to_mailchimp_list($user->user_email, array(
            'FNAME' => $user->first_name,
            'LNAME' => $user->last_name
        ), 'new-users');
    }
    
    /**
     * Webhook para nuevos comentarios (engagement tracking)
     */
    public function send_new_comment_webhook($comment_id, $comment_approved) {
        if ($comment_approved === 1 || $comment_approved === 'approved') {
            $comment = get_comment($comment_id);
            
            $data = array(
                'event' => 'new_comment',
                'comment_id' => $comment_id,
                'post_id' => $comment->comment_post_ID,
                'post_title' => get_the_title($comment->comment_post_ID),
                'author_name' => $comment->comment_author,
                'author_email' => $comment->comment_author_email,
                'comment_content' => $comment->comment_content,
                'timestamp' => current_time('mysql')
            );
            
            $this->send_to_n8n('new-comment', $data);
        }
    }
    
    /**
     * Carrito abandonado
     */
    public function send_cart_abandonment() {
        check_ajax_referer('velocitycash_nonce', 'nonce');
        
        if (!isset($_COOKIE['velocitycash_cart'])) {
            wp_send_json_error('No cart data');
        }
        
        $cart_data = json_decode(stripslashes($_COOKIE['velocitycash_cart']), true);
        
        $email = sanitize_email($_POST['email']);
        $name = sanitize_text_field($_POST['name']);
        
        $data = array(
            'event' => 'cart_abandonment',
            'email' => $email,
            'name' => $name,
            'cart_items' => $cart_data['items'],
            'cart_total' => $cart_data['total'],
            'abandon_timestamp' => current_time('mysql'),
            'recovery_url' => home_url('/checkout/')
        );
        
        $this->send_to_n8n('cart-abandonment', $data);
        
        wp_send_json_success('Cart abandonment tracked');
    }
    
    /**
     * Test webhook desde admin
     */
    public function test_webhook() {
        check_ajax_referer('velocitycash_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }
        
        $data = array(
            'event' => 'test',
            'message' => 'Test webhook from VelocityCash AI',
            'timestamp' => current_time('mysql'),
            'site_url' => get_site_url()
        );
        
        $result = $this->send_to_n8n('test', $data);
        
        if ($result) {
            wp_send_json_success('Webhook sent successfully');
        } else {
            wp_send_json_error('Failed to send webhook');
        }
    }
    
    /**
     * Añadir contacto a lista de Mailchimp
     */
    private function add_to_mailchimp_list($email, $merge_fields = array(), $list_tag = 'general') {
        $api_key = get_option('velocitycash_mailchimp_key', '');
        
        if (empty($api_key)) {
            return false;
        }
        
        // Extraer datacenter del API key
        list($key, $datacenter) = explode('-', $api_key);
        
        // Esta es una implementación simplificada
        // En producción, usar la librería oficial de Mailchimp
        $url = "https://{$datacenter}.api.mailchimp.com/3.0/lists/YOUR_LIST_ID/members";
        
        $data = array(
            'email_address' => $email,
            'status' => 'subscribed',
            'merge_fields' => $merge_fields,
            'tags' => array($list_tag)
        );
        
        $args = array(
            'body' => json_encode($data),
            'headers' => array(
                'Authorization' => 'Basic ' . base64_encode('user:' . $api_key),
                'Content-Type' => 'application/json',
            ),
            'method' => 'POST'
        );
        
        wp_remote_post($url, $args);
        
        return true;
    }
    
    /**
     * Enviar lead magnet download a n8n
     */
    public function send_lead_magnet_download($email, $magnet_id, $source_url) {
        $data = array(
            'event' => 'lead_magnet_download',
            'email' => $email,
            'magnet_id' => $magnet_id,
            'source_url' => $source_url,
            'timestamp' => current_time('mysql')
        );
        
        $this->send_to_n8n('lead-magnet', $data);
        
        // Añadir a lista de leads en Mailchimp
        $this->add_to_mailchimp_list($email, array(), 'leads');
    }
}
