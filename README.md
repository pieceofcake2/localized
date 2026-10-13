# CakePHP 2 Localized plugin

[![GitHub License](https://img.shields.io/github/license/pieceofcake2/localized?label=License)](LICENSE)
[![Packagist Version](https://img.shields.io/packagist/v/pieceofcake2/localized?label=Packagist)](https://packagist.org/packages/pieceofcake2/localized)
![PHP](https://img.shields.io/packagist/dependency-v/pieceofcake2/localized/php?logo=php&logoColor=%23FFFFFF&label=PHP&labelColor=%23777BB4&color=%23FFFFFF)
![CakePHP](https://img.shields.io/packagist/dependency-v/pieceofcake2/localized/pieceofcake2/cakephp?logo=cakephp&logoColor=%23FFFFFF&label=CakePHP&labelColor=%23D33C43&color=%23FFFFFF)
[![CI](https://img.shields.io/github/actions/workflow/status/pieceofcake2/localized/CI.yml?label=CI)](https://github.com/pieceofcake2/localized/actions/workflows/CI.yml)
[![Codecov](https://img.shields.io/codecov/c/gh/pieceofcake2/localized?label=Coverage)](https://codecov.io/gh/pieceofcake2/localized)

**This is forked for CakePHP2.**

This plugin contains various localized validation classes for specific countries.

## Requirements

The 2.x branch has the following requirements:

* CakePHP 2.12+ ([pieceofcake2/cakephp](https://github.com/pieceofcake2/cakephp))
* PHP 8.0+

## Installation

Install the plugin with [Composer](https://getcomposer.org/):

```bash
composer require pieceofcake2/localized
```

Then load the plugin in `app/Config/bootstrap.php`:

```php
CakePlugin::load('Localized');
```

## Model validation

Localized validation classes can be used for validating model fields.

```php
<?php
App::uses('MxValidation', 'Localized.Validation');

class Post extends AppModel
{
    public $validate = [
        'postal' => [
            'valid' => [
                'rule' => array('postal', null, 'mx'),
                'message' => 'Must be valid mexico postal code',
            ],
        ],
    ];
}
```

For further information on validation rules see the [cakephp documentation on validation](https://book.cakephp.org/2.0/en/models/data-validation.html)

## Using localized validations with Validation

You can also access the localized validators any time you would call `Validation` methods. After importing the validation class.

```php
if (Validation::postal($value, null, 'cz')) {
    // Do something with valid postal code
}
```

## Dynamic forwarding of validation
Using a snippet like the following could reduce the manual include/setup part when using multiple localizations:
```php
// Inside your custom validation rule validatePostal()
// $country (de, at, ...) and $value (12345, 1234, ...) given
$className = ucfirst($country) . 'Validation';
App::uses($className, 'Localized.Validation');

// Skip if we don't have that country, then only check for existence
if (!class_exists($className) || !method_exists($className, 'postal')) {
    return !empty($value);
}

try {
    $result = Validation::postal($value, null, $country);
} catch (NotImplementedException $e) {
    $result = !empty($value);
}

return $result;
```

## PO files

This plugin also houses translations for the client-facing translated strings in the core (the `cake` domain). to use these files link or copy them
into their expected location: `APP/Locale/<locale>/LC_MESSAGES/cake.po`

## LC_TIME files

This plugin also houses POSIX compliant LC_TIME files which are used for translating
time related string of LC_TIME domain. To use these files link or copy them into
their expected location: `APP/Locale/<locale>/LC_TIME`.

## Migration Guide

### Localized.Validation

The lib path for validation files changed. They should now be either in `APP/Validation/` or in `APP/PluginName/Validation/`.

You also need to adjust your App::uses() statements in your code accordingly:

`App::uses('MxValidation', 'Localized.Lib');` is now `App::uses('MxValidation', 'Localized.Validation');`

## Contributing to Localized

If you find that your country is not part of the Localized plugin, please fork the project on github.  Once you have forked the project you can commit your validator class (and any test cases).  Once you have pushed your changes back to github send a pull request, and your changes will be reviewed and merged in or feedback will be given.

### Validation methods

There are a few methods that are common to all classes, defined through the interface "ValidationInterface":

* `phone()` to check a phone number
* `postal()` to check a postal code
* `personId()` (and `ssn()` for BC) to check a country specific person ID

Please try to fit your validation rules in that naming scheme.
Apart from that you can also define further validation methods in your implementing class, of course.

## Issues with Localized

If you have issues with Localized, you can report them at https://github.com/pieceofcake2/localized/issues
