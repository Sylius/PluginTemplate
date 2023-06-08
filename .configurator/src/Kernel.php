<?php

namespace Sylius\PluginTemplate\Configurator;

use Sylius\PluginTemplate\Configurator\DependencyInjection\CompilerPass\RemoveDefaultCommandsPass;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function getProjectDir(): string
    {
        return dirname(__DIR__);
    }

    protected function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RemoveDefaultCommandsPass());
    }
}
