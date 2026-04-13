<?php

namespace LicenseManagerSDK;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class LicenseManagerException
 *
 * Custom exception class for the License Manager SDK.
 *
 * @package LicenseManagerSDK
 */
class LicenseManagerException extends \Exception {

    /**
     * API error code from the response.
     *
     * @var string|null
     */
    protected $api_error_code;

    /**
     * API error message from the response.
     *
     * @var string|null
     */
    protected $api_error_message;

    /**
     * Raw API response data.
     *
     * @var mixed
     */
    protected $response_data;

    /**
     * LicenseManagerException constructor.
     *
     * @param string     $message           Human-readable error message.
     * @param string     $api_error_code    Error code from the API.
     * @param string     $api_error_message Error message from the API.
     * @param mixed      $response_data     Raw response data.
     * @param int        $code              Exception code.
     * @param \Throwable $previous          Previous exception.
     */
    public function __construct(
        $message = '',
        $api_error_code = '',
        $api_error_message = '',
        $response_data = null,
        $code = 0,
        \Throwable $previous = null
    ) {
        $this->api_error_code    = $api_error_code;
        $this->api_error_message = $api_error_message;
        $this->response_data     = $response_data;

        parent::__construct( $message, $code, $previous );
    }

    /**
     * Get the API error code.
     *
     * @return string
     */
    public function getApiErrorCode() {
        return $this->api_error_code;
    }

    /**
     * Get the API error message.
     *
     * @return string
     */
    public function getApiErrorMessage() {
        return $this->api_error_message;
    }

    /**
     * Get the raw response data.
     *
     * @return mixed
     */
    public function getResponseData() {
        return $this->response_data;
    }

    /**
     * String representation.
     *
     * @return string
     */
    public function __toString() {
        return sprintf(
            '[%s] %s: %s (Code: %s)',
            __CLASS__,
            $this->getMessage(),
            $this->api_error_message,
            $this->api_error_code
        );
    }
}
