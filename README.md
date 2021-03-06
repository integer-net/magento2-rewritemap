# IntegerNet_RewriteMap Magento Module
<div align="center">

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
![Supported Magento Versions][ico-compatibility]

[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Maintainability][ico-maintainability]][link-maintainability]
</div>

---

Generate RewriteMap files for Apache from custom Magento URL rewrites (redirects). This can be useful to re-use the rewrites in a different frontend.

For example, to use the redirects in **Vue Storefront**, a reverse proxy that uses the generated rewrite maps can be configured.  

## Installation

1. Install it into your Magento 2 project with composer:
    ```
    composer require integer-net/magento2-rewritemap
    ```

2. Enable module
    ```
    bin/magento setup:upgrade
    ```

## Configuration

In your store configuration navigate to *Catalog > SEO*:

- **Enable Rewrite Maps Generation**: set to "yes" to enable (Default: no)
- **Rewrite Maps Generation Cron Schedule**: configure, when rewrite maps are regenerated (Default: every hour)

## Usage

Rewrite maps are stored in `var/rewrite_maps` as one text file per store and redirect type (301, 302).

See http://httpd.apache.org/docs/current/rewrite/rewritemap.html for details how to use those files.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Testing

### Unit Tests

```
vendor/bin/phpunit tests/unit
```

### Magento Integration Tests

0. Configure test database in `dev/tests/integration/etc/install-config-mysql.php`. [Read more in the Magento docs.](https://devdocs.magento.com/guides/v2.4/test/integration/integration_test_execution.html) 

1. Copy `tests/integration/phpunit.xml.dist` from the package to `dev/tests/integration/phpunit.xml` in your Magento installation.

2. In that directory, run
    ``` bash
    ../../../vendor/bin/phpunit
    ```


## Security

If you discover any security related issues, please email bd@integer-net.de instead of using the issue tracker.

## Credits

- [Bernard Delhez][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

[ico-version]: https://img.shields.io/packagist/v/integer-net/magento2-rewritemap.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/integer-net/magento2-rewritemap/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/integer-net/magento2-rewritemap?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/integer-net/magento2-rewritemap.svg?style=flat-square
[ico-maintainability]: https://img.shields.io/codeclimate/maintainability/integer-net/magento2-rewritemap?style=flat-square
[ico-compatibility]: https://img.shields.io/badge/magento-%202.3%20|%202.4-brightgreen.svg?logo=magento&longCache=true&style=flat-square

[link-packagist]: https://packagist.org/packages/integer-net/magento2-rewritemap
[link-travis]: https://travis-ci.org/integer-net/magento2-rewritemap
[link-scrutinizer]: https://scrutinizer-ci.com/g/integer-net/magento2-rewritemap/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/integer-net/magento2-rewritemap
[link-maintainability]: https://codeclimate.com/github/integer-net/magento2-rewritemap
[link-author]: https://github.com/bernarddelhez
[link-contributors]: ../../contributors
