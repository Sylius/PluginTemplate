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

        $isMakeInstalled = (bool) shell_exec('command -v make');
        $isNodeInstalled = (bool) shell_exec('command -v node');

        if (!$isMakeInstalled) {
            $io->error('GNU Make is not installed. Please install it before continuing.');
            exit(1);
        }

        $io->success('GNU Make is installed.');

        if (!$isNodeInstalled) {
            $io->error('Node.js is not installed. Please install it before continuing.');
            exit(1);
        }

        $io->success('Node.js is installed.');

        if ($this->isWindows()) {
            $io->error('This plugin template is not compatible with Windows. Please use Linux (Bare metal, Docker or WSL) or macOS.');
            exit(1);
        }

        $io->success('Requirements met!');
    }

    private function isWindows(): bool
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }
}
