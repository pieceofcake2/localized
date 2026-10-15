# Localized Documentation

This plugin contains localized validation classes for specific countries, translations of the
core's client-facing strings (the `cake` domain) and POSIX LC_TIME files.

- [Installation](#installation)
- [Validation Classes](#validation-classes)
- [Model Validation](#model-validation)
- [Using Localized Validations with Validation](#using-localized-validations-with-validation)
- [Dynamic Forwarding of Validation](#dynamic-forwarding-of-validation)
- [PO Files](#po-files)
- [LC_TIME Files](#lc_time-files)
- [Migration Guide](#migration-guide)
- [Contributing to Localized](#contributing-to-localized)

## Installation

Install the plugin with [Composer](https://getcomposer.org/):

```bash
composer require pieceofcake2/localized
```

The plugin has the `cakephp-plugin` type, so it is installed into your plugins directory
(as `Localized`) by `composer/installers`. Set `installer-paths` in your application's
`composer.json` so it is installed into the right place:

| Layout | `installer-paths` |
| --- | --- |
| Modern (`config/`, `src/`, `templates/`, `plugins/`) | `"plugins/{$name}/": ["type:cakephp-plugin"]` |
| Legacy (`app/Config/`, `app/Plugin/`) | `"app/Plugin/{$name}/": ["type:cakephp-plugin"]` |

Then load the plugin in `config/bootstrap.php` (legacy layout: `app/Config/bootstrap.php`):

```php
CakePlugin::load('Localized');
```

## Validation Classes

Every class implements `phone()`, `postal()` and `personId()` (see
[Validation methods](#validation-methods)); some classes have additional methods.

| Country code | Country | Class | Additional methods |
| --- | --- | --- | --- |
| `at` | Austria | `AtValidation` |  |
| `au` | Australia | `AuValidation` |  |
| `bd` | Bangladesh | `BdValidation` |  |
| `be` | Belgium | `BeValidation` |  |
| `br` | Brazil | `BrValidation` | `cpf()`, `cnpj()` |
| `ca` | Canada | `CaValidation` |  |
| `ch` | Switzerland | `ChValidation` |  |
| `cn` | China (mainland) | `CnValidation` |  |
| `cz` | Czech Republic | `CzValidation` |  |
| `de` | Germany | `DeValidation` | `address1()`, `dob()` |
| `dk` | Denmark | `DkValidation` |  |
| `es` | Spain | `EsValidation` |  |
| `fr` | France | `FrValidation` |  |
| `gb` | United Kingdom | `GbValidation` |  |
| `hr` | Croatia | `HrValidation` |  |
| `id` | Indonesia | `IdValidation` | `mobile()` |
| `in` | India | `InValidation` |  |
| `ir` | Iran | `IrValidation` | `alphaNumeric()`, `numeric()`, `cc()`, `mobile()` |
| `it` | Italy | `ItValidation` | `cf()` |
| `jp` | Japan | `JpValidation` | `hiragana()`, `katakana()`, `zenkaku()` |
| `lt` | Lithuania | `LtValidation` |  |
| `mx` | Mexico | `MxValidation` |  |
| `nl` | Netherlands | `NlValidation` |  |
| `no` | Norway | `NoValidation` | `dob()` |
| `pl` | Poland | `PlValidation` | `pesel()`, `regon()` |
| `pt` | Portugal | `PtValidation` |  |
| `ro` | Romania | `RoValidation` |  |
| `rs` | Serbia | `RsValidation` | `addressCode()`, `jmbg()` |
| `ru` | Russia | `RuValidation` | `address1()`, `passport()`, `vatin()`, `snils()` |
| `sk` | Slovakia | `SkValidation` |  |
| `tr` | Turkey | `TrValidation` | `trIdentityNumber()` |
| `tw` | Taiwan | `TwValidation` | `ubn()`, `nicn()` |
| `uk` | United Kingdom (deprecated, use `gb`) | `UkValidation` |  |
| `us` | United States | `UsValidation` |  |

## Model Validation

Localized validation classes can be used for validating model fields.

```php
App::uses('MxValidation', 'Localized.Validation');

class Post extends AppModel
{
    public $validate = [
        'postal' => [
            'valid' => [
                'rule' => ['postal', null, 'mx'],
                'message' => 'Must be valid mexico postal code',
            ],
        ],
    ];
}
```

For further information on validation rules see
[Data Validation](https://pieceofcake2.github.io/docs/models/data-validation.html) in the book.

## Using Localized Validations with Validation

You can also access the localized validators any time you would call `Validation` methods,
after importing the validation class.

```php
App::uses('CzValidation', 'Localized.Validation');

if (Validation::postal($value, null, 'cz')) {
    // Do something with valid postal code
}
```

## Dynamic Forwarding of Validation

A snippet like the following reduces the manual include and setup when using multiple
localizations:

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

## PO Files

This plugin also houses translations for the client-facing translated strings in the core (the
`cake` domain). To use these files, link or copy them into their expected location:
`resources/locales/<locale>/LC_MESSAGES/cake.po` (legacy layout:
`app/Locale/<locale>/LC_MESSAGES/cake.po`).

## LC_TIME Files

This plugin also houses POSIX compliant LC_TIME files, which are used for translating time
related strings of the LC_TIME domain. To use these files, link or copy them into their
expected location: `resources/locales/<locale>/LC_TIME` (legacy layout:
`app/Locale/<locale>/LC_TIME`).

## Migration Guide

### Localized.Validation

The lib path for validation files changed. They should now be either in `APP/Validation/` or
in `APP/PluginName/Validation/`.

You also need to adjust your `App::uses()` statements accordingly:
`App::uses('MxValidation', 'Localized.Lib');` is now
`App::uses('MxValidation', 'Localized.Validation');`.

## Contributing to Localized

If you find that your country is not part of the Localized plugin, please fork the project on
GitHub, commit your validator class (and its test cases) and send a pull request. See the
[contribution guidelines](https://github.com/pieceofcake2/.github/blob/master/CONTRIBUTING.md).

### Validation methods

There are a few methods that are common to all classes, defined through the interface
`ValidationInterface`:

* `phone()` to check a phone number
* `postal()` to check a postal code
* `personId()` (and `ssn()` for BC) to check a country specific person ID

Please try to fit your validation rules in that naming scheme. Apart from that you can also
define further validation methods in your implementing class, of course.
