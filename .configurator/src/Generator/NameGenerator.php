<?php

declare(strict_types=1);

namespace Sylius\PluginTemplate\Configurator\Generator;

final class NameGenerator
{
    public static function generatePackageName(string $vendor, string $pluginName): string
    {
        return sprintf('%s/%s', self::slugify($vendor), self::slugify($pluginName));
    }

    public static function generateNamespace(string $vendor, string $pluginName, bool $doubleDashed = true): string
    {
        return sprintf('%s%s%s', self::toPascalCase($vendor), $doubleDashed ? '\\\\' : '\\', self::toPascalCase($pluginName));
    }

    public static function toPascalCase(string $subject): string
    {
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $subject)));
    }

    public static function slugify(string $subject, string $separator = '-'): string
    {
        return strtolower(trim(preg_replace('/(?<!^)[A-Z]/', sprintf('%s$0', $separator), $subject)));
    }
}
