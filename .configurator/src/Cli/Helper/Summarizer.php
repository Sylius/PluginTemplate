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

        $io->section('Plugin configuration summary');

        $io->definitionList(
            ['Name' => $input->getArgument('pluginName')],
            ['Vendor' => $input->getArgument('vendorName')],
            ['Package name' => $input->getArgument('packageName')],
            ['Namespace' => NameGenerator::generateNamespace(
                $input->getArgument('vendorName'),
                $input->getArgument('pluginName'),
                doubleDashed: false,
            )],
            new TableSeparator(),
            'Utilities',
            '',
            ['Use Docker' => $input->getOption('no-docker') ? 'No' : 'Yes'],
            new TableSeparator(),
            'Static Analysis',
            '',
            ['Use Psalm' => $input->getOption('no-psalm') ? 'No' : 'Yes'],
            ['Use PHPStan' => $input->getOption('no-phpstan') ? 'No' : 'Yes'],
            ['Use Easy Coding Standard' => $input->getOption('no-ecs') ? 'No' : 'Yes'],
            new TableSeparator(),
            'Testing',
            '',
            ['Use PHPUnit' => $input->getOption('no-phpunit') ? 'No' : 'Yes'],
            ['Use PHPSpec' => $input->getOption('no-phpspec') ? 'No' : 'Yes'],
            ['Use Behat' => $input->getOption('no-behat') ? 'No' : 'Yes'],
            new TableSeparator(),
            'Database',
            '',
            ['Database user' => null === $input->getOption('database-user') ? 'Not configured' : $input->getOption('database-user')],
            ['Database password' => null === $input->getOption('database-password') ? 'Not configured' : $input->getOption('database-password')],
            ['Database name' => null === $input->getOption('database-name') ? 'Not configured' : $input->getOption('database-name')],
            ['Database host' => null === $input->getOption('database-host') ? 'Not configured' : $input->getOption('database-host')],
            ['Database port' => null === $input->getOption('database-port') ? 'Not configured' : $input->getOption('database-port')],
        );
    }
}
