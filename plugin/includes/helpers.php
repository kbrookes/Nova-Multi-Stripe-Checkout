<?php
/**
 * Helper functions for Nova Multi-Stripe Checkout
 *
 * @package NovaMSC
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get all plugin options
 *
 * @return array Plugin options array
 */
function nova_msc_opts() {
    return get_option(NOVA_MSC_OPT, array());
}

/**
 * Get a single plugin option with default fallback
 *
 * @param string $key     Option key
 * @param mixed  $default Default value if option doesn't exist
 * @return mixed Option value or default
 */
function nova_msc_opt_get($key, $default = null) {
    $options = nova_msc_opts();
    return isset($options[$key]) ? $options[$key] : $default;
}

/**
 * Get Stripe secret key for country (env first, then options)
 *
 * @param string $country Country code (AU or NZ)
 * @return string|false Stripe secret key or false if not found
 */
function nova_msc_secret($country) {
    $country = strtoupper($country);
    
    // Check environment variables first
    $env_key = "STRIPE_{$country}_SK";
    $env_secret = getenv($env_key);
    
    if ($env_secret) {
        return $env_secret;
    }
    
    // Fallback to options
    $option_key = strtolower($country) . '_sk';
    return nova_msc_opt_get($option_key, false);
}

/**
 * Return successful REST response
 *
 * @param mixed $data Response data
 * @param int   $code HTTP status code
 * @return WP_REST_Response
 */
function nova_msc_rest_ok($data, $code = 200) {
    return new WP_REST_Response($data, $code);
}

/**
 * Return error REST response
 *
 * @param string $message Error message
 * @param int    $code    HTTP status code
 * @return WP_Error
 */
function nova_msc_rest_error($message, $code = 400) {
    return new WP_Error('nova_msc_error', $message, array('status' => $code));
}

/**
 * Sanitize and validate country code
 *
 * @param string $country Country code to validate
 * @return string|false Valid country code or false
 */
function nova_msc_validate_country($country) {
    $valid_countries = array('AU', 'NZ');
    $country = strtoupper(sanitize_text_field($country));
    
    return in_array($country, $valid_countries, true) ? $country : false;
}

/**
 * Sanitize and validate plan type
 *
 * @param string $plan Plan type to validate
 * @return string|false Valid plan type or false
 */
function nova_msc_validate_plan($plan) {
    $valid_plans = array('plus', 'pro', 'ultimate');
    $plan = strtolower(sanitize_text_field($plan));
    
    return in_array($plan, $valid_plans, true) ? $plan : false;
}

/**
 * Sanitize and validate support level
 *
 * @param string $support Support level to validate
 * @return string|false Valid support level or false
 */
function nova_msc_validate_support($support) {
    $valid_support = array('basic', 'plus', 'ultimate');
    $support = strtolower(sanitize_text_field($support));
    
    return in_array($support, $valid_support, true) ? $support : false;
}

/**
 * Sanitize and validate billing period
 *
 * @param string $billing Billing period to validate
 * @return string|false Valid billing period or false
 */
function nova_msc_validate_billing($billing) {
    $valid_billing = array('quarterly', 'annual');
    $billing = strtolower(sanitize_text_field($billing));
    
    return in_array($billing, $valid_billing, true) ? $billing : false;
}

/**
 * Sanitize and validate user count
 *
 * @param mixed $users User count to validate
 * @return int|false Valid user count or false
 */
function nova_msc_validate_users($users) {
    $users = intval($users);
    
    return ($users >= 1) ? $users : false;
}
