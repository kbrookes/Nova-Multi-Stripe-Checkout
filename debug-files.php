<?php
/**
 * Debug file to check if Stripe SDK files exist
 * This file can be accessed via: https://your-site.com/wp-content/plugins/nova-multi-stripe-checkout/debug-files.php
 */

// Get the plugin directory
$plugin_dir = dirname(__FILE__);

echo "<h2>Nova Multi-Stripe Checkout - File Debug</h2>";
echo "<p><strong>Plugin Directory:</strong> " . $plugin_dir . "</p>";

// Check vendor directory
$vendor_path = $plugin_dir . '/vendor/autoload.php';
echo "<p><strong>Vendor autoloader:</strong> " . ($vendor_path) . "</p>";
echo "<p><strong>Vendor exists:</strong> " . (file_exists($vendor_path) ? 'YES' : 'NO') . "</p>";

// Check Stripe SDK
$stripe_path = $plugin_dir . '/includes/stripe-php/init.php';
echo "<p><strong>Stripe init file:</strong> " . ($stripe_path) . "</p>";
echo "<p><strong>Stripe exists:</strong> " . (file_exists($stripe_path) ? 'YES' : 'NO') . "</p>";

// Check includes directory
$includes_dir = $plugin_dir . '/includes/';
echo "<p><strong>Includes directory:</strong> " . ($includes_dir) . "</p>";
echo "<p><strong>Includes exists:</strong> " . (is_dir($includes_dir) ? 'YES' : 'NO') . "</p>";

if (is_dir($includes_dir)) {
    echo "<p><strong>Includes contents:</strong></p><ul>";
    $files = scandir($includes_dir);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            echo "<li>" . $file . "</li>";
        }
    }
    echo "</ul>";
}

// Check if we can load Stripe
if (file_exists($stripe_path)) {
    echo "<p><strong>Attempting to load Stripe SDK...</strong></p>";
    try {
        require_once $stripe_path;
        echo "<p style='color: green;'><strong>SUCCESS:</strong> Stripe SDK loaded successfully!</p>";
        
        // Test if Stripe classes are available
        if (class_exists('Stripe\Stripe')) {
            echo "<p style='color: green;'><strong>SUCCESS:</strong> Stripe\\Stripe class is available!</p>";
        } else {
            echo "<p style='color: red;'><strong>ERROR:</strong> Stripe\\Stripe class not found!</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'><strong>ERROR:</strong> " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='color: red;'><strong>ERROR:</strong> Stripe SDK file not found!</p>";
}
?>
