<?php

declare(strict_types=1);

namespace Sylius\PluginTemplate\Configurator\Cli;

use Sylius\PluginTemplate\Configurator\Cli\Helper\Summarizer;
use Sylius\PluginTemplate\Configurator\Generator\NameGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class ConfigureCommand extends Command
{
    public const AVAILABLE_PACKAGES = [
        'docker' => 'Docker',
        'psalm' => 'Psalm',
        'phpstan' => 'PHPStan',
        'ecs' => 'Easy Coding Standard',
        'phpunit' => 'PHPUnit',
        'phpspec' => 'PHPSpec',
        'behat' => 'Behat',
    ];

    protected function configure(): void
    {
        $this
            ->setName('configure')
            ->addOption('vendorName', mode: InputOption::VALUE_REQUIRED)
            ->addOption('pluginName', mode: InputOption::VALUE_REQUIRED)
            ->addOption('packageName', mode: InputOption::VALUE_REQUIRED)
            ->addOption('description', mode: InputOption::VALUE_REQUIRED)
            ->addOption('packages', mode: InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED)
            ->addOption('useGitHubActions', mode: InputOption::VALUE_REQUIRED)
            ->addOption('removeScaffoldedFiles', mode: InputOption::VALUE_REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        return self::SUCCESS;
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Welcome to the Sylius Plugin configurator!');

        $io->section('Information gathering');

        if (null === $input->getOption('vendorName')) {
            $vendorName = $io->ask('What is your organization\'s name?', 'Acme');
            $input->setOption('vendorName', $vendorName);
        }

        if (null === $input->getOption('pluginName')) {
            $pluginName = $io->ask('How would you like to name your plugin?', 'SyliusAwesomePlugin');
            $input->setOption('pluginName', $pluginName);
        }

        if (null === $input->getOption('packageName')) {
            $suggestedPackageName = NameGenerator::generatePackageName(
                $input->getOption('vendorName'),
                $input->getOption('pluginName')
            );
            $packageName = $io->ask('What is the package name?', $suggestedPackageName);
            $input->setOption('packageName', $packageName);
        }

        if (null === $input->getOption('description')) {
            $description = $io->ask('What is the description?', 'My brand new Sylius plugin!');
            $input->setOption('description', $description);
        }

        if ([] === $input->getOption('packages')) {
            $chosenPackages = [];

            foreach (self::AVAILABLE_PACKAGES as $package => $name) {
                if ($io->confirm(sprintf('Would you like to use %s?', $name))) {
                    $chosenPackages[] = $package;
                }
            }

            $input->setOption('packages', $chosenPackages);
        }

        if (null === $input->getOption('useGitHubActions')) {
            $useGitHubActions = $io->confirm('Would you like to generate an example GitHub Actions workflow file?');
            $input->setOption('useGitHubActions', $useGitHubActions);
        }

        if (null === $input->getOption('removeScaffoldedFiles')) {
            $removeScaffoldedFiles = $io->confirm('Would you like to remove scaffolded files?', false);
            $input->setOption('removeScaffoldedFiles', $removeScaffoldedFiles);
        }

        Summarizer::displaySummary($input, $output);
    }
}
