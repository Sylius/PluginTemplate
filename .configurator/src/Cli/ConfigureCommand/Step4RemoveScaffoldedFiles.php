<?php

declare(strict_types=1);

namespace Sylius\PluginTemplate\Configurator\Cli\ConfigureCommand;

use Sylius\PluginTemplate\Configurator\Model\PluginConfiguration;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

final class Step4RemoveScaffoldedFiles
{
    public function __construct (
        private string $pluginTemplateDir,
    ) {
    }

    public function __invoke(SymfonyStyle $io, PluginConfiguration $configuration, int $stepsTotal): void
    {
        $filesystem = new Filesystem();

        $io->section(sprintf('Step 4 of %d: Removing scaffolded files', $stepsTotal));

        if (!$configuration->removeScaffoldedFiles()) {
            $io->info('Skipping step');
            $io->info('Performing cleanup');

            $filesToBeCleanedUp = $this->getFilesToCleanUp();

            foreach ($filesToBeCleanedUp as $fileToBeCleanedUp) {
                $filePath = sprintf('%s/%s', $this->pluginTemplateDir, $fileToBeCleanedUp);
                $filesystem->remove($filePath);

                if ($io->getVerbosity() === OutputInterface::VERBOSITY_DEBUG) {
                    $io->writeln(sprintf('Removed %s file', $fileToBeCleanedUp));
                }
            }

            $io->success(sprintf('Step 4 of %d completed!', $stepsTotal));

            return;
        }

        $filesToBeRemoved = $this->getFilesToBeRemoved();

        $io->info(sprintf('Removing %d scaffolded files', count($filesToBeRemoved)));

        foreach ($filesToBeRemoved as $fileToBeRemoved) {
            $filePath = sprintf('%s/%s', $this->pluginTemplateDir, $fileToBeRemoved);
            $filesystem->remove($filePath);

            if ($io->getVerbosity() === OutputInterface::VERBOSITY_DEBUG) {
                $io->writeln(sprintf('Removed %s file', $fileToBeRemoved));
            }
        }

        $io->success(sprintf('Step 4 of %d completed!', $stepsTotal));
    }

    public function getFilesToCleanUp(): array
    {
        $result = [];

        $result[] = 'config.config.yaml.empty';
        $result[] = 'config/shop_routing.yml.empty';
        $result[] = 'config/services.xml.empty';
        $result[] = 'features/.gitkeep';
        $result[] = 'public/.gitkeep';
        $result[] = 'src/Controller/.gitkeep';
        $result[] = 'templates/.gitkeep';
        $result[] = 'tests/Behat/Resources/services.xml.empty';
        $result[] = 'tests/Behat/Resources/suites.yml.empty';

        return $result;
    }

    private function getFilesToBeRemoved(): array
    {
        $result = [];

        // assets
        $result[] = 'assets';

        // config
        $result[] = 'config/services.xml';
        $result[] = 'config/shop_routing.yml';
        $result[] = 'config/config.yaml';
        $result[] = 'config/packages/sylius_ui.php';
        $result[] = 'config/packages';

        //features
        $result[] = 'features/running_a_sylius_feature.feature';
        $result[] = 'features/dynamically_greeting_a_customer.feature';
        $result[] = 'features/statically_greeting_a_customer.feature';

        //public
        $result[] = 'public/greeting.js';

        //src
        $result[] = 'src/Controller';

        //templates
        $result[] = 'templates/dynamic_greeting.html.twig';
        $result[] = 'templates/static_greeting.html.twig';
        $result[] = 'templates/Admin';
        $result[] = 'templates/Shop';

        //tests
        $result[] = 'tests/Behat/Context';
        $result[] = 'tests/Behat/Page';
        $result[] = 'tests/Behat/Resources/suites.yml';
        $result[] = 'tests/Behat/Resources/services.xml';

        return $result;
    }
}
