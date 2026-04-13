<?php

namespace LicenseManagerSDK\Resources;

use LicenseManagerSDK\HttpClient;

/**
 * Class Customers
 *
 * Handles customer-related REST API endpoints (Pro).
 *
 * Endpoints covered:
 *   - GET /lmfwc/v2/customers/{id}/licenses → getLicenses()
 *
 * @package LicenseManagerSDK\Resources
 */
class Customers {

    /**
     * HTTP client instance.
     *
     * @var HttpClient
     */
    private $http;

    /**
     * Customers constructor.
     *
     * @param HttpClient $http HTTP client.
     */
    public function __construct( HttpClient $http ) {
        $this->http = $http;
    }

    /**
     * Get all license keys assigned to a specific customer (Pro).
     *
     * @param int $customer_id The WordPress user ID of the customer.
     *
     * @return mixed Array of license data objects belonging to the customer.
     * @throws \LicenseManagerSDK\LicenseManagerException
     */
    public function getLicenses( $customer_id ) {
        return $this->http->get(
            sprintf( 'customers/%d/licenses', (int) $customer_id )
        );
    }
}
