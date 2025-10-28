<?php
/**
 * REST API endpoints for Nova Multi-Stripe Checkout
 *
 * @package NovaMSC
 */

namespace NovaMSC;

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * REST API class
 */
class REST {

    /**
     * Constructor
     */
    public function __construct() {
        add_action('rest_api_init', array($this, 'register_routes'));
    }

    /**
     * Register REST routes
     */
    public function register_routes() {
        register_rest_route('nova/v1', '/checkout', array(
            'methods' => 'POST',
            'callback' => array($this, 'create_checkout_session'),
            'permission_callback' => '__return_true',
            'args' => array(
                'country' => array(
                    'required' => true,
                    'type' => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                'plan' => array(
                    'required' => true,
                    'type' => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                'support' => array(
                    'required' => true,
                    'type' => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                'billing' => array(
                    'required' => true,
                    'type' => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                'users' => array(
                    'required' => true,
                    'type' => 'integer',
                    'sanitize_callback' => 'absint',
                ),
            ),
        ));
    }

    /**
     * Create Stripe checkout session
     *
     * @param WP_REST_Request $request REST request object
     * @return WP_REST_Response|WP_Error
     */
    public function create_checkout_session($request) {
        // Validate input parameters
        $country = nova_msc_validate_country($request->get_param('country'));
        if (!$country) {
            return nova_msc_rest_error('Invalid country. Must be AU or NZ.', 400);
        }

        $plan = nova_msc_validate_plan($request->get_param('plan'));
        if (!$plan) {
            return nova_msc_rest_error('Invalid plan. Must be plus, pro, or ultimate.', 400);
        }

        $support = nova_msc_validate_support($request->get_param('support'));
        if (!$support) {
            return nova_msc_rest_error('Invalid support level. Must be basic, plus, or ultimate.', 400);
        }

        $billing = nova_msc_validate_billing($request->get_param('billing'));
        if (!$billing) {
            return nova_msc_rest_error('Invalid billing period. Must be quarterly or annual.', 400);
        }

        $users = nova_msc_validate_users($request->get_param('users'));
        if (!$users) {
            return nova_msc_rest_error('Invalid user count. Must be 1 or greater.', 400);
        }

        // Get Stripe secret key
        $secret_key = nova_msc_secret($country);
        if (!$secret_key) {
            return nova_msc_rest_error('Stripe secret key not configured for ' . $country, 500);
        }

        // Validate price IDs exist
        if (!Prices::validate_price_ids($country, $plan, $support, $billing)) {
            return nova_msc_rest_error('Price configuration not found for the selected options.', 500);
        }

        // Get price IDs
        $price_ids = Prices::get_price_ids($country, $plan, $support, $billing);

        // Get success and cancel URLs
        $success_url = nova_msc_opt_get('success_url_' . strtolower($country), home_url('/success/'));
        $cancel_url = nova_msc_opt_get('cancel_url_' . strtolower($country), home_url('/cancel/'));

        try {
            // Initialize Stripe
            \Stripe\Stripe::setApiKey($secret_key);

            // Create checkout session
            $session = \Stripe\Checkout\Session::create(array(
                'mode' => 'subscription',
                'payment_method_types' => array('card'),
                'line_items' => array(
                    array(
                        'price' => $price_ids['plan'],
                        'quantity' => $users,
                    ),
                    array(
                        'price' => $price_ids['support'],
                        'quantity' => $users,
                    ),
                ),
                'success_url' => $success_url . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => $cancel_url,
                'metadata' => array(
                    'country' => $country,
                    'plan' => $plan,
                    'support' => $support,
                    'billing' => $billing,
                    'users' => $users,
                ),
            ));

            return nova_msc_rest_ok(array(
                'url' => $session->url,
                'session_id' => $session->id,
            ));

        } catch (\Stripe\Exception\CardException $e) {
            return nova_msc_rest_error('Card error: ' . $e->getMessage(), 400);
        } catch (\Stripe\Exception\RateLimitException $e) {
            return nova_msc_rest_error('Rate limit exceeded. Please try again later.', 429);
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return nova_msc_rest_error('Invalid request: ' . $e->getMessage(), 400);
        } catch (\Stripe\Exception\AuthenticationException $e) {
            return nova_msc_rest_error('Authentication failed. Please check your API keys.', 401);
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            return nova_msc_rest_error('Network error. Please try again later.', 500);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return nova_msc_rest_error('Stripe API error: ' . $e->getMessage(), 500);
        } catch (Exception $e) {
            return nova_msc_rest_error('Unexpected error: ' . $e->getMessage(), 500);
        }
    }
}
