<?php

declare(strict_types=1);

namespace Sylius\PluginTemplate\Configurator\Cli\ConfigureCommand;

use Sylius\PluginTemplate\Configurator\Generator\NameGenerator;
use Sylius\PluginTemplate\Configurator\Model\PluginConfiguration;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

final class Step6RenameFiles
{
    public function __construct (
        private string $pluginTemplateDir,
    ) {
    }

    public function __invoke(SymfonyStyle $io, PluginConfiguration $configuration, int $stepsTotal): void
    {
        $filesystem = new Filesystem();

        $io->section(sprintf('Step 6 of %d: Renaming files', $stepsTotal));

        $filesToBeRenamed = $this->getFilesToBeRenamed($configuration);

        $io->info(sprintf('Renaming %d files', count($filesToBeRenamed)));

        foreach ($filesToBeRenamed as $fileToBeRenamed => $newFileName) {
            $filePath = sprintf('%s/%s', $this->pluginTemplateDir, $fileToBeRenamed);
            $newFilePath = sprintf('%s/%s', $this->pluginTemplateDir, $newFileName);
            $filesystem->rename($filePath, $newFilePath);

            if ($io->getVerbosity() === OutputInterface::VERBOSITY_DEBUG) {
                $io->writeln(sprintf('Renamed %s to %s', $filePath, $newFilePath));
            }
        }

        $io->success(sprintf('Step 6 of %d completed!', $stepsTotal));
    }

    private function getFilesToBeRenamed(PluginConfiguration $configuration): array
    {
        $result = [];

        if ($configuration->removeScaffoldedFiles()) {
            $result['config/services.xml.empty'] = 'config/services.xml';
            $result['config/shop_routing.yml.empty'] = 'config/shop_routing.yml';
            $result['config/config.yaml.empty'] = 'config/config.yaml';
        }

        if ($configuration->removeScaffoldedFiles() && $configuration->useBehat()) {
            $result['tests/Behat/Resources/services.xml.empty'] = 'tests/Behat/Resources/services.xml';
            $result['tests/Behat/Resources/suites.yml.empty'] = 'tests/Behat/Resources/suites.yml';
        }

        if ($configuration->useGitHubActions()) {
            $result['.github/workflows/ci.yaml.example'] = '.github/workflows/ci.yaml';
        }

        $result['composer.json.template'] = 'composer.json';

        $pluginClass = NameGenerator::generatePluginClass($configuration->getVendorName(), $configuration->getPluginName());
        $result['src/Plugin.php'] = sprintf('src/%s.php', $pluginClass);

        $extensionClass = NameGenerator::generateExtensionClass($configuration->getVendorName(), $configuration->getPluginName());
        $result['src/DependencyInjection/Extension.php'] = sprintf('src/DependencyInjection/%s.php', $extensionClass);

        $result['tests/Application/env.local.example']  = 'tests/Application/.env.local';
        $result['tests/Application/env.test.local.example']  = 'tests/Application/.env.test.local';
        $result['symfony.lock.example'] = 'symfony.lock';
        $result['.gitattributes.example'] = '.gitattributes';

        return $result;
    }
}
