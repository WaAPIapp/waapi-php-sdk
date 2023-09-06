# WaAPI PHP Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/waapi/waapi-php-sdk.svg?style=flat-square)](https://packagist.org/packages/waapi/waapi-php-sdk)
[![Total Downloads](https://img.shields.io/packagist/dt/waapi/waapi-php-sdk.svg?style=flat-square)](https://packagist.org/packages/waapi/waapi-php-sdk)
---


<a name="section-1"></a>

## Introduction

 PHP package to easily interact with [waapi.app](https://waapi.app).


## Installation

### Composer

```shell
composer require waapi/waapi-php-sdk
```

## Usage

### Initial Setup

```php
use WaAPI\WaAPISdk\WhatsAppSdk;

$apiToken = 'xxxxxxxxxxxxxxxxxxxxxx';
$sdk = new WhatsAppSdk($apiToken);
```

### Check if WhatsApp API is available

```php
$isAvailable = $this->sdk->isApiAvailable();
```

### Create a new instance

```php
$instance = $this->sdk->createInstance();
$instanceId = $instance->id;
```

### Get an existing instance

```php
$instanceId = 10; //you need to know your instance id at this point
$instance = $this->sdk->getInstance($instanceId);
```

### Update an existing instance

```php
$instanceId = 10; //you need to know your instance id at this point

//if a subscribed event occurs, this url will be requested with the event data
//can also be null if you do not want to receive webhooks
$webhookUrl = '';
$subscribedEvents = ['', '']; //can also be null or an empty array

$this->sdk->updateInstance($instanceId, $webhookUrl, $subscribedEvents);

//if you have an instance object, you can also use the following method
$instance = $this->sdk->getInstance($instanceId);
$instance->update($webhookUrl, $subscribedEvents);
```

### Delete an existing instance

```php
$instanceId = 10; //you need to know your instance id at this point
$this->sdk->deleteInstance($instanceId);

//if you have an instance object, you can also use the following method to delete this instance
$instance = $this->sdk->getInstance($instanceId);
$instance->delete();
```

### Get the QR-Code

After creating a new instance, you need to connect your WhatsApp phone number with this instance.
[Follow this guide](/docs/1.0/setup-whatsapp-business) if you do not know how to do this.
With the following code, you are able to receive the current QR-Code.

```php
$instanceId = 10; //you need to know your instance id at this point
$response = $this->sdk->getInstanceClientQrCode($instanceId);

//if you have an instance object, you can also use the following method to delete this instance
$instance = $this->sdk->getInstance($instanceId);
$response = $instance->clientQrCode();

// The QR code as a base64 image string (data:image/png;base64,…).
// Set this string in the src attribute of a <img src=““> html tag.
$qrCode = $response->qrCode;
```

### Get the instance status

An instance always has a status.

| Status         | Description                                                                                     |
|:---------------|:------------------------------------------------------------------------------------------------|
| booting        | The node is starting the instance. The status will change to `loading_screen` soon.             |
| loading_screen | The instance is starting WhatsApp Web. The status will change to `qr` or `ready` soon.          |
| qr             | The instance needs to be connected with a WhatsApp phone number. You can fetch the QR code now. |
| authenticated  | The connection via the QR code was successful. The status will change to `ready` soon.          |
| auth_failure   | The connection via the QR code failed or WhatsApp Web is down.                                  |
| ready          | The instance is ready to use. You are able to execute actions and send messages.                |
| disconnected   | Disconnected from WhatsApp Web                                                                  |

```php
$instanceId = 10; //you need to know your instance id at this point
$response = $this->sdk->getInstanceClientStatus($instanceId);

//if you have an instance object, you can also use the following method to delete this instance
$instance = $this->sdk->getInstance($instanceId);
$response = $instance->clientStatus();

$instanceStatus = $response->instanceStatus;
```

### Get information about an existing instance

```php
$instanceId = 10; //you need to know your instance id at this point
$response = $this->sdk->getInstanceClientInfo($instanceId);

//if you have an instance object, you can also use the following method to delete this instance
$instance = $this->sdk->getInstance($instanceId);
$response = $instance->clientInfo();

//your public name of your WhatsApp profile (your name)
$displayName = $response->displayName;

//the connected WhatsApp phone number (your phone number)
$phoneNumber = $response->formattedNumber;

//profile image url of the connected WhatsApp phone number (your profile picture)
$profileUrl = $response->profilePicUrl;

//a unique identifier for your WhatsApp account / profile / phone number
$whatsAppId = $response->contactId;
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

