<?php

declare(strict_types=1);

namespace Sylius\PluginTemplate\Configurator\Cli\ConfigureCommand;

use Sylius\PluginTemplate\Configurator\Model\PluginConfiguration;
use Symfony\Component\Console\Style\SymfonyStyle;

final class Step0RequirementsChecker
{
    public function __invoke(SymfonyStyle $io, PluginConfiguration $configuration, int $stepsTotal): void
    {
        $io->section('Checking requirements');

        if ($this->isWindows()) {
            $io->error('This plugin template is not compatible with Windows. Please use Linux (Bare metal, Docker or WSL) or macOS.');
            exit(1);
        }

        $isMakeInstalled = (bool) shell_exec('command -v make');
        $isNodeInstalled = (bool) shell_exec('command -v node');
        $isSymfonyBinaryInstalled = (bool) shell_exec('command -v symfony');

        $io->writeln([
            sprintf('%s Make', $isMakeInstalled ? '✅' : '❌'),
            sprintf('%s Node.js', $isNodeInstalled ? '✅' : '❌'),
            sprintf('%s Symfony binary', $isSymfonyBinaryInstalled ? '✅' : '⚠️'),
        ]);

        if (!$isMakeInstalled || !$isNodeInstalled) {
            $io->error('Make and Node.js are required to use this plugin template.');
            exit(1);
        }

        if (!$isSymfonyBinaryInstalled) {
            $io->warning('Symfony binary is not installed. You will not be able to use make serve command to start your local development server.');
            $io->confirm('Do you want to continue the plugin configuration anyway?', false);

            return;
        }

        $io->success('Requirements met!');
    }

    private function isWindows(): bool
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }
}
