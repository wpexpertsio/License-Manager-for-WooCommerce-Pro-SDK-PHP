<?php

/**
 * License Manager Pro SDK Autoloader
 *
 * Maps SDK class names to their file paths.
 * Framework-agnostic.
 *
 * @package LicenseManagerSDK
 */

// Define the mapping of fully-qualified class names to file paths.
$lmw_sdk_class_map = array(
    'LicenseManagerSDK\\LicenseManagerClient'       => __DIR__ . '/LicenseManagerClient.php',
    'LicenseManagerSDK\\LicenseManagerException'    => __DIR__ . '/LicenseManagerException.php',
    'LicenseManagerSDK\\HttpClient'                 => __DIR__ . '/HttpClient.php',
    'LicenseManagerSDK\\Resources\\Licenses'        => __DIR__ . '/resources/Licenses.php',
    'LicenseManagerSDK\\Resources\\Generators'      => __DIR__ . '/resources/Generators.php',
    'LicenseManagerSDK\\Resources\\Products'        => __DIR__ . '/resources/Products.php',
    'LicenseManagerSDK\\Resources\\Customers'       => __DIR__ . '/resources/Customers.php',
    'LicenseManagerSDK\\Resources\\Applications'    => __DIR__ . '/resources/Applications.php',
);

spl_autoload_register(
    function ( $class ) use ( $lmw_sdk_class_map ) {
        if ( isset( $lmw_sdk_class_map[ $class ] ) ) {
            require_once $lmw_sdk_class_map[ $class ];
        }
    }
);
