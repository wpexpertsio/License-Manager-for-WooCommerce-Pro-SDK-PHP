<?php

namespace LicenseManagerSDK;

/**
 * Class HttpClient
 *
 * Framework-agnostic HTTP client for the License Manager SDK.
 * Uses native PHP cURL.
 *
 * @package LicenseManagerSDK
 */
class HttpClient {

    /**
     * Base URL of the WooCommerce store.
     *
     * @var string
     */
    private $store_url;

    /**
     * WooCommerce REST API Consumer Key.
     *
     * @var string
     */
    private $consumer_key;

    /**
     * WooCommerce REST API Consumer Secret.
     *
     * @var string
     */
    private $consumer_secret;

    /**
     * API namespace + version, e.g. "lmfwc/v2".
     *
     * @var string
     */
    private $api_namespace;

    /**
     * Request timeout in seconds.
     *
     * @var int
     */
    private $timeout;

    /**
     * HttpClient constructor.
     *
     * @param string $store_url       Base URL of the store.
     * @param string $consumer_key    WooCommerce REST API consumer key.
     * @param string $consumer_secret WooCommerce REST API consumer secret.
     * @param string $api_namespace   API namespace (default: 'lmfwc/v2').
     * @param int    $timeout         Request timeout in seconds (default: 30).
     */
    public function __construct(
        $store_url,
        $consumer_key,
        $consumer_secret,
        $api_namespace = 'lmfwc/v2',
        $timeout = 30
    ) {
        $this->store_url       = rtrim( $store_url, '/' );
        $this->consumer_key    = $consumer_key;
        $this->consumer_secret = $consumer_secret;
        $this->api_namespace   = trim( $api_namespace, '/' );
        $this->timeout         = $timeout;
    }

    /**
     * Build the full API endpoint URL.
     *
     * @param string $endpoint Relative endpoint path.
     * @param array  $params   Query parameters to append.
     *
     * @return string
     */
    public function buildUrl( $endpoint, $params = array() ) {
        $url = sprintf(
            '%s/wp-json/%s/%s',
            $this->store_url,
            $this->api_namespace,
            ltrim( $endpoint, '/' )
        );

        // Append consumer key and secret for authentication.
        $auth_params = array(
            'consumer_key'    => $this->consumer_key,
            'consumer_secret' => $this->consumer_secret,
        );

        $all_params = array_merge( $auth_params, $params );

        if ( ! empty( $all_params ) ) {
            $url .= '?' . http_build_query( $all_params );
        }

        return $url;
    }

    /**
     * Make a GET request.
     *
     * @param string $endpoint Relative API endpoint.
     * @param array  $params   Query string parameters.
     *
     * @return mixed Decoded response data.
     * @throws LicenseManagerException
     */
    public function get( $endpoint, $params = array() ) {
        return $this->request( 'GET', $endpoint, $params );
    }

    /**
     * Make a POST request.
     *
     * @param string $endpoint Relative API endpoint.
     * @param array  $body     Request body data.
     *
     * @return mixed Decoded response data.
     * @throws LicenseManagerException
     */
    public function post( $endpoint, $body = array() ) {
        return $this->request( 'POST', $endpoint, array(), $body );
    }

    /**
     * Make a PUT request.
     *
     * @param string $endpoint Relative API endpoint.
     * @param array  $body     Request body data.
     *
     * @return mixed Decoded response data.
     * @throws LicenseManagerException
     */
    public function put( $endpoint, $body = array() ) {
        return $this->request( 'PUT', $endpoint, array(), $body );
    }

    /**
     * Make a DELETE request.
     *
     * @param string $endpoint Relative API endpoint.
     *
     * @return mixed Decoded response data.
     * @throws LicenseManagerException
     */
    public function delete( $endpoint ) {
        return $this->request( 'DELETE', $endpoint );
    }

    /**
     * Core request method using cURL.
     *
     * @param string $method   HTTP method.
     * @param string $endpoint Relative endpoint.
     * @param array  $params   Query parameters.
     * @param array  $body     Request body data.
     *
     * @return mixed
     * @throws LicenseManagerException
     */
    private function request( $method, $endpoint, $params = array(), $body = array() ) {
        $url = $this->buildUrl( $endpoint, $params );
        $ch  = curl_init();

        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_TIMEOUT, $this->timeout );
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, $method );

        $headers = array(
            'Content-Type: application/json',
            'Accept: application/json',
        );

        if ( ! empty( $body ) ) {
            curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $body ) );
        }

        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );

        $response  = curl_exec( $ch );
        $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
        $error     = curl_error( $ch );

        curl_close( $ch );

        if ( false === $response ) {
            throw new LicenseManagerException(
                sprintf( 'cURL Error: %s', $error ),
                'curl_error'
            );
        }

        return $this->handleResponse( $response, $http_code );
    }

    /**
     * Handle the raw JSON response.
     *
     * @param string $response  Raw JSON string.
     * @param int    $http_code HTTP status code.
     *
     * @return mixed Decoded response data.
     * @throws LicenseManagerException
     */
    private function handleResponse( $response, $http_code ) {
        $decoded = json_decode( $response );

        // Handle invalid JSON (e.g. server returns HTML error page)
        if ( json_last_error() !== JSON_ERROR_NONE ) {
            throw new LicenseManagerException(
                sprintf( 'Invalid response from API (Status %d). Expected JSON, received: %s', $http_code, substr( $response, 0, 100 ) ),
                'invalid_json',
                null,
                null,
                $http_code
            );
        }

        // Check for non-2xx HTTP status codes.
        if ( $http_code < 200 || $http_code >= 300 ) {
            $error_code    = isset( $decoded->code )    ? $decoded->code    : 'http_error';
            $error_message = isset( $decoded->message ) ? $decoded->message : 'Unknown HTTP error.';

            throw new LicenseManagerException(
                sprintf( 'API request failed with HTTP %d.', $http_code ),
                $error_code,
                $error_message,
                $decoded,
                $http_code
            );
        }

        // Check for API-level failure in the body.
        if ( isset( $decoded->success ) && false === $decoded->success ) {
            $error_code    = isset( $decoded->data->code )    ? $decoded->data->code    : 'api_error';
            $error_message = isset( $decoded->data->message ) ? $decoded->data->message : 'Unknown API error.';

            throw new LicenseManagerException(
                'API returned success=false.',
                $error_code,
                $error_message,
                $decoded,
                $http_code
            );
        }

        return isset( $decoded->data ) ? $decoded->data : $decoded;
    }
}
