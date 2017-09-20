# Slick Configuration package

[![Latest Version](https://img.shields.io/github/release/slickframework/configuration.svg?style=flat-square)](https://github.com/slickframework/configuration/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/slickframework/configuration/develop.svg?style=flat-square)](https://travis-ci.org/slickframework/configuration)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/slickframework/configuration/develop.svg?style=flat-square)](https://scrutinizer-ci.com/g/slickframework/configuration/code-structure?branch=develop)
[![Quality Score](https://img.shields.io/scrutinizer/g/slickframework/configuration/develop.svg?style=flat-square)](https://scrutinizer-ci.com/g/slickframework/configuration?branch=develop)
[![Total Downloads](https://img.shields.io/packagist/dt/slick/configuration.svg?style=flat-square)](https://packagist.org/packages/slick/configuration)

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

First lets create a configuration file:

```php
return [
    'foo' => [
        'bar' => 'baz'
    ]
];
```

we save this file as `./config.php`.
 
Now all you have to do is to use the `Slick\Configuration\Configuration` factory class to create
your configuration driver object.

```php
use Slick\Configuration\Configuration;

$configuration = Configuration::get('config');
``` 

Its really simple. Now lets use it.

```php
print_r($configuration->get('foo', false));

# this will output
# Array (
#     [bar] => baz
# )
```

To work with a configuration driver you can use the following API:

#### `ConfigurationInterface::get()`
Returns the value store with provided key or the default value.
```php
public mixed get(string $key[, mixed $default = null])
``` 
Parameters      | Type     | Description 
----------------|----------|-------------
 *`$key`*       | `string` | The key used to store the value in configuration.
 *`$default`*   | `mixed`  | Default value if no value was stored.

Return   | Description  
---------|-----------
`mixed`  | The stored value or the default value if key was not found.

---

#### `ConfigurationInterface::set()`
Set/Store the provided value with a given key.
```php
public ConfigurationInterface set(string $key, mixed $value)
``` 
Parameters  | Type     | Description 
------------|----------|-------------
 *`$key`*   | `string` | The key used to store the value in configuration.
 *`$value`* | `mixed`  | The value to store under the provided key.
 
Return                    | Description  
--------------------------|-----------
`ConfigurationInterface`  | Self instance for method call chains.

Lets add another entry to the configuration file above:
```php
return [
    'foo' => [
        'bar' => 'baz',
        'other' => [
            'level' => 'value'
        ]     
    ]
];
```
You can set any level of nesting in your configuration array but as you on
adding another level to the array it becomes harder to use.
```php
$value = $configuration->get('foo')['other']['level'];
// OR
$foo = $configuration->get('foo');
$value = $foo['other']['level'];
```
To simplify you ca use a "dot notation" to rich a deeper level.
```php
$value = $configuration->get('foo.other.level');
```

<br>&nbsp;
#### Other configuration drivers

---

`Slick\Configuration` comes with support for PHP arrays and _ini_ files. To set the
driver type you want to use just add it as a parameter to the factory method:


```php
use Slick\Configuration\Configuration;

$configuration = Configuration::get('config', Configuration::DRIVER_INI);
```

The above code will search for a file called `./config.ini` in the
current working directory and will parse it.

You can also create your own driver and use the `Slick\Configuration\Configuration`
factory to create it:

```php
use Slick\Configuration\Configuration;

$configuration = new Configuration(
    [
        'type' => 'My\Custom\Driver',
        'file' => 'Some/path/to/file.cfg'
    ]
);
$driver = $configuration->initialize();

// Or

$configuration = Configuration::get(
    '/Some/path/to/file.cfg',
    'My\Custom\Driver'
);
```

<br>&nbsp;
#### Configuration files path

By default the configuration factory will look into current working directory (`./`)
for configuration files. It is possible to add other paths to the factory and the
factory will look in those paths. For example:

```php
use Slick\Configuration\Configuration;

Configuration::addPath('/Some/path/to');

$configuration = Configuration::get(
    'file.cfg',
    'My\Custom\Driver'
);
```

The factory will try to load `./file.cfg` and `/Some/path/to/file.cfg`.

## Testing

``` bash
$ vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email silvam.filipe@gmail.com instead of using the issue tracker.

## Credits

- [Slick framework](https://github.com/slickframework)
- [All Contributors](https://github.com/slickframework/configuration/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.