UNLOQ PHP SDK
=========================

Implements methods to easily interact with UNLOQ RESTful API.

## Installation

The SDK can be installed easily with the usage of [Composer](#http://getcomposer.org/). You can install Composer with the following command:
```bash
curl -sS https://getcomposer.org/installer | php
```

**Next**, install UNLOQ PHP SDK by using the following composer command:
```bash
composer require unloq/unloq-php-sdk
```

## Usage

You can find general usage examples in ``docs/`` folder under **general.md**.

We got covered:
- UNLOQ use cases;
- general usage of UNLOQ PHP SDK:
    - initialization;
    - making different types of requests;
    - positive and negative response formats;
    - different errorCodes that might be returned and the causes.
- implementing UNLOQ Authentication
    - requirements;
    - authentication through 'EMAIL' method;
    - authentication through 'UNLOQ' method;
    - authentication through 'OTP' method;
    - errorCodes that might occur and the causes. 
    
A more indepth documentation can be found in **laravel.md**. This discusses implementing a passwordless authentication over the Laravel's authentication. 

## Support and Feedback

Please visit UNLOQ [documentation website](https://docs.unloq.io) for more information.

If you find a bug, please submit the issue in Github directly.

If you require additional assistance, send us a message through the form on our [contact page](https://unloq.io/contact)