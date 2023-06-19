<?php

declare(strict_types=1);

namespace Sylius\PluginTemplate\Configurator\Cli\ConfigureCommand;

use Sylius\PluginTemplate\Configurator\Model\PluginConfiguration;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

final class Step8FinishSetup
{
    public function __construct (
        private string $pluginTemplateDir,
    ) {
    }

    public function __invoke(SymfonyStyle $io, PluginConfiguration $configuration, int $stepsTotal): void
    {
        $filesystem = new Filesystem();

        $io->section(sprintf('Step 8 of %d: Finishing setup', $stepsTotal));

        $io->info('Removing configurator files');

        $filesystem->remove(sprintf('%s/.configurator', $this->pluginTemplateDir));

        $io->success(sprintf('Step 8 of %d completed!', $stepsTotal));
    }
}
