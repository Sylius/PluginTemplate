<?php

declare(strict_types=1);

use Sylius\PluginTemplate\Configurator\Finder\FileFinder;
use Sylius\PluginTemplate\Configurator\Replacer\PlaceholderReplacer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator) {
    $containerConfigurator->import(__DIR__ . '/services/**.php');

    $services = $containerConfigurator->services();

    $services->set(FileFinder::class);

    $services->set(PlaceholderReplacer::class);
};
