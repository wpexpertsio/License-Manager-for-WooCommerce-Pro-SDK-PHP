<?php
/**
 * License Manager Pro SDK — Multi-Framework Example
 *
 * This file demonstrates how to integrate the License Manager Pro SDK
 * into any PHP application (SaaS, CMS, Laravel, or WordPress).
 *
 * IMPORTANT: Replace the store_url, consumer_key, and consumer_secret
 * with your actual WooCommerce REST API credentials.
 *
 * @package YourSoftware
 */

if ( ! function_exists( 'my_saas_sdk' ) ) {

    /**
     * Helper function for easy SDK access.
     * Works in WordPress, Laravel, or Standalone PHP.
     *
     * @return \LicenseManagerSDK\LicenseManagerClient|null
     */
    function my_saas_sdk() {
        global $my_saas_sdk_client;

        if ( ! isset( $my_saas_sdk_client ) ) {

            // Include the SDK Entry Point
            // Adjust the path based on your project structure
            require_once __DIR__ . '/vendor/lmw-client-sdk/lmw-sdk.php';

            $my_saas_sdk_client = lmw_sdk_init( array(
                'store_url'       => 'https://your-store.com',    // ← Your WooCommerce URL
                'consumer_key'    => 'ck_xxxxxxxxxxxxxxxxxxxx',   // ← Your WC consumer key
                'consumer_secret' => 'cs_xxxxxxxxxxxxxxxxxxxx',   // ← Your WC consumer secret
                'timeout'         => 30,                          // Optional
            ) );
        }

        return $my_saas_sdk_client;
    }
}

// =============================================================================
// SAAS LOGIC EXAMPLES
// =============================================================================

/**
 * Example: Activate a license in your software.
 *
 * @param string $license_key The key entered by your customer.
 * @return array Result containing success token or error message.
 */
function handle_software_activation( $license_key ) {
    try {
        $result = my_saas_sdk()->licenses()->activate( $license_key );

        if ( isset( $result->activationData->token ) ) {
            // SUCCESS: Save the token in your database/config
            // This token is required for future validation and deactivation.
            return [
                'success' => true,
                'token'   => $result->activationData->token,
                'license' => $result
            ];
        }
    } catch ( \Exception $e ) {
        return [
            'success' => false,
            'error'   => $e->getMessage()
        ];
    }
}

/**
 * Example: Periodically validate the license status.
 *
 * @param string $license_key The customer's license key.
 * @return bool True if the license is active.
 */
function is_license_active( $license_key ) {
    try {
        $license = my_saas_sdk()->licenses()->validate( $license_key );

        // Validation logic: Status 3 is "Active" in License Manager for WooCommerce.
        return isset( $license->status ) && (int) $license->status === 3;

    } catch ( \Exception $e ) {
        // If the API returns an error (e.g. key deleted or expired), treat as inactive.
        return false;
    }
}

/**
 * Example: Check for Software Updates (Pro).
 *
 * @param string $license_key The customer's license key.
 * @param string $current_version The version currently installed.
 * @return array|null Update information if available.
 */
function check_for_saas_updates( $license_key, $current_version ) {
    try {
        $update_info = my_saas_sdk()->products()->update( $license_key );

        // If a new version is released on the store:
        if ( isset( $update_info->new_version ) && version_compare( $update_info->new_version, $current_version, '>' ) ) {
            return [
                'version'     => $update_info->new_version,
                'download_url' => $update_info->package, // This is a signed download link
                'changelog'   => $update_info->sections->changelog ?? ''
            ];
        }
    } catch ( \Exception $e ) {
        // Log errors silently for update checks
    }
    return null;
}

/**
 * Example: Bulk Generator (After a successful custom payment).
 *
 * @param int $generator_id The ID of your pre-configured generator.
 * @return string Newly generated key.
 */
function generate_key_for_new_customer( $generator_id ) {
    try {
        $keys = my_saas_sdk()->generators()->generate( $generator_id, [
            'amount' => 1,
            'save'   => true,
            'status' => 'active'
        ] );
        
        return $keys[0] ?? null;
    } catch ( \Exception $e ) {
        return null;
    }
}

// =============================================================================
// MERCHANT TIPS
// =============================================================================
/*
 * 1. PING: Use $sdk->products()->ping() during activation to track where 
 *    your software is being used (IP, Host, etc.).
 * 2. CUSTOMERS: Use $sdk->customers()->getLicenses($id) to build a "My Licenses" 
 *    dashboard for your users inside your software.
 */
