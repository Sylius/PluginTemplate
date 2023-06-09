<?php

declare(strict_types=1);

use Sylius\PluginTemplate\Configurator\Cli\ConfigureCommand;
use Sylius\PluginTemplate\Configurator\Finder\FileFinder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator) {
    $services = $containerConfigurator->services();

    $services
        ->set(ConfigureCommand::class)
        ->args([
            service(FileFinder::class),
            '%kernel.project_dir%',
        ])
        ->tag('console.command', ['configurator' => true])
    ;

    $services->set(FileFinder::class);
};
