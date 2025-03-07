<?php
/**
 * Plugin Name: Customer Reviews API
 * Plugin URI: https://github.com/your-repo/customer-reviews-api
 * Description: A WordPress plugin to fetch and submit customer reviews via an external API with reCAPTCHA protection.
 * Version: 1.0.0
 * Author: Ibrahim khalil (WP Expert)
 * License: MIT
 * Text Domain: customer-reviews-api
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Customer_Reviews_API {
    /** @var string API URL */
    private $api_url;

    /** @var string Option name for storing API settings */
    private $option_name = 'customer_reviews_api_settings';

    /**
     * Class constructor.
     */
    public function __construct() {
        $this->api_url = apply_filters('customer_reviews_api_url', 'https://api-test.blubirdinteractive.org/api/reviews');
        add_action('admin_menu', [$this, 'create_admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_shortcode('customer_reviews', [$this, 'display_reviews_shortcode']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('wp_ajax_submit_review', [$this, 'submit_review']);
        add_action('wp_ajax_nopriv_submit_review', [$this, 'submit_review']);
    }

    /**
     * Adds the settings page to the admin menu.
     */
    public function create_admin_menu() {
        add_options_page(__('Customer Reviews API', 'customer-reviews-api'), __('Customer Reviews', 'customer-reviews-api'), 'manage_options', 'customer-reviews-api', [$this, 'settings_page']);
    }

    /**
     * Registers plugin settings.
     */
    public function register_settings() {
        register_setting('customer_reviews_api_group', $this->option_name);
    }

    /**
     * Displays the settings page.
     */
    public function settings_page() {
        $options = get_option($this->option_name, []);
        ?>
        <div class="wrap">
            <h2><?php _e('Customer Reviews API Settings', 'customer-reviews-api'); ?></h2>
            <form method="post" action="options.php">
                <?php settings_fields('customer_reviews_api_group'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="api_key"><?php _e('API Key', 'customer-reviews-api'); ?></label></th>
                        <td><input type="text" name="<?php echo $this->option_name; ?>[api_key]" value="<?php echo esc_attr($options['api_key'] ?? ''); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="recaptcha_site_key"><?php _e('reCAPTCHA Site Key', 'customer-reviews-api'); ?></label></th>
                        <td><input type="text" name="<?php echo $this->option_name; ?>[recaptcha_site_key]" value="<?php echo esc_attr($options['recaptcha_site_key'] ?? ''); ?>" class="regular-text"></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Enqueues frontend scripts and styles.
     */
    public function enqueue_scripts() {
        wp_enqueue_style('cra-bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css', [], null);
        wp_enqueue_style('cra-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css', [], null);
        wp_enqueue_style('cra-swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', [], null);
        wp_enqueue_style('cra-customer-reviews-css', plugin_dir_url(__FILE__) . 'assets/css/style.css', [], null);
        wp_enqueue_script('jquery');
        wp_enqueue_script('cra-bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', ['jquery'], null, true);
        wp_enqueue_script('cra-swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', ['jquery'], null, true);
        wp_enqueue_script('customer-reviews-js', plugin_dir_url(__FILE__) . 'assets/js/script.js', ['jquery'], null, true);
        wp_localize_script('customer-reviews-js', 'reviews_api', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('submit_review_nonce'),
            'recaptcha_site_key' => get_option($this->option_name)['recaptcha_site_key'] ?? ''
        ]);
    }

    /**
     * Shortcode handler for displaying reviews.
     *
     * @return string Rendered reviews template.
     */
    public function display_reviews_shortcode() {
        $reviews = $this->fetch_reviews();
        ob_start();
        include plugin_dir_path(__FILE__) . 'templates/review-template.php';
        return ob_get_clean();
    }

    /**
     * Fetches customer reviews from the external API.
     *
     * @return array List of reviews.
     */
    private function fetch_reviews() {
        $options = get_option($this->option_name, []);
        $api_key = $options['api_key'] ?? '';

        $response = wp_remote_get($this->api_url, [
            'headers' => [
                'X-API-Key' => $api_key,
                'Content-Type' => 'application/json'
            ]
        ]);

        if (is_wp_error($response)) {
            return [];
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);
        return apply_filters('customer_reviews_data', $body['reviews'] ?? []);
    }


    /**
     * Handles the review submission via AJAX.
     */
    public function submit_review()
    {
        // Verify nonce for security
        if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'submit_review_nonce')) {
            wp_send_json_error(['message' => __('Security check failed. Please refresh and try again.', 'customer-reviews-api')], 403);
        }

        // Get API Key
        $options = get_option($this->option_name, []);
        $api_key = $options['api_key'] ?? '';

        // Validate required fields
        if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['rating']) || empty($_POST['review'])) {
            wp_send_json_error(['message' => __('All fields are required.', 'customer-reviews-api')], 400);
        }

        // Prepare data for API submission
        $data = [
            'name' => sanitize_text_field($_POST['name']),
            'email' => sanitize_email($_POST['email']),
            'rating' => intval($_POST['rating']),
            'title' => sanitize_text_field($_POST['title']),
            'review' => sanitize_textarea_field($_POST['review']),
            'g-recaptcha-response' => sanitize_text_field($_POST['g-recaptcha-response'] ?? '')
        ];

        // Send request to API
        $response = wp_remote_post($this->api_url, [
            'headers' => [
                'X-API-Key' => $api_key,
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode($data),
            'timeout' => 15
        ]);

        // Check for WP_Error
        if (is_wp_error($response)) {
            wp_send_json_error(['message' => __('Error submitting review.', 'customer-reviews-api')], 500);
        }

        // Decode API response
        $response_body = wp_remote_retrieve_body($response);
        $decoded_response = json_decode($response_body, true);

        // Check if API response is valid
        if (!$decoded_response || !isset($decoded_response['message'])) {
            wp_send_json_error(['message' => __('Invalid API response. Please try again.', 'customer-reviews-api')], 500);
        }

        // Return API message to frontend
        wp_send_json_success(['message' => $decoded_response['message']]);
    }

}

// Initialize the plugin
new Customer_Reviews_API();
