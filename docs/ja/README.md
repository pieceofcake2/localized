# Localized ドキュメント

このプラグインには、国ごとにローカライズされたバリデーションクラス、コアのクライアント向け文字列
(`cake` ドメイン) の翻訳、POSIX の LC_TIME ファイルが含まれています。

- [インストール](#installation)
- [バリデーションクラス](#validation-classes)
- [モデルのバリデーション](#model-validation)
- [Validation でローカライズされたバリデーションを使う](#using-localized-validations-with-validation)
- [バリデーションの動的な振り分け](#dynamic-forwarding-of-validation)
- [PO ファイル](#po-files)
- [LC_TIME ファイル](#lc_time-files)
- [移行ガイド](#migration-guide)
- [Localized への貢献](#contributing-to-localized)

<a id="installation"></a>

## インストール

[Composer](https://getcomposer.org/) でプラグインをインストールします。

```bash
composer require pieceofcake2/localized
```

このプラグインのタイプは `cakephp-plugin` なので、`composer/installers` によってプラグインディレクトリに
(`Localized` として) インストールされます。正しい場所にインストールされるように、アプリケーションの
`composer.json` で `installer-paths` を設定してください。

| レイアウト | `installer-paths` |
| --- | --- |
| モダン (`config/`, `src/`, `templates/`, `plugins/`) | `"plugins/{$name}/": ["type:cakephp-plugin"]` |
| レガシー (`app/Config/`, `app/Plugin/`) | `"app/Plugin/{$name}/": ["type:cakephp-plugin"]` |

次に、`config/bootstrap.php` (レガシーレイアウトでは `app/Config/bootstrap.php`) でプラグインを
読み込みます。

```php
CakePlugin::load('Localized');
```

<a id="validation-classes"></a>

## バリデーションクラス

すべてのクラスは `phone()`、`postal()`、`personId()` を実装しています
([バリデーションメソッド](#validation-methods) をご覧ください)。追加のメソッドを持つクラスもあります。

| 国コード | 国 | クラス | 追加のメソッド |
| --- | --- | --- | --- |
| `at` | オーストリア | `AtValidation` |  |
| `au` | オーストラリア | `AuValidation` |  |
| `bd` | バングラデシュ | `BdValidation` |  |
| `be` | ベルギー | `BeValidation` |  |
| `br` | ブラジル | `BrValidation` | `cpf()`, `cnpj()` |
| `ca` | カナダ | `CaValidation` |  |
| `ch` | スイス | `ChValidation` |  |
| `cn` | 中国 (本土) | `CnValidation` |  |
| `cz` | チェコ | `CzValidation` |  |
| `de` | ドイツ | `DeValidation` | `address1()`, `dob()` |
| `dk` | デンマーク | `DkValidation` |  |
| `es` | スペイン | `EsValidation` |  |
| `fr` | フランス | `FrValidation` |  |
| `gb` | イギリス | `GbValidation` |  |
| `hr` | クロアチア | `HrValidation` |  |
| `id` | インドネシア | `IdValidation` | `mobile()` |
| `in` | インド | `InValidation` |  |
| `ir` | イラン | `IrValidation` | `alphaNumeric()`, `numeric()`, `cc()`, `mobile()` |
| `it` | イタリア | `ItValidation` | `cf()` |
| `jp` | 日本 | `JpValidation` | `hiragana()`, `katakana()`, `zenkaku()` |
| `lt` | リトアニア | `LtValidation` |  |
| `mx` | メキシコ | `MxValidation` |  |
| `nl` | オランダ | `NlValidation` |  |
| `no` | ノルウェー | `NoValidation` | `dob()` |
| `pl` | ポーランド | `PlValidation` | `pesel()`, `regon()` |
| `pt` | ポルトガル | `PtValidation` |  |
| `ro` | ルーマニア | `RoValidation` |  |
| `rs` | セルビア | `RsValidation` | `addressCode()`, `jmbg()` |
| `ru` | ロシア | `RuValidation` | `address1()`, `passport()`, `vatin()`, `snils()` |
| `sk` | スロバキア | `SkValidation` |  |
| `tr` | トルコ | `TrValidation` | `trIdentityNumber()` |
| `tw` | 台湾 | `TwValidation` | `ubn()`, `nicn()` |
| `uk` | イギリス (非推奨。`gb` を使ってください) | `UkValidation` |  |
| `us` | アメリカ合衆国 | `UsValidation` |  |

<a id="model-validation"></a>

## モデルのバリデーション

ローカライズされたバリデーションクラスは、モデルのフィールドのバリデーションに使えます。

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

バリデーションルールについて詳しくは、クックブックの
[データバリデーション](https://pieceofcake2.github.io/docs/ja/models/data-validation.html)
をご覧ください。

<a id="using-localized-validations-with-validation"></a>

## Validation でローカライズされたバリデーションを使う

バリデーションクラスをインポートすれば、`Validation` のメソッドを呼ぶところならどこでも、ローカライズ
されたバリデーターを使えます。

```php
App::uses('CzValidation', 'Localized.Validation');

if (Validation::postal($value, null, 'cz')) {
    // Do something with valid postal code
}
```

<a id="dynamic-forwarding-of-validation"></a>

## バリデーションの動的な振り分け

複数のローカライズを使う場合は、次のようなコードで、クラスを手動で読み込んで準備する手間を減らせます。

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

<a id="po-files"></a>

## PO ファイル

このプラグインには、コアのクライアント向けの翻訳文字列 (`cake` ドメイン) の翻訳も含まれています。
これらのファイルを使うには、想定された場所
`resources/locales/<locale>/LC_MESSAGES/cake.po` (レガシーレイアウトでは
`app/Locale/<locale>/LC_MESSAGES/cake.po`) にリンクするかコピーしてください。

<a id="lc_time-files"></a>

## LC_TIME ファイル

このプラグインには、LC_TIME ドメインの時刻に関する文字列の翻訳に使われる、POSIX 準拠の LC_TIME
ファイルも含まれています。これらのファイルを使うには、想定された場所
`resources/locales/<locale>/LC_TIME` (レガシーレイアウトでは `app/Locale/<locale>/LC_TIME`)
にリンクするかコピーしてください。

<a id="migration-guide"></a>

## 移行ガイド

<a id="localizedvalidation"></a>

### Localized.Validation

バリデーションファイルの lib パスが変わりました。`APP/Validation/` か `APP/PluginName/Validation/` の
どちらかに置く必要があります。

それに合わせて `App::uses()` 文も変更する必要があります。
`App::uses('MxValidation', 'Localized.Lib');` は
`App::uses('MxValidation', 'Localized.Validation');` になります。

<a id="contributing-to-localized"></a>

## Localized への貢献

自分の国が Localized プラグインにない場合は、GitHub でプロジェクトをフォークし、バリデーターのクラス
(とそのテストケース) をコミットして、プルリクエストを送ってください。
[コントリビューションガイドライン](https://github.com/pieceofcake2/.github/blob/master/CONTRIBUTING.md)
をご覧ください。

<a id="validation-methods"></a>

### バリデーションメソッド

すべてのクラスに共通のメソッドがいくつかあり、インターフェース `ValidationInterface` で定義されています。

* `phone()`: 電話番号をチェックします
* `postal()`: 郵便番号をチェックします
* `personId()` (後方互換性のための `ssn()` も): 国ごとの個人識別番号をチェックします

バリデーションルールは、できるだけこの命名規則に合わせてください。もちろん、それとは別に、実装クラスに
さらにバリデーションメソッドを定義することもできます。

<a id="origin"></a>

## 由来

このパッケージは [cakephp/localized](https://github.com/cakephp/localized)
(Packagist では `cakephp/localized`、アップストリームの最終バージョンは 2.7.0)
のメンテナンスされたフォークで、元は CakePHP コミュニティ (Cake Software Foundation, Inc.)
によって作られました。
[pieceofcake2](https://github.com/pieceofcake2) が、モダンな PHP 上の CakePHP 2 向けにメンテナンスしています。
オリジナルからの変更点は
[フォーク以降のコミット履歴](https://github.com/pieceofcake2/localized/compare/26ed36a...2.x)
にあります。
