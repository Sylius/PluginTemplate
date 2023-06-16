<?php

declare(strict_types=1);

namespace Sylius\PluginTemplate\Configurator\Tests\Generator;

use PHPUnit\Framework\TestCase;
use Sylius\PluginTemplate\Configurator\Generator\NameGenerator;

final class NameGeneratorTest extends TestCase
{
    /** @test */
    public function it_generates_package_name(): void
    {
        $this->assertSame(
            'sylius/price-history-plugin',
            NameGenerator::generatePackageName('Sylius', 'PriceHistoryPlugin')
        );
        $this->assertSame(
            'commerce-weavers/sylius-future-plugin',
            NameGenerator::generatePackageName('CommerceWeavers', 'SyliusFuturePlugin')
        );
    }

    /** @test */
    public function it_generates_namespace(): void
    {
        $this->assertSame(
            'Sylius\\PriceHistoryPlugin',
            NameGenerator::generateNamespace('Sylius', 'PriceHistoryPlugin', false)
        );
        $this->assertSame(
            'Sylius\\\\PriceHistoryPlugin',
            NameGenerator::generateNamespace('Sylius', 'PriceHistoryPlugin', true)
        );
        $this->assertSame(
            'CommerceWeavers\\SyliusFuturePlugin',
            NameGenerator::generateNamespace('CommerceWeavers', 'SyliusFuturePlugin', false)
        );
        $this->assertSame(
            'CommerceWeavers\\\\SyliusFuturePlugin',
            NameGenerator::generateNamespace('CommerceWeavers', 'SyliusFuturePlugin', true)
        );
    }

    /** @test */
    public function it_generates_config_key(): void
    {
        $this->assertSame(
            'sylius_price_history',
            NameGenerator::generateConfigKey('Sylius', 'PriceHistoryPlugin')
        );
        $this->assertSame(
            'commerce_weavers_sylius_future',
            NameGenerator::generateConfigKey('CommerceWeavers', 'SyliusFuturePlugin')
        );
    }

    /** @test */
    public function it_generates_plugin_class(): void
    {
        $this->assertSame(
            'SyliusPriceHistoryPlugin',
            NameGenerator::generatePluginClass('Sylius', 'PriceHistoryPlugin')
        );
        $this->assertSame(
            'CommerceWeaversSyliusFuturePlugin',
            NameGenerator::generatePluginClass('CommerceWeavers', 'SyliusFuturePlugin')
        );
    }

    /** @test */
    public function it_generates_lowercase_plugin_class(): void
    {
        $this->assertSame(
            'syliuspricehistoryplugin',
            NameGenerator::generatePluginClassLowercase('Sylius', 'PriceHistoryPlugin')
        );
        $this->assertSame(
            'commerceweaverssyliusfutureplugin',
            NameGenerator::generatePluginClassLowercase('CommerceWeavers', 'SyliusFuturePlugin')
        );
    }

    /** @test */
    public function it_generates_extension_class(): void
    {
        $this->assertSame(
            'SyliusPriceHistoryExtension',
            NameGenerator::generateExtensionClass('Sylius', 'PriceHistoryPlugin')
        );
        $this->assertSame(
            'CommerceWeaversSyliusFutureExtension',
            NameGenerator::generateExtensionClass('CommerceWeavers', 'SyliusFuturePlugin')
        );
    }

    /** @test */
    public function it_generates_webpack_asset_name(): void
    {
        $this->assertSame(
            'sylius-price-history-plugin',
            NameGenerator::generateWebpackAssetName('Sylius', 'PriceHistoryPlugin')
        );
        $this->assertSame(
            'commerce-weavers-sylius-future-plugin',
            NameGenerator::generateWebpackAssetName('CommerceWeavers', 'SyliusFuturePlugin')
        );
    }

    /** @test */
    public function it_converts_a_string_to_pascal_case(): void
    {
        $this->assertSame(
            'PriceHistoryPlugin',
            NameGenerator::toPascalCase('price-history-plugin')
        );
        $this->assertSame(
            'SyliusPriceHistoryPlugin',
            NameGenerator::toPascalCase('Sylius Price History Plugin')
        );
    }

    /** @test */
    public function it_converts_a_string_to_snake_case(): void
    {
        $this->assertSame(
            'price_history_plugin',
            NameGenerator::toSnakeCase('price-history-plugin')
        );
        $this->assertSame(
            'sylius_price_history_plugin',
            NameGenerator::toSnakeCase('sylius-price-history-plugin')
        );
    }

    /** @test */
    public function it_slugify_a_string(): void
    {
        $this->assertSame(
            'price-history-plugin',
            NameGenerator::slugify('Price History Plugin')
        );
        $this->assertSame(
            'sylius-price-history-plugin',
            NameGenerator::slugify('SyliusPriceHistoryPlugin')
        );
    }
}
