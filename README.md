QuartetStripeBundle
===================

[![Build Status](https://travis-ci.org/quartetcom/QuartetStripeBundle.svg?branch=master)](https://travis-ci.org/quartetcom/QuartetStripeBundle)

# Installation

## Download QuartetStripeBundle using composer

```bash
 $ composer require quartet/stripe-bundle
```

## Enable the bundle

Enable the bundle in the kernel

```php
<?php
// app/Appkernel.php

 public function registerBundles()
 {
    $bundles = [
        new Quartet\StripeBundle\QuartetStripeBundle(),
    ];
 }
 ```

## Configure your stripe application keys

```yaml
# app/config/config.yml

quartet_stripe:
    api_secret: your stripe api secret
    api_public: your stripe api public
    logger: logger_service_id # [optional] to enable http client logging feature
    debug: true or false      # [optional] to enable http client debugging feature (Useful for functional test)
```
