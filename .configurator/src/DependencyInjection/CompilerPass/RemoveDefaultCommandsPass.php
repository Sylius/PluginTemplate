<?php

declare(strict_types=1);

namespace Sylius\PluginTemplate\Configurator\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class RemoveDefaultCommandsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $commands = $container->findTaggedServiceIds('console.command');

        foreach ($commands as $serviceId => $tags) {
            $isConfiguratorCommand = array_filter($tags, static function (array $tag) {
                return isset($tag['configurator']) && true === $tag['configurator'];
            }) !== [];

            if ($isConfiguratorCommand) {
                continue;
            }

            $container->removeDefinition($serviceId);
        }
    }
}
