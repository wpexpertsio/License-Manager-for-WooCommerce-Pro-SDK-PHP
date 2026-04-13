<?php

namespace LicenseManagerSDK\Resources;

use LicenseManagerSDK\HttpClient;

/**
 * Class Licenses
 *
 * Handles all license-related REST API endpoints.
 *
 * Endpoints covered:
 *   - GET    /lmfwc/v2/licenses                              → list()
 *   - GET    /lmfwc/v2/licenses/{license_key}                → get()
 *   - POST   /lmfwc/v2/licenses                              → create()
 *   - PUT    /lmfwc/v2/licenses/{license_key}                → update()
 *   - DELETE /lmfwc/v2/licenses/{license_key}                → delete()
 *   - GET    /lmfwc/v2/licenses/activate/{license_key}       → activate()
 *   - GET    /lmfwc/v2/licenses/deactivate/{license_key}     → deactivate()
 *   - GET    /lmfwc/v2/licenses/activate/{license_key}       → reactivate() (with token)
 *   - GET    /lmfwc/v2/licenses/validate/{license_key}       → validate()
 *
 * @package LicenseManagerSDK\Resources
 */
class Licenses {

    /**
     * HTTP client instance.
     *
     * @var HttpClient
     */
    private $http;

    /**
     * Licenses constructor.
     *
     * @param HttpClient $http HTTP client.
     */
    public function __construct( HttpClient $http ) {
        $this->http = $http;
    }

    /**
     * List all license keys.
     *
     * @param array $params Optional query parameters (e.g. page, per_page).
     *
     * @return mixed Array of license data objects.
     * @throws \LicenseManagerSDK\LicenseManagerException
     */
    public function list( $params = array() ) {
        return $this->http->get( 'licenses', $params );
    }

    /**
     * Retrieve a single license key by its key string.
     *
     * @param string $license_key The license key.
     *
     * @return mixed License data object.
     * @throws \LicenseManagerSDK\LicenseManagerException
     */
    public function get( $license_key ) {
        return $this->http->get( sprintf( 'licenses/%s', rawurlencode( (string)$license_key ) ) );
    }

    /**
     * Create a new license key.
     *
     * @param array $data {
     *     License data.
     *
     *     @type string $license_key         The license key string.
     *     @type string $product_id          WooCommerce product ID.
     *     @type string $user_id             WordPress user ID.
     *     @type string $order_id            WooCommerce order ID.
     *     @type string $expires_at          Expiry date (Y-m-d). Do not combine with valid_for.
     *     @type int    $valid_for           Number of days valid. Do not combine with expires_at.
     *     @type string $status              License status: 'active', 'inactive', 'sold', 'delivered'.
     *     @type int    $times_activated_max Maximum allowed activations.
     * }
     *
     * @return mixed Newly created license data object.
     * @throws \LicenseManagerSDK\LicenseManagerException
     */
    public function create( $data ) {
        return $this->http->post( 'licenses', $data );
    }

    /**
     * Update an existing license key.
     *
     * @param string $license_key The license key to update.
     * @param array  $data        Fields to update (same structure as create()).
     *
     * @return mixed Updated license data object.
     * @throws \LicenseManagerSDK\LicenseManagerException
     */
    public function update( $license_key, $data ) {
        return $this->http->put( sprintf( 'licenses/%s', rawurlencode( (string)$license_key ) ), $data );
    }

    /**
     * Delete a license key.
     *
     * @param string $license_key The license key to delete.
     *
     * @return mixed Deleted license data object.
     * @throws \LicenseManagerSDK\LicenseManagerException
     */
    public function delete( $license_key ) {
        return $this->http->delete( sprintf( 'licenses/%s', rawurlencode( (string)$license_key ) ) );
    }

    /**
     * Activate a license key.
     *
     * Increments times_activated by 1 and returns the updated license + activation token.
     *
     * @param string $license_key The license key to activate.
     * @param array  $params      Optional query params (e.g. label, meta_data).
     *
     * @return mixed Updated license data object including activationData.
     * @throws \LicenseManagerSDK\LicenseManagerException
     */
    public function activate( $license_key, $params = array() ) {
        return $this->http->get(
            sprintf( 'licenses/activate/%s', rawurlencode( (string)$license_key ) ),
            $params
        );
    }

    /**
     * Deactivate a license key (or a specific activation token).
     *
     * When no token is passed, all activations are deactivated.
     * To deactivate a single activation, pass 'token' in $params.
     *
     * @param string $license_key The license key to deactivate.
     * @param array  $params      Optional. Pass ['token' => 'xxx'] to target a specific activation.
     *
     * @return mixed Updated license data object.
     * @throws \LicenseManagerSDK\LicenseManagerException
     */
    public function deactivate( $license_key, $params = array() ) {
        return $this->http->get(
            sprintf( 'licenses/deactivate/%s', rawurlencode( (string)$license_key ) ),
            $params
        );
    }

    /**
     * Reactivate a previously deactivated license token.
     *
     * Uses the same activate endpoint but with a token parameter to re-enable
     * a specific deactivated activation record.
     *
     * @param string $license_key The license key to reactivate.
     * @param array  $params      Optional. Pass ['token' => 'xxx'] to reactivate a specific activation.
     *
     * @return mixed Updated license data object including activationData.
     * @throws \LicenseManagerSDK\LicenseManagerException
     */
    public function reactivate( $license_key, $params = array() ) {
        return $this->http->get(
            sprintf( 'licenses/activate/%s', rawurlencode( (string)$license_key ) ),
            $params
        );
    }

    /**
     * Validate a license key.
     *
     * Checks the current activation status (times_activated, timesActivatedMax, remaining).
     *
     * @param string $license_key The license key to validate.
     *
     * @return mixed License data object with activation details.
     * @throws \LicenseManagerSDK\LicenseManagerException
     */
    public function validate( $license_key ) {
        return $this->http->get(
            sprintf( 'licenses/validate/%s', rawurlencode( (string)$license_key ) )
        );
    }
}
