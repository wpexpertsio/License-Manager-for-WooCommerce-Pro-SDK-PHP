<?php

/**
 * License Manager Pro SDK — Entry Point
 *
 * This is the framework-agnostic entry point for the SDK.
 * Require this file in your PHP project to initialize the SDK.
 *
 * Usage:
 *   require_once 'path/to/sdk/lmw-sdk.php';
 *
 *   $client = lmw_sdk_init( array(
 *       'store_url'       => 'https://your-store.com',
 *       'consumer_key'    => 'ck_xxxxxxxxxxxx',
 *       'consumer_secret' => 'cs_xxxxxxxxxxxx',
 *   ) );
 *
 * @package LicenseManagerSDK
 * @version 1.0.0
 */

// Load the autoloader first.
require_once __DIR__ . '/src/autoload.php';

use LicenseManagerSDK\LicenseManagerClient;

if ( ! function_exists( 'lmw_sdk_init' ) ) {

    /**
     * Initialize and return a LicenseManagerClient instance.
     *
     * @param array $config {
     *     Required configuration.
     *
     *     @type string $store_url       Base URL of the WooCommerce store.
     *     @type string $consumer_key    WooCommerce REST API Consumer Key.
     *     @type string $consumer_secret WooCommerce REST API Consumer Secret.
     *     @type string $api_namespace   Optional. Default: 'lmfwc/v2'.
     *     @type int    $timeout         Optional. Default: 30.
     * }
     *
     * @return LicenseManagerClient|null Client instance, or null if config is invalid.
     */
    function lmw_sdk_init( array $config ) {
        try {
            return new LicenseManagerClient( $config );
        } catch ( \Exception $e ) {
            // Log error or handle as needed
            return null;
        }
    }
}
