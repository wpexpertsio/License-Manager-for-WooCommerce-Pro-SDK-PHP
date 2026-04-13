# 🛡️ License Manager for WooCommerce (Pro) SDK

**The Ultimate PHP Licensing Engine for SaaS, CMS, and Custom Software.**
Stop building custom Licensing APIs. This framework-agnostic SDK is the enterprise-grade bridge between your application and your [License Manager for WooCommerce (Pro)](https://licensemanager.at/pricing/) backend.

<div align="center">

[![SDK Version](https://img.shields.io/badge/Release-v1.0.0-blue.svg?style=for-the-badge)](https://licensemanager.at/)
[![Platform](https://img.shields.io/badge/Platform-Framework%20Agnostic-green.svg?style=for-the-badge)](https://licensemanager.at/)
[![Commercial](https://img.shields.io/badge/Monetization-SaaS%20Ready-gold.svg?style=for-the-badge)](https://licensemanager.at/pricing/)

</div>

---

## 🚀 Accelerate Your Software Monetization
If you are building a SaaS or selling custom software, your focus should be on your product—not building complex REST API integrations for licensing. 

This SDK provides **100% native coverage** of all endpoints in the [License Manager for WooCommerce (Pro)](https://licensemanager.at/pricing/) ecosystem. Integrate robust activation, validation, and update management into your software in minutes.

### 🛡️ Why Choose This SDK?
- **Zero API Manual Coding**: Every endpoint is mapped to a simple PHP method.
- **Framework Agnostic**: Pure PHP cURL implementation. Works perfectly in **Laravel, CodeIgniter, WordPress**, or any custom standalone application.
- **Enterprise Ready**: Designed to handle high-volume license generation and multi-tier SaaS fulfillment.
- **Data Sovereignty**: Keep 100% of your customer data and revenue. No third-party commissions.

---

## 💎 Professional API Support Matrix
This SDK provides **100% native coverage** for every endpoint in the [License Manager for WooCommerce (Pro)](https://licensemanager.at/pricing/) ecosystem. No more manual cURL requests.

### 🔑 core Licensing
| Feature | SDK Method | API Endpoint |
| :--- | :--- | :--- |
| **List Licenses** | `$client->licenses()->list()` | `GET /licenses` |
| **Retrieve License** | `$client->licenses()->get($key)` | `GET /licenses/{key}` |
| **Create License** | `$client->licenses()->create($data)` | `POST /licenses` |
| **Update License** | `$client->licenses()->update($key, $data)` | `PUT /licenses/{key}` |
| **Delete License** | `$client->licenses()->delete($key)` | `DELETE /licenses/{key}` |
| **Activate License** | `$client->licenses()->activate($key)` | `GET /activate/{key}` |
| **Reactivate License** | `$client->licenses()->reactivate($key, $token)` | `GET /activate/{key}` |
| **Deactivate License** | `$client->licenses()->deactivate($key)` | `GET /deactivate/{key}` |
| **Validate License** | `$client->licenses()->validate($key)` | `GET /validate/{key}` |

### ⚙️ License Generators
| Feature | SDK Method | API Endpoint |
| :--- | :--- | :--- |
| **List Generators** | `$client->generators()->list()` | `GET /generators` |
| **Retrieve Generator** | `$client->generators()->get($id)` | `GET /generators/{id}` |
| **Create Generator** | `$client->generators()->create($data)` | `POST /generators` |
| **Update Generator** | `$client->generators()->update($id, $data)` | `PUT /generators/{id}` |
| **Delete Generator** | `$client->generators()->delete($id)` | `DELETE /generators/{id}` |
| **Generate Keys (Pro)** | `$client->generators()->generate($id, $data)` | `POST /generators/{id}/generate` |

### 🚀 Merchant & SaaS (Pro)
| Feature | SDK Method | API Endpoint |
| :--- | :--- | :--- |
| **Customer Licenses** | `$client->customers()->getLicenses($id)` | `GET /customers/{id}/licenses` |
| **Products/Ping** | `$client->products()->ping($data)` | `POST /products/ping` |
| **Products/Update** | `$client->products()->update($key)` | `GET /products/update/{key}` |
| **Download Products** | `$client->products()->downloadUrl($key)` | `GET /products/download/latest/{key}` |
| **Retrieve Application** | `$client->applications()->get($id)` | `GET /application/{id}` |
| **Download Application** | `$client->applications()->downloadUrl($id, $token)` | `GET /application/{id}/download` |

---

## 💎 The Revenue Model: Free SDK + Pro Engine
The SDK is your "Client Side" tool to be bundled with your software. To serve as the merchant backend, you **must** have the Pro Engine.

| Phase | **Client SDK (This Free Repo)** | **Pro Engine (Your WooCommerce Store)** |
| :--- | :--- | :--- |
| **Purpose** | Connects your software to your store. | Handles orders, payments, and keys. |
| **Availability** | Free for all developers. | **[Purchase Required Here](https://licensemanager.at/pricing/)** |

---

## 📦 3-Minute Developer Quick Start

### Step 1: Upload the SDK
Copy the `lmw-client-sdk` folder into your project's `vendor` directory. Your file structure should look like this:

```
your-software/
├── vendor/
│   └── lmw-client-sdk/
│       ├── lmw-sdk.php (Entry File)
│       ├── src/       (Core Logic)
│       └── images/    (Assets)
├── includes/
└── your-main-file.php
```

### Step 2: Initialization
Require the entry point and initialize the client:

```php
require_once __DIR__ . '/vendor/lmw-client-sdk/lmw-sdk.php';

$client = lmw_sdk_init([
    'store_url'       => 'https://your-store.com',
    'consumer_key'    => 'ck_xxxxxxxxxxxx',
    'consumer_secret' => 'cs_xxxxxxxxxxxx',
]);
```

### 3. Basic Activation Pattern
```php
try {
    $result = $client->licenses()->activate( 'ABCD-1234-EFGH-5678' );
    // Store $result->activationData->token for future validation
} catch ( \Exception $e ) {
    echo "Error: " . $e->getMessage();
}
```

---

## 🏗️ Framework Integration

### Laravel
Use the SDK in your Service Layer or Controllers. Store credentials in `.env` and initialize the `LicenseManagerClient` using the native autoloader.

### CodeIgniter / Standalone
Place the folder in your `ThirdParty` or `Libraries` directory and require `lmw-sdk.php`.

---

## 🚀 Get the Pro Merchant Engine Today

Unlock 100% of the SDK's power by upgrading to the Pro edition of License Manager for WooCommerce.

<p align="center">
  <a href="https://licensemanager.at/pricing/">
    <img src="https://img.shields.io/badge/UPGRADE_TO_PRO_BACKEND-7e22ce?style=for-the-badge&logo=wordpress" alt="Buy License Manager for WooCommerce Pro" height="60">
  </a>
</p>

<p align="center">
  <a href="https://licensemanager.at/">Live Demo</a> • 
  <a href="https://licensemanager.at/docs/">Official Documentation</a> • 
  <a href="https://licensemanager.at/contact/">Premium Support</a>
</p>

---
**Standard for Modern Software Monetization.** Pure PHP. 0% Commission. Total Control.
