<?php

namespace LicenseManagerSDK\Resources;

use LicenseManagerSDK\HttpClient;

/**
 * Class Products
 *
 * Handles product-related REST API endpoints (Pro).
 *
 * Endpoints covered:
 *   - POST /lmfwc/v2/products/ping                          → ping()
 *   - GET  /lmfwc/v2/products/update/{license_key}          → update()
 *   - GET  /lmfwc/v2/products/download/latest/{license_key} → downloadUrl()
 *
 * @package LicenseManagerSDK\Resources
 */
class Products {

    /**
     * HTTP client instance.
     *
     * @var HttpClient
     */
    private $http;

    /**
     * Products constructor.
     *
     * @param HttpClient $http HTTP client.
     */
    public function __construct( HttpClient $http ) {
        $this->http = $http;
    }

    /**
     * Send a ping request to register/update an installation record (Pro).
     *
     * After calling this, the admin can see the ping details in:
     * WooCommerce > Products Installed On
     *
     * @param array $data {
     *     Ping data.
     *
     *     @type string $license_key  The license key used on the site.
     *     @type string $product_name The product name installed on the site.
     *     @type string $host         The host/domain of the site (e.g. 'example.com').
     * }
     *
     * @return mixed API response data (null on success).
     * @throws \LicenseManagerSDK\LicenseManagerException
     */
    public function ping( $data ) {
        return $this->http->post( 'products/ping', $data );
    }

    /**
     * Get product update information for a license key (Pro).
     *
     * Returns product version details and a signed download URL.
     *
     * @param string $license_key The license key.
     *
     * @return mixed Product update data object (new_version, package URL, changelog, etc.).
     * @throws \LicenseManagerSDK\LicenseManagerException
     */
    public function update( $license_key ) {
        return $this->http->get(
            sprintf( 'products/update/%s', rawurlencode( (string)$license_key ) )
        );
    }

    /**
     * Get the download URL for the latest product release (Pro).
     *
     * Note: This URL is intended for browser-based downloads only.
     * The URL returned includes consumer_key and consumer_secret as query params.
     *
     * @param string $license_key The license key.
     *
     * @return string Signed download URL for the latest product package.
     */
    public function downloadUrl( $license_key ) {
        // This endpoint streams a file download directly, so we return the URL
        // rather than making the request via the HTTP client.
        return $this->http->buildUrl(
            sprintf( 'products/download/latest/%s', rawurlencode( (string)$license_key ) )
        );
    }
}
