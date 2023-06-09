<?php

declare(strict_types=1);

use Sylius\PluginTemplate\Configurator\Cli\ConfigureCommand\Step1ReplacePlaceholders;
use Sylius\PluginTemplate\Configurator\Finder\FileFinder;
use Sylius\PluginTemplate\Configurator\Replacer\PlaceholderReplacer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator) {
    $services = $containerConfigurator->services();

    $services
        ->set(Step1ReplacePlaceholders::class)
        ->args([
            service(FileFinder::class),
            service(PlaceholderReplacer::class),
            '%kernel.project_dir%',
        ])
        ->tag('configurator.step', ['priority' => 1])
    ;
};
