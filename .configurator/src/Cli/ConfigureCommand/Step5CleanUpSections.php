<?php

declare(strict_types=1);

namespace Sylius\PluginTemplate\Configurator\Cli\ConfigureCommand;

use Sylius\PluginTemplate\Configurator\Cleaner\SectionCleaner;
use Sylius\PluginTemplate\Configurator\Model\PluginConfiguration;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class Step5CleanUpSections
{
    private const REMOVE = 'remove';

    private const LEAVE = 'leave';

    public function __construct (
        private SectionCleaner $sectionCleaner,
        private string $pluginTemplateDir,
    ) {
    }

    public function __invoke(SymfonyStyle $io, PluginConfiguration $configuration, int $stepsTotal): void
    {
        $io->section(sprintf('Step 5 of %d: Cleaning up sections', $stepsTotal));

        $sectionsToBeCleanedUp = $this->getSectionsToBeCleanedUp($configuration);

        $io->info(sprintf('Cleaning up %d sections', count($sectionsToBeCleanedUp)));

        foreach ($sectionsToBeCleanedUp as $sectionName => $files) {
            foreach ($files as $fileName => $action) {
                $filePath = sprintf('%s/%s', $this->pluginTemplateDir, $fileName);

                if ($action === self::REMOVE) {
                    $this->sectionCleaner->removeSection($filePath, $sectionName);

                    if ($io->getVerbosity() === OutputInterface::VERBOSITY_DEBUG) {
                        $io->writeln(sprintf('Removed %s section in %s', $sectionName, $fileName));
                    }
                } else {
                    $this->sectionCleaner->leaveSection($filePath, $sectionName);

                    if ($io->getVerbosity() === OutputInterface::VERBOSITY_DEBUG) {
                        $io->writeln(sprintf('Left %s section in %s', $sectionName, $fileName));
                    }
                }
            }
        }

        $io->success(sprintf('Step 5 of %d completed!', $stepsTotal));
    }

    private function getSectionsToBeCleanedUp(PluginConfiguration $configuration): array
    {
        $result = [
            'scaffolded' => [],
            'phpspec' => [],
            'behat' => [],
            'readme' => [],
        ];

        if ($configuration->useEcs()) {
            $result['phpspec']['ecs.php'] = $configuration->usePhpSpec() ? self::LEAVE : self::REMOVE;
            $result['behat']['ecs.php'] = $configuration->useBehat() ? self::LEAVE : self::REMOVE;
        }

        if ($configuration->usePhpStan()) {
            $result['behat']['phpstan.neon'] = $configuration->useBehat() ? self::LEAVE : self::REMOVE;
        }

        $result['scaffolded']['tests/Application/webpack.config.js'] = $configuration->removeScaffoldedFiles() ? self::REMOVE : self::LEAVE;
        $result['behat']['tests/Application/config/bundles.php'] = $configuration->useBehat() ? self::LEAVE : self::REMOVE;
        $result['readme']['README.md'] = self::REMOVE;

        return $result;
    }
}
