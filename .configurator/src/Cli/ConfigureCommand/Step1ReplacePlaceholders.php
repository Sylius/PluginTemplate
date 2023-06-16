<?php

declare(strict_types=1);

namespace Sylius\PluginTemplate\Configurator\Cli\ConfigureCommand;

use SplFileInfo;
use Sylius\PluginTemplate\Configurator\Finder\FileFinder;
use Sylius\PluginTemplate\Configurator\Generator\NameGenerator;
use Sylius\PluginTemplate\Configurator\Model\PluginConfiguration;
use Sylius\PluginTemplate\Configurator\Replacer\PlaceholderReplacer;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class Step1ReplacePlaceholders
{
    private const EXCLUDED_FROM_SEARCH_DIRECTORIES = [
        '.configurator',
        '.git',
        'vendor',
        'node_modules',
        'tests/Application/node_modules',
        'tests/Application/public/build',
        'tests/Application/public/bundles',
        'tests/Application/var',
    ];

    public function __construct (
        private FileFinder $fileFinder,
        private PlaceholderReplacer $placeholderReplacer,
        private string $pluginTemplateDir,
    ) {
    }

    public function __invoke(SymfonyStyle $io, PluginConfiguration $configuration, int $stepsTotal): void
    {
        $io->section(sprintf('Step 1 of %d: Replacing placeholders in files', $stepsTotal));

        $filesWithPlaceholders = $this->getFilesWithPlaceholders($configuration);

        $io->info(sprintf('Found %d files containing placeholders to be replaced.', count($filesWithPlaceholders)));

        foreach ($filesWithPlaceholders as $file) {
            $this->placeholderReplacer->replaceInFile($file->getRealPath(), $this->getPlaceholdersWithReplacements($configuration));

            if ($io->getVerbosity() === OutputInterface::VERBOSITY_DEBUG) {
                $io->writeln(sprintf('Replaced placeholders in %s', $file->getRealPath()));
            }
        }

        $io->success(sprintf('Step 1 of %d completed!', $stepsTotal));
    }


    /** @return array<SplFileInfo> */
    private function getFilesWithPlaceholders(PluginConfiguration $configuration): array
    {
        $pluginTemplateDirectory = $this->pluginTemplateDir;

        $files = $this->fileFinder->findContaining(
            $pluginTemplateDirectory,
            array_keys($this->getPlaceholdersWithReplacements($configuration)),
            self::EXCLUDED_FROM_SEARCH_DIRECTORIES,
        );

        return iterator_to_array($files);
    }

    private function getPlaceholdersWithReplacements(PluginConfiguration $configuration): array
    {
        $vendorName = $configuration->getVendorName();
        $pluginName = $configuration->getPluginName();

        $result = [
            ':config_key' => NameGenerator::generateConfigKey($vendorName, $pluginName),
            ':extension_class' => NameGenerator::generateExtensionClass($vendorName, $pluginName),
            ':full_namespace_double_backslash' => NameGenerator::generateNamespace($vendorName, $pluginName, doubleDashed: true),
            ':full_namespace' => NameGenerator::generateNamespace($vendorName, $pluginName, doubleDashed: false),
            ':package_description' => $configuration->getDescription(),
            ':package_name' => NameGenerator::generatePackageName($vendorName, $pluginName),
            ':plugin_class_lowercase' => NameGenerator::generatePluginClassLowercase($vendorName, $pluginName),
            ':plugin_class' => NameGenerator::generatePluginClass($vendorName, $pluginName),
            ':plugin_name_slug' => NameGenerator::slugify($pluginName),
            ':plugin_name' => $pluginName,
            ':webpack_asset_name' => NameGenerator::generateWebpackAssetName($vendorName, $pluginName),
            ':vendor_name_slug' => NameGenerator::slugify($vendorName),
        ];

        if ($configuration->isDatabaseConfigured()) {
            $result[':database_connection_string'] = $configuration->getDatabaseConnectionString();
        }

        return $result;
    }
}
