<?php

declare(strict_types=1);

use Sylius\PluginTemplate\Configurator\Cleaner\SectionCleaner;
use Sylius\PluginTemplate\Configurator\Finder\FileFinder;
use Sylius\PluginTemplate\Configurator\Modifier\ComposerModifier;
use Sylius\PluginTemplate\Configurator\Replacer\PlaceholderReplacer;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator, ContainerBuilder $containerBuilder) {
    $containerConfigurator->import(__DIR__ . '/services/**.php');

    /** Parameters */

    $parameters = $containerConfigurator->parameters();

    $parameters->set('configurator.plugin_template_dir', dirname($containerBuilder->getParameter('kernel.project_dir')));

    /** Services */

    $services = $containerConfigurator->services();

    $services
        ->set(ComposerModifier::class)
        ->args(['%configurator.plugin_template_dir%'])
    ;

    $services->set(FileFinder::class);

    $services->set(PlaceholderReplacer::class);

    $services->set(SectionCleaner::class);
};
