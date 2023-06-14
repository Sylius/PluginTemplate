<?php

declare(strict_types=1);

namespace Sylius\PluginTemplate\Configurator\Cli\Helper;

use Sylius\PluginTemplate\Configurator\Generator\NameGenerator;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class Summarizer
{
    public static function displaySummary(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);

        $packages = $input->getOption('packages');

        $io->section('Plugin configuration summary');

        $io->definitionList(
            ['Name' => $input->getOption('pluginName')],
            ['Vendor' => $input->getOption('vendorName')],
            ['Package name' => $input->getOption('packageName')],
            ['Namespace' => NameGenerator::generateNamespace(
                $input->getOption('vendorName'),
                $input->getOption('pluginName'),
                doubleDashed: false,
            )],
            new TableSeparator(),
            'Utilities',
            '',
            ['Use Docker' => self::isPackageEnabled('docker', $packages) ? 'Yes' : 'No'],
            new TableSeparator(),
            'Static Analysis',
            '',
            ['Use Psalm' => self::isPackageEnabled('psalm', $packages) ? 'Yes' : 'No'],
            ['Use PHPStan' => self::isPackageEnabled('phpstan', $packages) ? 'Yes' : 'No'],
            ['Use Easy Coding Standard' => self::isPackageEnabled('ecs', $packages) ? 'Yes' : 'No'],
            new TableSeparator(),
            'Testing',
            '',
            ['Use PHPUnit' => self::isPackageEnabled('phpunit', $packages) ? 'Yes' : 'No'],
            ['Use PHPSpec' => self::isPackageEnabled('phpspec', $packages) ? 'Yes' : 'No'],
            ['Use Behat' => self::isPackageEnabled('behat', $packages) ? 'Yes' : 'No'],
            new TableSeparator(),
            'Database',
            '',
            ['Database user' => $input->getOption('databaseUser')],
            ['Database password' => '********'],
            ['Database name' => $input->getOption('databaseName')],
            ['Database host' => $input->getOption('databaseHost')],
            ['Database port' => $input->getOption('databasePort')],
        );
    }

    private static function isPackageEnabled(string $packageKey, array $packages): bool
    {
        return in_array($packageKey, $packages, true);
    }
}
