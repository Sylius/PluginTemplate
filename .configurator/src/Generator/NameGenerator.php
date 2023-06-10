<?php

declare(strict_types=1);

namespace Sylius\PluginTemplate\Configurator\Generator;

final class NameGenerator
{
    public static function generatePackageName(string $vendor, string $pluginName): string
    {
        return sprintf('%s/%s', self::slugify($vendor), self::slugify($pluginName));
    }

    public static function generateNamespace(string $vendor, string $pluginName, bool $doubleDashed): string
    {
        return sprintf('%s%s%s', self::toPascalCase($vendor), $doubleDashed ? '\\\\' : '\\', self::toPascalCase($pluginName));
    }

    public static function generateConfigKey(string $vendor, string $pluginName): string
    {
        $packageName = self::generatePackageName($vendor, $pluginName);
        $packageName = str_replace('/', '-', $packageName);
        $packageName = str_replace('-plugin', '', $packageName);

        return self::toSnakeCase($packageName);
    }

    public static function generatePluginClass(string $vendor, string $pluginName): string
    {
        return sprintf('%s%s', self::toPascalCase($vendor), self::toPascalCase($pluginName));
    }

    public static function generatePluginClassLowercase(string $vendor, string $pluginName): string
    {
        return strtolower(self::generatePluginClass($vendor, $pluginName));
    }

    public static function generateExtensionClass(string $vendor, string $pluginName): string
    {
        return str_replace('Plugin', 'Extension', self::generatePluginClass($vendor, $pluginName));
    }

    public static function generateWebpackAssetName(string $vendor, string $pluginName): string
    {
        $packageName = self::generatePackageName($vendor, $pluginName);

        return str_replace('/', '-', $packageName);
    }

    public static function toPascalCase(string $subject): string
    {
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $subject)));
    }

    public static function toSnakeCase(string $subject, $currentSeparator = '-'): string
    {
        return strtolower(str_replace($currentSeparator, '_', $subject));
    }

    public static function slugify(string $subject, string $separator = '-'): string
    {
        $subject = ucwords(str_replace(' ', '', $subject));

        return strtolower(trim(preg_replace('/(?<!^)[A-Z]/', sprintf('%s$0', $separator), $subject)));
    }
}
