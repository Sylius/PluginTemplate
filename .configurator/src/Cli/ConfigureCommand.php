<?php

declare(strict_types=1);

namespace Sylius\PluginTemplate\Configurator\Cli;

use SplFileInfo;
use Sylius\PluginTemplate\Configurator\Cli\Helper\Summarizer;
use Sylius\PluginTemplate\Configurator\Finder\FileFinder;
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

    public function __construct (
        private FileFinder $fileFinder,
        private string $projectDir,
    ) {
        parent::__construct();
    }

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
        $io = new SymfonyStyle($input, $output);

        $filesWithPlaceholders = $this->getFilesWithPlaceholders($input);

        $io->info(sprintf('Found %d files containing placeholders to be replaced.', count($filesWithPlaceholders)));

        $io->progressStart(count($filesWithPlaceholders));

        foreach ($filesWithPlaceholders as $file) {
            $this->replacePlaceholders($input, $file->getRealPath());
            $io->progressAdvance();
        }

        return self::SUCCESS;
    }

    /** @return array<SplFileInfo> */
    private function getFilesWithPlaceholders(InputInterface $input): array
    {
        $pluginTemplateDirectory = dirname($this->projectDir);

        $files = $this->fileFinder->findContaining(
            $pluginTemplateDirectory,
            array_keys($this->getPlaceholdersWithReplacements($input)),
            $this->getExcludedFromSearchDirectories(),
        );

        return iterator_to_array($files);
    }

    private function replacePlaceholders(InputInterface $input, string $path): void
    {
        $placeholdersWithReplacements = $this->getPlaceholdersWithReplacements($input);

        $fileContent = file_get_contents($path);

        $fileContent = str_replace(
            array_keys($placeholdersWithReplacements),
            array_values($placeholdersWithReplacements),
            $fileContent,
        );

        file_put_contents($path, $fileContent);
    }

    private function getExcludedFromSearchDirectories(): array
    {
        return ['vendor', 'node_modules', 'tests/Application/vendor', 'tests/Application/node_modules'];
    }

    private function getPlaceholdersWithReplacements(InputInterface $input): array
    {
        return [
            ':config_key' => '',
            ':extension_class' => '',
            ':full_namespace_double_backslash' => '',
            ':full_namespace' => '',
            ':package_description' => '',
            ':package_name' => '',
            ':plugin_class_lowercase' => '',
            ':plugin_class' => '',
            ':plugin_name_slug' => '',
            ':plugin_name' => '',
            ':webpack_asset_name' => '',
            ':vendor_name_slug' => '',
        ];
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
