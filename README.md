# Omnipay: MobilPay

**MobilPay driver for the Omnipay PHP payment processing library**

[Omnipay](https://github.com/omnipay/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.4+. This package implements [MOBILPAY](http://www.mobilpay.ro) support for Omnipay.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply run:

```
composer require gentor/omnipay-mobilpay
```

## Basic Usage

The following gateways are provided by this package:

* MobilPay

**Initiating payment request**

```php
$gateway = Omnipay::create('MobilPay');
$gateway->setMerchantId('1234-5678-9012-3456-7890');
$gateway->setPublicKey('/path/to/public.cer');

$response = $gateway->purchase([
    'amount'     => '10.00',
    'currency'   => 'RON',
    'orderId'    => 1,
    'confirmUrl' => 'http://example.com/ipn',
    'returnUrl'  => 'http://www.google.com',
    'details'    => 'Test payment',
    'testMode'   => true,
    'params'     => [
        'selected_package' => 1
    ]
])->send();

$response->redirect();
```

**Processing IPN requests**

```php
$gateway = Omnipay::create('MobilPay');
$gateway->privateKeyPath('/path/to/private.key');

$response = $gateway->completePurchase($_POST)->send();
$response->sendResponse();

if ($response->isSuccessful()) {
    return STATUS_COMPLETED;
}

if ($response->isCancelled()) {
    return STATUS_CANCELED;
}

if ($response->isPending()) {
    return STATUS_PENDING;
}

if ($response->isRefunded()) {
    return STATUS_REFUNDED;
}
```

For general usage instructions, please see the main [Omnipay](https://github.com/omnipay/omnipay)
repository.

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/BusinessMastery/omnipay-mobilpay/issues),
or better yet, fork the library and submit a pull request.
