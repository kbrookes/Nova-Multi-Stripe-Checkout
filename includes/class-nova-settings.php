<?php
/**
 * Settings management for Nova Multi-Stripe Checkout
 *
 * @package NovaMSC
 */

namespace NovaMSC;

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Settings class
 */
class Settings {

    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'init_settings'));
    }

    /**
     * Add admin menu page
     */
    public function add_admin_menu() {
        add_options_page(
            'Nova Checkout Settings',
            'Nova Checkout',
            'manage_options',
            'nova-checkout',
            array($this, 'admin_page')
        );
    }

    /**
     * Initialize settings
     */
    public function init_settings() {
        register_setting('nova_msc_settings', NOVA_MSC_OPT, array($this, 'sanitize_options'));

        add_settings_section(
            'nova_msc_general',
            'General Settings',
            array($this, 'general_section_callback'),
            'nova-checkout'
        );

        add_settings_field(
            'mode',
            'Mode',
            array($this, 'mode_field_callback'),
            'nova-checkout',
            'nova_msc_general'
        );

        add_settings_field(
            'au_sk',
            'Australia Stripe Secret Key',
            array($this, 'au_sk_field_callback'),
            'nova-checkout',
            'nova_msc_general'
        );

        add_settings_field(
            'nz_sk',
            'New Zealand Stripe Secret Key',
            array($this, 'nz_sk_field_callback'),
            'nova-checkout',
            'nova_msc_general'
        );

        add_settings_section(
            'nova_msc_urls',
            'URLs',
            array($this, 'urls_section_callback'),
            'nova-checkout'
        );

        add_settings_field(
            'success_url_au',
            'Australia Success URL',
            array($this, 'success_url_au_field_callback'),
            'nova-checkout',
            'nova_msc_urls'
        );

        add_settings_field(
            'cancel_url_au',
            'Australia Cancel URL',
            array($this, 'cancel_url_au_field_callback'),
            'nova-checkout',
            'nova_msc_urls'
        );

        add_settings_field(
            'success_url_nz',
            'New Zealand Success URL',
            array($this, 'success_url_nz_field_callback'),
            'nova-checkout',
            'nova_msc_urls'
        );

        add_settings_field(
            'cancel_url_nz',
            'New Zealand Cancel URL',
            array($this, 'cancel_url_nz_field_callback'),
            'nova-checkout',
            'nova_msc_urls'
        );

        add_settings_field(
            'portal_enabled',
            'Enable Customer Portal',
            array($this, 'portal_enabled_field_callback'),
            'nova-checkout',
            'nova_msc_urls'
        );
    }

    /**
     * Sanitize options
     *
     * @param array $input Raw input data
     * @return array Sanitized data
     */
    public function sanitize_options($input) {
        $sanitized = array();

        if (isset($input['mode'])) {
            $sanitized['mode'] = in_array($input['mode'], array('test', 'live'), true) ? $input['mode'] : 'test';
        }

        if (isset($input['au_sk'])) {
            $sanitized['au_sk'] = sanitize_text_field($input['au_sk']);
        }

        if (isset($input['nz_sk'])) {
            $sanitized['nz_sk'] = sanitize_text_field($input['nz_sk']);
        }

        if (isset($input['success_url_au'])) {
            $sanitized['success_url_au'] = esc_url_raw($input['success_url_au']);
        }

        if (isset($input['cancel_url_au'])) {
            $sanitized['cancel_url_au'] = esc_url_raw($input['cancel_url_au']);
        }

        if (isset($input['success_url_nz'])) {
            $sanitized['success_url_nz'] = esc_url_raw($input['success_url_nz']);
        }

        if (isset($input['cancel_url_nz'])) {
            $sanitized['cancel_url_nz'] = esc_url_raw($input['cancel_url_nz']);
        }

        if (isset($input['portal_enabled'])) {
            $sanitized['portal_enabled'] = (bool) $input['portal_enabled'];
        }

        return $sanitized;
    }

    /**
     * Admin page callback
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1>Nova Checkout Settings</h1>
            
            <div class="notice notice-info">
                <p><strong>Environment Variables:</strong> Stripe secret keys can be set via environment variables <code>STRIPE_AU_SK</code> and <code>STRIPE_NZ_SK</code> which will override the settings below.</p>
            </div>

            <form method="post" action="options.php">
                <?php
                settings_fields('nova_msc_settings');
                do_settings_sections('nova-checkout');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * General section callback
     */
    public function general_section_callback() {
        echo '<p>Configure your Stripe accounts and general settings.</p>';
    }

    /**
     * URLs section callback
     */
    public function urls_section_callback() {
        echo '<p>Set success and cancel URLs for each country.</p>';
    }

    /**
     * Mode field callback
     */
    public function mode_field_callback() {
        $mode = nova_msc_opt_get('mode', 'test');
        ?>
        <select name="<?php echo esc_attr(NOVA_MSC_OPT); ?>[mode]">
            <option value="test" <?php selected($mode, 'test'); ?>>Test</option>
            <option value="live" <?php selected($mode, 'live'); ?>>Live</option>
        </select>
        <p class="description">Use test mode for development and testing.</p>
        <?php
    }

    /**
     * AU SK field callback
     */
    public function au_sk_field_callback() {
        $au_sk = nova_msc_opt_get('au_sk', '');
        $env_override = getenv('STRIPE_AU_SK') ? ' (Environment variable set)' : '';
        ?>
        <input type="password" name="<?php echo esc_attr(NOVA_MSC_OPT); ?>[au_sk]" value="<?php echo esc_attr($au_sk); ?>" class="regular-text" />
        <p class="description">Australia Stripe Secret Key<?php echo esc_html($env_override); ?></p>
        <?php
    }

    /**
     * NZ SK field callback
     */
    public function nz_sk_field_callback() {
        $nz_sk = nova_msc_opt_get('nz_sk', '');
        $env_override = getenv('STRIPE_NZ_SK') ? ' (Environment variable set)' : '';
        ?>
        <input type="password" name="<?php echo esc_attr(NOVA_MSC_OPT); ?>[nz_sk]" value="<?php echo esc_attr($nz_sk); ?>" class="regular-text" />
        <p class="description">New Zealand Stripe Secret Key<?php echo esc_html($env_override); ?></p>
        <?php
    }

    /**
     * Success URL AU field callback
     */
    public function success_url_au_field_callback() {
        $url = nova_msc_opt_get('success_url_au', home_url('/success-au/'));
        ?>
        <input type="url" name="<?php echo esc_attr(NOVA_MSC_OPT); ?>[success_url_au]" value="<?php echo esc_attr($url); ?>" class="regular-text" />
        <p class="description">URL to redirect after successful payment for Australia customers.</p>
        <?php
    }

    /**
     * Cancel URL AU field callback
     */
    public function cancel_url_au_field_callback() {
        $url = nova_msc_opt_get('cancel_url_au', home_url('/cancel-au/'));
        ?>
        <input type="url" name="<?php echo esc_attr(NOVA_MSC_OPT); ?>[cancel_url_au]" value="<?php echo esc_attr($url); ?>" class="regular-text" />
        <p class="description">URL to redirect if payment is cancelled for Australia customers.</p>
        <?php
    }

    /**
     * Success URL NZ field callback
     */
    public function success_url_nz_field_callback() {
        $url = nova_msc_opt_get('success_url_nz', home_url('/success-nz/'));
        ?>
        <input type="url" name="<?php echo esc_attr(NOVA_MSC_OPT); ?>[success_url_nz]" value="<?php echo esc_attr($url); ?>" class="regular-text" />
        <p class="description">URL to redirect after successful payment for New Zealand customers.</p>
        <?php
    }

    /**
     * Cancel URL NZ field callback
     */
    public function cancel_url_nz_field_callback() {
        $url = nova_msc_opt_get('cancel_url_nz', home_url('/cancel-nz/'));
        ?>
        <input type="url" name="<?php echo esc_attr(NOVA_MSC_OPT); ?>[cancel_url_nz]" value="<?php echo esc_attr($url); ?>" class="regular-text" />
        <p class="description">URL to redirect if payment is cancelled for New Zealand customers.</p>
        <?php
    }

    /**
     * Portal enabled field callback
     */
    public function portal_enabled_field_callback() {
        $enabled = nova_msc_opt_get('portal_enabled', false);
        ?>
        <input type="checkbox" name="<?php echo esc_attr(NOVA_MSC_OPT); ?>[portal_enabled]" value="1" <?php checked($enabled); ?> />
        <p class="description">Enable Stripe Customer Portal for subscription management (future feature).</p>
        <?php
    }
}
