<?php
/**
 * Price mapping for Nova Multi-Stripe Checkout
 *
 * @package NovaMSC
 */

namespace NovaMSC;

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Prices class
 */
class Prices {

    /**
     * Get price mapping for country
     *
     * @param string $country Country code (AU or NZ)
     * @return array Price mapping array
     */
    public static function map($country) {
        $country = strtoupper($country);
        
        switch ($country) {
            case 'AU':
                return self::get_au_prices();
            case 'NZ':
                return self::get_nz_prices();
            default:
                return array();
        }
    }

    /**
     * Get Australia price mapping
     *
     * @return array Australia price mapping
     */
    private static function get_au_prices() {
        return array(
            'plus' => array(
                'quarterly' => array(
                    'plan' => 'price_au_plus_quarterly_placeholder', // Replace with actual Stripe price ID
                    'support' => array(
                        'basic' => 'price_au_support_basic_quarterly_placeholder',
                        'plus' => 'price_au_support_plus_quarterly_placeholder',
                        'ultimate' => 'price_au_support_ultimate_quarterly_placeholder',
                    ),
                ),
                'annual' => array(
                    'plan' => 'price_au_plus_annual_placeholder', // Replace with actual Stripe price ID
                    'support' => array(
                        'basic' => 'price_au_support_basic_annual_placeholder',
                        'plus' => 'price_au_support_plus_annual_placeholder',
                        'ultimate' => 'price_au_support_ultimate_annual_placeholder',
                    ),
                ),
            ),
            'pro' => array(
                'quarterly' => array(
                    'plan' => 'price_au_pro_quarterly_placeholder', // Replace with actual Stripe price ID
                    'support' => array(
                        'basic' => 'price_au_support_basic_quarterly_placeholder',
                        'plus' => 'price_au_support_plus_quarterly_placeholder',
                        'ultimate' => 'price_au_support_ultimate_quarterly_placeholder',
                    ),
                ),
                'annual' => array(
                    'plan' => 'price_au_pro_annual_placeholder', // Replace with actual Stripe price ID
                    'support' => array(
                        'basic' => 'price_au_support_basic_annual_placeholder',
                        'plus' => 'price_au_support_plus_annual_placeholder',
                        'ultimate' => 'price_au_support_ultimate_annual_placeholder',
                    ),
                ),
            ),
            'ultimate' => array(
                'quarterly' => array(
                    'plan' => 'price_au_ultimate_quarterly_placeholder', // Replace with actual Stripe price ID
                    'support' => array(
                        'basic' => 'price_au_support_basic_quarterly_placeholder',
                        'plus' => 'price_au_support_plus_quarterly_placeholder',
                        'ultimate' => 'price_au_support_ultimate_quarterly_placeholder',
                    ),
                ),
                'annual' => array(
                    'plan' => 'price_au_ultimate_annual_placeholder', // Replace with actual Stripe price ID
                    'support' => array(
                        'basic' => 'price_au_support_basic_annual_placeholder',
                        'plus' => 'price_au_support_plus_annual_placeholder',
                        'ultimate' => 'price_au_support_ultimate_annual_placeholder',
                    ),
                ),
            ),
        );
    }

    /**
     * Get New Zealand price mapping
     *
     * @return array New Zealand price mapping
     */
    private static function get_nz_prices() {
        return array(
            'plus' => array(
                'quarterly' => array(
                    'plan' => 'price_nz_plus_quarterly_placeholder', // Replace with actual Stripe price ID
                    'support' => array(
                        'basic' => 'price_nz_support_basic_quarterly_placeholder',
                        'plus' => 'price_nz_support_plus_quarterly_placeholder',
                        'ultimate' => 'price_nz_support_ultimate_quarterly_placeholder',
                    ),
                ),
                'annual' => array(
                    'plan' => 'price_nz_plus_annual_placeholder', // Replace with actual Stripe price ID
                    'support' => array(
                        'basic' => 'price_nz_support_basic_annual_placeholder',
                        'plus' => 'price_nz_support_plus_annual_placeholder',
                        'ultimate' => 'price_nz_support_ultimate_annual_placeholder',
                    ),
                ),
            ),
            'pro' => array(
                'quarterly' => array(
                    'plan' => 'price_nz_pro_quarterly_placeholder', // Replace with actual Stripe price ID
                    'support' => array(
                        'basic' => 'price_nz_support_basic_quarterly_placeholder',
                        'plus' => 'price_nz_support_plus_quarterly_placeholder',
                        'ultimate' => 'price_nz_support_ultimate_quarterly_placeholder',
                    ),
                ),
                'annual' => array(
                    'plan' => 'price_nz_pro_annual_placeholder', // Replace with actual Stripe price ID
                    'support' => array(
                        'basic' => 'price_nz_support_basic_annual_placeholder',
                        'plus' => 'price_nz_support_plus_annual_placeholder',
                        'ultimate' => 'price_nz_support_ultimate_annual_placeholder',
                    ),
                ),
            ),
            'ultimate' => array(
                'quarterly' => array(
                    'plan' => 'price_nz_ultimate_quarterly_placeholder', // Replace with actual Stripe price ID
                    'support' => array(
                        'basic' => 'price_nz_support_basic_quarterly_placeholder',
                        'plus' => 'price_nz_support_plus_quarterly_placeholder',
                        'ultimate' => 'price_nz_support_ultimate_quarterly_placeholder',
                    ),
                ),
                'annual' => array(
                    'plan' => 'price_nz_ultimate_annual_placeholder', // Replace with actual Stripe price ID
                    'support' => array(
                        'basic' => 'price_nz_support_basic_annual_placeholder',
                        'plus' => 'price_nz_support_plus_annual_placeholder',
                        'ultimate' => 'price_nz_support_ultimate_annual_placeholder',
                    ),
                ),
            ),
        );
    }

    /**
     * Get price ID for plan and support combination
     *
     * @param string $country  Country code
     * @param string $plan     Plan type
     * @param string $support  Support level
     * @param string $billing  Billing period
     * @return array Array with 'plan' and 'support' price IDs
     */
    public static function get_price_ids($country, $plan, $support, $billing) {
        $prices = self::map($country);
        
        if (empty($prices[$plan][$billing])) {
            return array();
        }
        
        $plan_price = $prices[$plan][$billing]['plan'];
        $support_price = $prices[$plan][$billing]['support'][$support] ?? '';
        
        return array(
            'plan' => $plan_price,
            'support' => $support_price,
        );
    }

    /**
     * Validate that price IDs exist for given parameters
     *
     * @param string $country  Country code
     * @param string $plan     Plan type
     * @param string $support  Support level
     * @param string $billing  Billing period
     * @return bool True if valid, false otherwise
     */
    public static function validate_price_ids($country, $plan, $support, $billing) {
        $price_ids = self::get_price_ids($country, $plan, $support, $billing);
        
        return !empty($price_ids['plan']) && !empty($price_ids['support']);
    }
}
