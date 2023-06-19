<section:readme>
> **Attention!** Sylius Template Plugin is in alpha stage. Keep in mind that some bugs while creating a project may occur.
> 
> > This repository is highly inspired by [spatie/package-skeleton-laravel](https://github.com/spatie/package-skeleton-laravel).
> 
<p align="center">
    <a href="https://sylius.com" target="_blank">
        <img src="https://demo.sylius.com/assets/shop/img/logo.png" />
    </a>
</p>

<h1 align="center">Plugin Template</h1>

<p align="center">Template for starting Sylius plugins.</p>

---

### Requirements

- *nix based operating system (macOS, Linux, WSL2)
- make
- PHP version 8.0 or higher
- Node.js version 16 or higher

If you want to start quickly, it is recommended to install the [Symfony CLI](https://symfony.com/download). It will help you to run the project locally with using our Make commands.

### Usage

This repo can be used to scaffold a Sylius plugin. Follow these steps to get started:

1. Run `composer create-project sylius/plugin-template ProjectName`
2. Have fun creating your package.
3. If you need help creating a plugin, consider reading our <a href="https://docs.sylius.com/en/latest/plugin-development-guide/index.html">Plugin Development</a> guide.

Alternatively you can use the "Use this template" button on GitHub to create a new repository based on this template.
After that you can clone your freshly created repository and run `make configure` set up your brand-new plugin.

---
</section:readme>
# :plugin_name

[![Latest Version on Packagist](https://img.shields.io/packagist/v/:vendor_name_slug/:plugin_name_slug.svg?style=flat-square)](https://packagist.org/packages/:vendor_name_slug/:plugin_name_slug)
[![Total Downloads](https://img.shields.io/packagist/dt/:vendor_name_slug/:plugin_name_slug.svg?style=flat-square)](https://packagist.org/packages/:vendor_name_slug/:plugin_name_slug)  

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

1. Run `composer require :vendor_name_slug/:plugin_name_slug`.

2. Import routes
    ```yaml
    # config/routes/sylius_shop.yaml

    :config_key_shop:
        resource: "@:plugin_class/config/shop_routing.yaml"
        prefix: /{_locale}
        requirements:
            _locale: ^[A-Za-z]{2,4}(_([A-Za-z]{4}|[0-9]{3}))?(_([A-Za-z]{2}|[0-9]{3}))?$

    # config/routes/sylius_admin.yaml

    :config_key_admin:
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

Please see the [License File](LICENSE.md) for more information about licensing.
