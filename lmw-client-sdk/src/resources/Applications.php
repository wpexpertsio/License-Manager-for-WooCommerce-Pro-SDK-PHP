<?php

namespace LicenseManagerSDK\Resources;

use LicenseManagerSDK\HttpClient;

/**
 * Class Applications
 *
 * Handles application-related REST API endpoints (Pro).
 *
 * Endpoints covered:
 *   - GET /lmfwc/v2/application/{application_id} → get()
 *   - GET /lmfwc/v2/application/{application_id}/download → downloadUrl()
 *
 * @package LicenseManagerSDK\Resources
 */
class Applications {

    /**
     * HTTP client instance.
     *
     * @var HttpClient
     */
    private $http;

    /**
     * Applications constructor.
     *
     * @param HttpClient $http HTTP client.
     */
    public function __construct( HttpClient $http ) {
        $this->http = $http;
    }

    /**
     * Retrieve a single application by its ID (Pro).
     *
     * @param int $application_id The application ID.
     *
     * @return mixed Application data object (name, type, stable_release, description, gallery, etc.).
     * @throws \LicenseManagerSDK\LicenseManagerException
     */
    public function get( $application_id ) {
        return $this->http->get(
            sprintf( 'application/%d', (int) $application_id )
        );
    }

    /**
     * Get the download URL for an application via access token (Pro).
     *
     * The resulting URL is intended for browser-based file download.
     *
     * @param int    $application_id The application ID.
     * @param string $access_token   The access token issued by the plugin.
     *
     * @return string Signed download URL.
     */
    public function downloadUrl( $application_id, $access_token ) {
        return $this->http->buildUrl(
            sprintf( 'application/%d/download', (int) $application_id ),
            array( 'token' => $access_token )
        );
    }
}
