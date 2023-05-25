<!--delete-->
<p align="center">
    <a href="https://sylius.com" target="_blank">
        <img src="https://demo.sylius.com/assets/shop/img/logo.png" />
    </a>
</p>
<h1 align="center">Plugin Skeleton</h1>

<p align="center">Skeleton for starting Sylius plugins.</p>

---
This repo can be used to scaffold a Sylius plugin. Follow these steps to get started:

1. Press the "Use this template" button at the top of this repo to create a new repo with the contents of this skeleton.
2. Run `php ./configure.php` to run a script that will replace all placeholders throughout all the files.
3. Have fun creating your package.
4. If you need help creating a plugin, consider reading our <a href="https://docs.sylius.com/en/latest/plugin-development-guide/index.html">Plugin Development</a> guide.
---
<!--/delete-->
# :plugin_name

[![Latest Version on Packagist](https://img.shields.io/packagist/v/:vendor_name_slug/:plugin_name_slug.svg?style=flat-square)](https://packagist.org/packages/:vendor_name_slug/:plugin_name_slug)
[![Total Downloads](https://img.shields.io/packagist/dt/:vendor_name_slug/:plugin_name_slug.svg?style=flat-square)](https://packagist.org/packages/:vendor_name_slug/:plugin_name_slug)
This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

1. Run `composer require :vendor_name_slug/:plugin_name_slug`.

2. Import routes
    ```yaml
    # config/routes/sylius_shop.yaml

    :vendor_name_slug_:plugin_name_slug_shop:
        resource: "@:plugin_class/config/shop_routing.yaml"
        prefix: /{_locale}
        requirements:
            _locale: ^[A-Za-z]{2,4}(_([A-Za-z]{4}|[0-9]{3}))?(_([A-Za-z]{2}|[0-9]{3}))?$

    # config/routes/sylius_admin.yaml

    :vendor_name_slug_:plugin_name_slug_admin:
        resource: "@:plugin_class/config/admin_routing.yml"
        prefix: /admin
    ```

3. Import configuration
    ```yaml
    # config/packages/_sylius.yaml

    imports:
    # ...
    - { resource: "@:plugin_class/config/config.yaml" }
    ```

4. Apply migrations
    ```bash
    bin/console doctrine:migrations:migrate

    ```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
