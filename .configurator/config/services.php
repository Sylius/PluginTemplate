<?php

declare(strict_types=1);

use Sylius\PluginTemplate\Configurator\Finder\FileFinder;
use Sylius\PluginTemplate\Configurator\Replacer\PlaceholderReplacer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

return static function (ContainerConfigurator $containerConfigurator, ParameterBag $parametersBag) {
    $containerConfigurator->import(__DIR__ . '/services/**.php');

    /** Parameters */

    $parameters = $containerConfigurator->parameters();

    $parameters->set('configurator.plugin_template_dir', dirname($parametersBag->get('kernel.project_dir')));

    /** Services */

    $services = $containerConfigurator->services();

    $services->set(FileFinder::class);

    $services->set(PlaceholderReplacer::class);
};
