<?php

declare(strict_types=1);

namespace Sylius\PluginTemplate\Configurator\Cli\ConfigureCommand;

use Sylius\PluginTemplate\Configurator\Model\PluginConfiguration;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

final class Step2RemoveUnusedFiles
{
    public function __construct (
        private string $projectTemplateDir,
    ) {
    }

    public function __invoke(SymfonyStyle $io, PluginConfiguration $configuration, int $stepsTotal): void
    {
        $io->section(sprintf('Step 2 of %d: Removing unused files', $stepsTotal));

        $filesystem = new Filesystem();
        $filesToBeRemoved = $this->getFilesToBeRemoved($configuration);

        $io->info(sprintf('Removing %d files', count($filesToBeRemoved)));

        foreach ($filesToBeRemoved as $fileToBeRemoved) {
            $fullPath = sprintf('%s/%s', $this->projectTemplateDir, $fileToBeRemoved);
            $filesystem->remove($fullPath);

            if ($io->getVerbosity() === OutputInterface::VERBOSITY_DEBUG) {
                $io->writeln(sprintf('Removed %s', $fullPath));
            }
        }

        $io->success(sprintf('Step 2 of %d completed!', $stepsTotal));
    }

    public function getFilesToBeRemoved(PluginConfiguration $configuration): array
    {
        $result = [
            '.github/workflows/ci.yaml',
            'composer.json',
            'composer.lock',
        ];

        if (!$configuration->useDocker()) {
            $result[] = '.docker';
            $result[] = 'docker-compose.yml';
        }

        if (!$configuration->usePsalm()) {
            $result[] = 'psalm.xml';
        }

        if (!$configuration->usePhpStan()) {
            $result[] = 'phpstan.neon';
        }

        if (!$configuration->useEcs()) {
            $result[] = 'ecs.php';
        }

        if (!$configuration->usePhpUnit()) {
            $result[] = 'phpunit.xml.dist';
        }

        if (!$configuration->usePhpSpec()) {
            $result[] = 'phpspec.yml.dist';
            $result[] = 'spec';
        }

        if (!$configuration->useBehat()) {
            $result[] = 'behat.yml.dist';
            $result[] = 'etc';
            $result[] = 'features';
            $result[] = 'tests/Application/config/services_test.yaml';
            $result[] = 'tests/Application/config/services_test_cached.yaml';
            $result[] = 'tests/Behat';
        }

        if (!$configuration->useGitHubActions()) {
            $result[] = '.github';
        }

        return $result;
    }
}
