<?php

declare(strict_types=1);

namespace Sylius\PluginTemplate\Configurator\Cli\ConfigureCommand;

use Sylius\PluginTemplate\Configurator\Cleaner\SectionCleaner;
use Sylius\PluginTemplate\Configurator\Model\PluginConfiguration;
use Symfony\Component\Console\Style\SymfonyStyle;

final class Step5SectionsCleanUp
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
                } else {
                    $this->sectionCleaner->leaveSection($filePath, $sectionName);
                }
            }
        }

        $io->success(sprintf('Step 5 of %d completed!', $stepsTotal));
    }

    private function getSectionsToBeCleanedUp(PluginConfiguration $configuration): array
    {
        $result = [];

        $result['scaffolded'] = [];
        $result['scaffolded']['tests/Application/webpack.config.js'] = $configuration->removeScaffoldedFiles() ? self::REMOVE : self::LEAVE;

        $result['phpspec'] = [];
        $result['phpspec']['ecs.php'] = $configuration->usePhpSpec() ? self::LEAVE : self::REMOVE;

        $result['behat'] = [];
        $result['behat']['ecs.php'] = $configuration->useBehat() ? self::LEAVE : self::REMOVE;
        $result['behat']['phpstan.neon'] = $configuration->useBehat() ? self::LEAVE : self::REMOVE;

        $result['readme'] = [];
        $result['readme']['README.md'] = self::REMOVE;

        return $result;
    }
}
