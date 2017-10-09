# Slick Configuration

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

`Slick/Configuration` is a simple package that deals with configuration files. It has a very simple
interface that you can use to set your own configuration drivers. By default it uses the PHP arrays
for configuration as it does not need any parser and therefore is more performance friendly.

This package is compliant with PSR-2 code standards and PSR-4 autoload standards. It
also applies the [semantic version 2.0.0](http://semver.org) specification.

## Install

Via Composer

``` bash
$ composer require slick/configuration
```

## Usage

Lets start by creating a configuration file:

``` php
<?php
/**
 * App configuration file
 */
namespace settings;

$settings = [];
$settings['application'] = [
    'version' => 'v1.0.0',
    'environment' => 'develop'
];
return $settings;
```

we save this file as `./settings.php`. We are using plain PHP arrays for configuration files. Don’t forget to add the `return` statement at the end of the file so that the defined array could be assigned when initializing the driver.

#### Creating a Configuration

Now we will use the `Slick\Configuration\Configuration` factory o create our `Slick\Configuration\ConfigurationInterface`:

``` php
use Slick\Configuration\Configuration;

$settings = Configuration::get('settings');
```

Its really simple.

#### Retrieving values

Now lets use it.

``` php
print_r($settings->get('application'));

# the output form above is:
# Array (
#    [version] => v1.0.0,
#    [environment] => develop
# )
```

You can set any level of nesting in your configuration array but as you add another level to the array it becomes harder to use. Please check the example bellow:

``` php
$value = $settings->get('application')['version'];
// OR
$appSettings = $settings->get('application');
$value = $appSettings['version'];
```

To simplify you ca use a “dot notation” to reach a deeper level.

``` php
$value = $settings->get('application.version');
```

#### Default values

It is possible to have a default value when no key is found on a configuration driver. By default if a key is not found a `NULL` is returned but if you specify a value it will be returned by the `ConfigurationInterface::get()` method:

``` php
$value = $settings->get('application.rowsPerPage', 10);
print $value;

# the output form above is:
# 10
```

Please check [documentation site](http://configuration.slick-framework.com) for a complete reference. 

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email slick.framework@gmail.com instead of using the issue tracker.

## Credits

- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/slick/configuration.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/slickframework/configuration/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/slickframework/configuration.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/slickframework/configuration.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/slick/configuration.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/slick/configuration
[link-travis]: https://travis-ci.org/slickframework/configuration
[link-scrutinizer]: https://scrutinizer-ci.com/g/slickframework/configuration/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/slickframework/configuration
[link-downloads]: https://packagist.org/packages/slickupdated/configuration
[link-contributors]: https://github.com/slickframework/configuration/graphs/contributors