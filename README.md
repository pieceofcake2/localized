# CakePHP 2 Localized Plugin

[![GitHub License](https://img.shields.io/github/license/pieceofcake2/localized?label=License)](LICENSE)
[![Packagist Version](https://img.shields.io/packagist/v/pieceofcake2/localized?label=Packagist)](https://packagist.org/packages/pieceofcake2/localized)
![PHP](https://img.shields.io/packagist/dependency-v/pieceofcake2/localized/php?logo=php&logoColor=%23FFFFFF&label=PHP&labelColor=%23777BB4&color=%23FFFFFF)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%205-brightgreen?label=PHPStan)](https://github.com/pieceofcake2//Users/tsuyoshi/dev/pieceofcake2/localized/actions/workflows/CI.yml)
![CakePHP](https://img.shields.io/packagist/dependency-v/pieceofcake2/localized/pieceofcake2/cakephp?logo=cakephp&logoColor=%23FFFFFF&label=CakePHP&labelColor=%23D33C43&color=%23FFFFFF)
[![CI](https://img.shields.io/github/actions/workflow/status/pieceofcake2/localized/CI.yml?label=CI)](https://github.com/pieceofcake2/localized/actions/workflows/CI.yml)
[![Codecov](https://img.shields.io/codecov/c/gh/pieceofcake2/localized?label=Coverage)](https://codecov.io/gh/pieceofcake2/localized)

This plugin contains localized validation classes for specific countries (postal codes, phone
numbers, person IDs and more), translations of the core's `cake` domain and POSIX LC_TIME files.
This is a fork of [cakephp/localized](https://github.com/cakephp/localized) 2.x for
[pieceofcake2/cakephp](https://github.com/pieceofcake2/cakephp).

## Documentation

- Website: https://pieceofcake2.github.io/localized/ (日本語: https://pieceofcake2.github.io/localized/ja/)
- Markdown: [docs/README.md](docs/README.md) (日本語: [docs/ja/README.md](docs/ja/README.md)) (for AI tools and offline reading)

The Markdown documentation is also included in the Composer package, in the plugin's directory: `plugins/Localized/docs/README.md` (the location depends on your `installer-paths`). Install the package with `--dev` if you only need it for development.

## Requirements

* PHP 8.0+
* pieceofcake2/cakephp 2.12+

## Installation

```bash
composer require pieceofcake2/localized
```

## Reporting Issues

Please open an issue on [GitHub](https://github.com/pieceofcake2/localized/issues).

## Contributing

See the [contribution guidelines](https://github.com/pieceofcake2/.github/blob/master/CONTRIBUTING.md).

## Origin

This package is a maintained fork of [cakephp/localized](https://github.com/cakephp/localized)
(`cakephp/localized` on Packagist, last upstream version 2.7.0), originally by the CakePHP community
(Cake Software Foundation, Inc.). It is maintained for CakePHP 2 on modern PHP by
[pieceofcake2](https://github.com/pieceofcake2). Changes from the original are listed in the
[commit history since the fork](https://github.com/pieceofcake2/localized/compare/26ed36a...2.x).

## License

The MIT License. See [LICENSE](LICENSE).
