<?php

namespace LicenseManagerSDK;

use LicenseManagerSDK\Resources\Licenses;
use LicenseManagerSDK\Resources\Generators;
use LicenseManagerSDK\Resources\Products;
use LicenseManagerSDK\Resources\Customers;
use LicenseManagerSDK\Resources\Applications;

/**
 * Class LicenseManagerClient
 *
 * Main entry point for interacting with the License Manager for WooCommerce REST API.
 * Framework-agnostic.
 *
 * Usage:
 *   $client = new LicenseManagerClient([
 *       'store_url'       => 'https://your-store.com',
 *       'consumer_key'    => 'ck_xxxxxxxxxxxx',
 *       'consumer_secret' => 'cs_xxxxxxxxxxxx',
 *   ]);
 *   $licenses = $client->licenses()->list();
 *
 * @package LicenseManagerSDK
 */
class LicenseManagerClient {

    /**
     * SDK Version.
     */
    const VERSION = '1.0.0';

    /**
     * Default API namespace.
     */
    const API_NAMESPACE = 'lmfwc/v2';

    /**
     * Configuration array.
     *
     * @var array
     */
    private $config;

    /**
     * HTTP client instance.
     *
     * @var HttpClient
     */
    private $http;

    /**
     * Cached resource instances.
     *
     * @var array
     */
    private $resources = array();

    /**
     * LicenseManagerClient constructor.
     *
     * @param array $config {
     *     Configuration options.
     *
     *     @type string $store_url       Required. Base URL of the WordPress/WooCommerce store.
     *     @type string $consumer_key    Required. WooCommerce REST API consumer key.
     *     @type string $consumer_secret Required. WooCommerce REST API consumer secret.
     *     @type string $api_namespace   Optional. API namespace. Default: 'lmfwc/v2'.
     *     @type int    $timeout         Optional. Request timeout in seconds. Default: 30.
     * }
     *
     * @throws \InvalidArgumentException If required config keys are missing.
     */
    public function __construct( array $config ) {
        $required = array( 'store_url', 'consumer_key', 'consumer_secret' );

        foreach ( $required as $key ) {
            if ( empty( $config[ $key ] ) ) {
                throw new \InvalidArgumentException(
                    sprintf( 'License Manager SDK: Missing required config key "%s".', $key )
                );
            }
        }

        $this->config = array_merge(
            array(
                'api_namespace' => self::API_NAMESPACE,
                'timeout'       => 30,
            ),
            $config
        );

        $this->http = new HttpClient(
            $this->config['store_url'],
            $this->config['consumer_key'],
            $this->config['consumer_secret'],
            $this->config['api_namespace'],
            $this->config['timeout']
        );
    }

    /**
     * Get the underlying HTTP client.
     *
     * @return HttpClient
     */
    public function getHttpClient() {
        return $this->http;
    }

    /**
     * Get the store URL.
     *
     * @return string
     */
    public function getStoreUrl() {
        return $this->config['store_url'];
    }

    /**
     * Get a config value.
     *
     * @param string $key     Config key.
     * @param mixed  $default Default value if not set.
     *
     * @return mixed
     */
    public function getConfig( $key, $default = null ) {
        return isset( $this->config[ $key ] ) ? $this->config[ $key ] : $default;
    }

    // -------------------------------------------------------------------------
    // Resource factories
    // -------------------------------------------------------------------------

    /**
     * Get the Licenses resource.
     *
     * @return Licenses
     */
    public function licenses() {
        if ( ! isset( $this->resources['licenses'] ) ) {
            $this->resources['licenses'] = new Licenses( $this->http );
        }
        return $this->resources['licenses'];
    }

    /**
     * Get the Generators resource.
     *
     * @return Generators
     */
    public function generators() {
        if ( ! isset( $this->resources['generators'] ) ) {
            $this->resources['generators'] = new Generators( $this->http );
        }
        return $this->resources['generators'];
    }

    /**
     * Get the Products resource (Pro).
     *
     * @return Products
     */
    public function products() {
        if ( ! isset( $this->resources['products'] ) ) {
            $this->resources['products'] = new Products( $this->http );
        }
        return $this->resources['products'];
    }

    /**
     * Get the Customers resource (Pro).
     *
     * @return Customers
     */
    public function customers() {
        if ( ! isset( $this->resources['customers'] ) ) {
            $this->resources['customers'] = new Customers( $this->http );
        }
        return $this->resources['customers'];
    }

    /**
     * Get the Applications resource (Pro).
     *
     * @return Applications
     */
    public function applications() {
        if ( ! isset( $this->resources['applications'] ) ) {
            $this->resources['applications'] = new Applications( $this->http );
        }
        return $this->resources['applications'];
    }
}
