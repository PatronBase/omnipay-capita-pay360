# Omnipay: Capita Pay360

**Capita Pay360 driver for the Omnipay PHP payment processing library**

[![Build Status](https://travis-ci.org/PatronBase/omnipay-capita-pay360.png?branch=master)](https://travis-ci.org/PatronBase/omnipay-capita-pay360)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/PatronBase/omnipay-capita-pay360.svg?style=flat)](https://scrutinizer-ci.com/g/PatronBase/omnipay-capita-pay360/code-structure)
[![Code Quality](https://img.shields.io/scrutinizer/g/PatronBase/omnipay-capita-pay360.svg?style=flat)](https://scrutinizer-ci.com/g/PatronBase/omnipay-capita-pay360/?branch=master)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat)](LICENSE.md)
[![Latest Stable Version](https://poser.pugx.org/PatronBase/omnipay-capita-pay360/version.png)](https://packagist.org/packages/patronbase/omnipay-capita-pay360)
[![Total Downloads](https://poser.pugx.org/patronbase/omnipay-capita-pay360/d/total.png)](https://packagist.org/packages/patronbase/omnipay-capita-pay360)


[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements Capita Pay360 support for Omnipay. It includes
support for both redirect (3-party) and webservice (2-party) versions of the gateway.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "PatronBase/omnipay-capita-pay360": "~2.0"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Basic Usage

The following gateways are provided by this package:

* CapitaPay360_Redirect

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository.

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/PatronBase/omnipay-capita-pay360/issues),
or better yet, fork the library and submit a pull request.
