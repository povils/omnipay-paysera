# Omnipay: Paysera

**Paysera gateway driver for the Omnipay PHP payment processing library**

[![Build Status](https://travis-ci.org/povils/omnipay-paysera.svg?branch=master)](https://travis-ci.org/povils/omnipay-paysera)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/povils/omnipay-paysera/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/povils/omnipay-paysera/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/povils/omnipay-paysera/badge.svg?branch=master)](https://coveralls.io/github/povils/omnipay-paysera?branch=master)

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements [Paysera](https://www.paysera.com/) support for Omnipay.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "povils/omnipay-paysera": "~1.0"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Basic Usage

The following gateways are provided by this package:

* Paysera

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository.

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release announcements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/povils/omnipay-paysera/issues),
or better yet, fork the library and submit a pull request.