=== Nova Multi-Stripe Checkout ===
Contributors: kbrookes
Tags: stripe, checkout, subscription, multi-currency, australia, new-zealand
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 8.1
Stable tag: 0.1.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Lightweight REST API for creating Stripe Checkout subscription sessions with per-seat quantities, using different Stripe accounts (AU/NZ) based on form input.

== Description ==

Nova Multi-Stripe Checkout is a lightweight WordPress plugin that provides a REST API for creating Stripe Checkout subscription sessions with per-seat quantities. The plugin supports different Stripe accounts for Australia and New Zealand, automatically routing customers based on their country selection.

**Key Features:**

* REST API endpoint for creating Stripe Checkout sessions
* Support for multiple Stripe accounts (AU/NZ)
* Per-seat quantity pricing for subscriptions
* Multiple plan tiers (Plus, Pro, Ultimate)
* Support level options (Basic, Plus, Ultimate)
* Quarterly and annual billing cycles
* Webhook handling for subscription events
* Environment variable support for API keys
* WordPress Coding Standards compliant
* Static analysis with PHPStan
* Security audit integration

**Perfect for:**
* SaaS applications with per-seat pricing
* Multi-region businesses
* Bricks Builder forms
* Custom WordPress integrations
* Headless WordPress setups

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/nova-multi-stripe-checkout` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Go to Settings → Nova Checkout to configure your Stripe accounts
4. Set up your Stripe price IDs in the plugin code
5. Configure your success and cancel URLs

== Configuration ==

**Stripe Setup:**
1. Create Stripe accounts for Australia and New Zealand
2. Create products and prices in both accounts
3. Update the price IDs in `class-nova-prices.php`
4. Set your API keys in Settings → Nova Checkout

**Environment Variables (Recommended):**
```
STRIPE_AU_SK=sk_test_...
STRIPE_NZ_SK=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...
```

**API Endpoint:**
```
POST /wp-json/nova/v1/checkout
```

**Request Body:**
```json
{
  "country": "AU",
  "plan": "plus",
  "support": "basic",
  "billing": "annual",
  "users": 5
}
```

**Response:**
```json
{
  "url": "https://checkout.stripe.com/...",
  "session_id": "cs_..."
}
```

== Changelog ==

= 0.1.6 =
* Add detailed debug logging to checkout endpoint for better error diagnosis

= 0.1.5 =
* Added debug REST endpoint for better troubleshooting
* Improved error handling and diagnostics
* Enhanced plugin debugging capabilities

= 0.1.4 =
* Fixed Stripe SDK loading with improved error handling
* Added debug file for troubleshooting
* Enhanced fallback logic for dependency loading

= 0.1.3 =
* Added Update URI header for better Git Updater compatibility
* Removed vendor directory to prevent conflicts
* Enhanced plugin detection

= 0.1.2 =
* Fixed Git Updater compatibility with embedded Stripe SDK
* Restored Composer fallback for proper dependency loading
* Resolved plugin activation issues

= 0.1.1 =
* Fixed coding standards compliance
* Updated Git Updater headers
* Improved error handling
* Enhanced documentation

= 0.1.0 =
* Initial release
* REST API for Stripe Checkout sessions
* Multi-country support (AU/NZ)
* Per-seat quantity pricing
* Webhook handling
* Admin settings page
* Environment variable support

== Upgrade Notice ==

= 0.1.2 =
Fixed Git Updater compatibility and plugin activation issues.

= 0.1.1 =
Minor improvements and bug fixes.

= 0.1.0 =
Initial release of Nova Multi-Stripe Checkout.

== Frequently Asked Questions ==

= How do I get my Stripe price IDs? =

1. Log into your Stripe Dashboard
2. Go to Products
3. Create your products and prices
4. Copy the price IDs (they start with `price_`)
5. Update the placeholders in `class-nova-prices.php`

= Can I use this with Bricks Builder? =

Yes! The plugin provides a REST API that can be called from any form, including Bricks Builder forms.

= How do I test the webhook locally? =

Use the Stripe CLI:
```bash
stripe listen --forward-to localhost:8080/wp-json/nova/v1/stripe-webhook
```

= Can I add more countries? =

Yes, you can extend the plugin by adding more countries to the price mapping and validation functions.

== Screenshots ==

1. Admin settings page
2. REST API endpoint documentation
3. Stripe Checkout session example

== Support ==

For support, please open an issue on GitHub or contact us at <SUPPORT_EMAIL>.
