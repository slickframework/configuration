# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

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

[Unreleased]: https://github.com/slickframework/configuration/compare/v1.2.1...HEAD
[v1.2.1]: https://github.com/slickframework/configuration/compare/v1.2.0...v1.2.1
[v1.2.0]: https://github.com/slickframework/configuration/compare/v1.1.0...v1.2.0
[v1.1.0]: https://github.com/slickframework/configuration/compare/v1.0.0...v1.1.0
