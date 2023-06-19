<?php

declare(strict_types=1);

use Sylius\PluginTemplate\Configurator\Cli\ConfigureCommand;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return static function (ContainerConfigurator $containerConfigurator) {
    $services = $containerConfigurator->services();

    $services
        ->set(ConfigureCommand::class)
        ->args([
            '%configurator.plugin_template_dir%',
            tagged_iterator('configurator.step'),
        ])
        ->tag('console.command', ['configurator' => true])
    ;
};
