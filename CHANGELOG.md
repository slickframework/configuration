# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [v2.1.0] - 2024-08-07
### Added
- Code quality verification with phpmd and phpstan
### Fixed
- Configuration chain fails to retrieve nested data issue #3
### Changed
- `phpspec` upgrade to work with PHP >= 8.2
### Removed
- Support for PHP <= 8.1
- Documentation on [Read the docs](https://readthedocs.org/) at [Configuration documentation](http://configuration.slick-framework.com)

## [v2.0.1] - 2022-04-04
### Added
- Support for PHP 8.X
### Removes
- Support for PHP < 8
### Fixes
- [Issue #3](https://github.com/slickframework/configuration/issues/3): Configuration chain fails to retrieve nested data

## [v1.2.2] - 2018-10-11
### Added
- New `Configuration::create()` factory method that will always create a new instance
  of `ConfigurationInterface`.
### Changed
- The method `Configuration::create()` acts as a _singleton_ returning the instance created
  when it was first call. 

## [v1.2.1] - 2018-01-11
### Fixed
- File validation bug when working with phar archives.

## [v1.2.0] - 2017-10-09
### Added
- New `Slick\Configuration\PriorityConfigurationChain`
- Environment variables driver: read from environment configuration
- New `ConfigurationChainInterface` to combine multiple configuration drivers
- Use PHPSpec for unit tests 
- Code of conduct
- Issue template
- Pull request template
- Documentation on [Read the docs](https://readthedocs.org/) at [Configuration documentation](http://configuration.slick-framework.com)

### Changed
- `Slick\Configuration::get()` method now returns a `Slick\Configuration\PriorityConfigurationChain`

### Removed
- The deprecated `Slick\Driver\DriverInterface`
- Behat feature tests
- PHPUnit for unit tests, it was replaced by PHPSpec
- Dependency on `slick/common`

## [v1.1.0] - 2015-08-28
### Added
- File structure for PSR-4 autoload
- Added PHP array driver

## v1.0.0 - 2014-06-04 
### Added
- First release of Slick Configuration!

[Unreleased]: https://github.com/slickframework/configuration/compare/v2.1.0...HEAD
[v2.1.0]: https://github.com/slickframework/configuration/compare/v2.0.1...v2.1.0
[v2.0.1]: https://github.com/slickframework/configuration/compare/v1.2.2...v2.0.1
[v1.2.2]: https://github.com/slickframework/configuration/compare/v1.2.1...v1.2.2
[v1.2.1]: https://github.com/slickframework/configuration/compare/v1.2.0...v1.2.1
[v1.2.0]: https://github.com/slickframework/configuration/compare/v1.1.0...v1.2.0
[v1.1.0]: https://github.com/slickframework/configuration/compare/v1.0.0...v1.1.0
