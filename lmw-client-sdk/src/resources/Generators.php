<?php

namespace LicenseManagerSDK\Resources;

use LicenseManagerSDK\HttpClient;

/**
 * Class Generators
 *
 * Handles all generator-related REST API endpoints.
 *
 * Endpoints covered:
 *   - GET    /lmfwc/v2/generators                        → list()
 *   - GET    /lmfwc/v2/generators/{id}                   → get()
 *   - POST   /lmfwc/v2/generators                        → create()
 *   - PUT    /lmfwc/v2/generators/{id}                   → update()
 *   - DELETE /lmfwc/v2/generators/{id}                   → delete()
 *   - POST   /lmfwc/v2/generators/{id}/generate (Pro)    → generate()
 *
 * @package LicenseManagerSDK\Resources
 */
class Generators {

    /**
     * HTTP client instance.
     *
     * @var HttpClient
     */
    private $http;

    /**
     * Generators constructor.
     *
     * @param HttpClient $http HTTP client.
     */
    public function __construct( HttpClient $http ) {
        $this->http = $http;
    }

    /**
     * List all generators.
     *
     * @return mixed Array of generator data objects.
     * @throws \LicenseManagerSDK\LicenseManagerException
     */
    public function list() {
        return $this->http->get( 'generators' );
    }

    /**
     * Retrieve a single generator by ID.
     *
     * @param int $generator_id The generator ID.
     *
     * @return mixed Generator data object.
     * @throws \LicenseManagerSDK\LicenseManagerException
     */
    public function get( $generator_id ) {
        return $this->http->get( sprintf( 'generators/%d', (int) $generator_id ) );
    }

    /**
     * Create a new generator.
     *
     * @param array $data {
     *     Generator data.
     *
     *     @type string $name                Display name for the generator.
     *     @type string $charset             Character set used for key generation.
     *     @type int    $chunks              Number of chunks in the key.
     *     @type int    $chunk_length        Characters per chunk.
     *     @type int    $times_activated_max Maximum activations per generated key.
     *     @type string $separator           Separator character between chunks (e.g. '-').
     *     @type string $prefix              Prefix prepended to the key.
     *     @type string $suffix              Suffix appended to the key.
     *     @type int    $expires_in          Number of days until generated keys expire.
     * }
     *
     * @return mixed Newly created generator data object.
     * @throws \LicenseManagerSDK\LicenseManagerException
     */
    public function create( $data ) {
        return $this->http->post( 'generators', $data );
    }

    /**
     * Update an existing generator.
     *
     * @param int   $generator_id The generator ID to update.
     * @param array $data         Fields to update (same structure as create()).
     *
     * @return mixed Updated generator data object.
     * @throws \LicenseManagerSDK\LicenseManagerException
     */
    public function update( $generator_id, $data ) {
        return $this->http->put( sprintf( 'generators/%d', (int) $generator_id ), $data );
    }

    /**
     * Delete a generator.
     *
     * @param int $generator_id The generator ID to delete.
     *
     * @return mixed Deleted generator data object.
     * @throws \LicenseManagerSDK\LicenseManagerException
     */
    public function delete( $generator_id ) {
        return $this->http->delete( sprintf( 'generators/%d', (int) $generator_id ) );
    }

    /**
     * Generate license keys using a generator (Pro).
     *
     * @param int   $generator_id The generator ID to use.
     * @param array $data         {
     *     Generation options.
     *
     *     @type int         $amount     Number of license keys to generate.
     *     @type bool        $save       Whether to save generated keys to the database.
     *     @type string      $status     Status for saved keys: 'active', 'inactive', 'sold', 'delivered'.
     *     @type int|null    $order_id   WooCommerce order ID to associate with (optional).
     *     @type int|null    $product_id WooCommerce product ID to associate with (optional).
     *     @type int|null    $user_id    WordPress user ID to associate with (optional).
     * }
     *
     * @return mixed Array of generated license key strings.
     * @throws \LicenseManagerSDK\LicenseManagerException
     */
    public function generate( $generator_id, $data ) {
        return $this->http->post(
            sprintf( 'generators/%d/generate', (int) $generator_id ),
            $data
        );
    }
}
