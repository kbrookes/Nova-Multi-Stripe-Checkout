<?php
/**
 * Webhook handling for Nova Multi-Stripe Checkout
 *
 * @package NovaMSC
 */

namespace NovaMSC;

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Webhooks class
 */
class Webhooks {

    /**
     * Constructor
     */
    public function __construct() {
        add_action('rest_api_init', array($this, 'register_routes'));
    }

    /**
     * Register webhook routes
     */
    public function register_routes() {
        register_rest_route('nova/v1', '/stripe-webhook', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_webhook'),
            'permission_callback' => '__return_true',
        ));
    }

    /**
     * Handle Stripe webhook
     *
     * @param WP_REST_Request $request REST request object
     * @return WP_REST_Response|WP_Error
     */
    public function handle_webhook($request) {
        $payload = $request->get_body();
        $sig_header = $request->get_header('stripe-signature');

        if (!$sig_header) {
            return nova_msc_rest_error('Missing Stripe signature header', 400);
        }

        // Get webhook secret from environment
        $webhook_secret = getenv('STRIPE_WEBHOOK_SECRET');
        if (!$webhook_secret) {
            return nova_msc_rest_error('Webhook secret not configured', 500);
        }

        try {
            // Verify webhook signature
            $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $webhook_secret);
        } catch (\UnexpectedValueException $e) {
            return nova_msc_rest_error('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return nova_msc_rest_error('Invalid signature', 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $this->handle_checkout_session_completed($event->data->object);
                break;

            case 'invoice.payment_succeeded':
                $this->handle_invoice_payment_succeeded($event->data->object);
                break;

            case 'customer.subscription.updated':
                $this->handle_subscription_updated($event->data->object);
                break;

            case 'customer.subscription.deleted':
                $this->handle_subscription_deleted($event->data->object);
                break;

            default:
                // Log unhandled event types
                error_log('Nova MSC: Unhandled webhook event type: ' . $event->type);
        }

        return nova_msc_rest_ok(array('received' => true));
    }

    /**
     * Handle checkout session completed
     *
     * @param object $session Stripe session object
     */
    private function handle_checkout_session_completed($session) {
        // Log successful checkout
        error_log('Nova MSC: Checkout session completed: ' . $session->id);
        
        // You can add custom logic here, such as:
        // - Sending confirmation emails
        // - Creating user accounts
        // - Updating internal records
        // - Triggering other business processes
    }

    /**
     * Handle invoice payment succeeded
     *
     * @param object $invoice Stripe invoice object
     */
    private function handle_invoice_payment_succeeded($invoice) {
        // Log successful payment
        error_log('Nova MSC: Invoice payment succeeded: ' . $invoice->id);
        
        // You can add custom logic here, such as:
        // - Extending service access
        // - Sending renewal confirmations
        // - Updating billing records
    }

    /**
     * Handle subscription updated
     *
     * @param object $subscription Stripe subscription object
     */
    private function handle_subscription_updated($subscription) {
        // Log subscription update
        error_log('Nova MSC: Subscription updated: ' . $subscription->id);
        
        // You can add custom logic here, such as:
        // - Updating user permissions
        // - Syncing with internal systems
        // - Sending update notifications
    }

    /**
     * Handle subscription deleted
     *
     * @param object $subscription Stripe subscription object
     */
    private function handle_subscription_deleted($subscription) {
        // Log subscription cancellation
        error_log('Nova MSC: Subscription deleted: ' . $subscription->id);
        
        // You can add custom logic here, such as:
        // - Revoking access
        // - Sending cancellation confirmations
        // - Updating internal records
    }

    /**
     * Get webhook endpoint URL
     *
     * @return string Webhook endpoint URL
     */
    public static function get_webhook_url() {
        return rest_url('nova/v1/stripe-webhook');
    }
}
