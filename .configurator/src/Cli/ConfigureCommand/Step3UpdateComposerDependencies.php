<?php

declare(strict_types=1);

namespace Sylius\PluginTemplate\Configurator\Cli\ConfigureCommand;

use Sylius\PluginTemplate\Configurator\Model\PluginConfiguration;
use Sylius\PluginTemplate\Configurator\Modifier\ComposerModifier;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class Step3UpdateComposerDependencies
{
    private const COMPOSER_TEMPLATE_JSON = 'composer.json.template';

    public function __construct (
        private ComposerModifier $composerModifier,
    ) {
    }

    public function __invoke(SymfonyStyle $io, PluginConfiguration $configuration, int $stepsTotal): void
    {
        $io->section(sprintf('Step 3 of %d: Updating composer.json', $stepsTotal));

        $dependenciesToBeRemoved = $this->getDependenciesToBeRemoved($configuration);

        $io->info(sprintf('Removing %d dependencies from the composer.json', count($dependenciesToBeRemoved)));

        foreach ($dependenciesToBeRemoved as $dependencyToBeRemoved) {
            $this->composerModifier->removePackage($dependencyToBeRemoved, self::COMPOSER_TEMPLATE_JSON);

            if ($io->getVerbosity() === OutputInterface::VERBOSITY_DEBUG) {
                $io->writeln(sprintf('Removed %s dependency', $dependencyToBeRemoved));
            }
        }

        $io->success(sprintf('Step 3 of %d completed!', $stepsTotal));
    }

    public function getDependenciesToBeRemoved(PluginConfiguration $configuration): array
    {
        $result = [];

        if (!$configuration->usePsalm()) {
            $result[] = 'vimeo/psalm';
        }

        if (!$configuration->usePhpStan())
        {
            $result[] = 'phpstan/extension-installer';
            $result[] = 'phpstan/phpstan';
            $result[] = 'phpstan/phpstan-doctrine';
            $result[] = 'phpstan/phpstan-strict-rules';
            $result[] = 'phpstan/phpstan-webmozart-assert';
        }

        if (!$configuration->useEcs()) {
            $result[] = 'sylius-labs/coding-standard';
        }

        if (!$configuration->usePhpUnit()) {
            $result[] = 'phpunit/phpunit';
        }

        if (!$configuration->usePhpSpec()) {
            $result[] = 'phpspec/phpspec';
        }

        if (!$configuration->useBehat()) {
            $result[] = 'behat/behat';
            $result[] = 'behat/mink-selenium2-driver';
            $result[] = 'dmore/behat-chrome-extension';
            $result[] = 'dmore/chrome-mink-driver';
            $result[] = 'friends-of-behat/mink';
            $result[] = 'friends-of-behat/mink-browserkit-driver';
            $result[] = 'friends-of-behat/mink-debug-extension';
            $result[] = 'friends-of-behat/mink-extension';
            $result[] = 'friends-of-behat/page-object-extension';
            $result[] = 'friends-of-behat/suite-settings-extension';
            $result[] = 'friends-of-behat/symfony-extension';
            $result[] = 'friends-of-behat/variadic-extension';
        }

        if (!$configuration->usePhpUnit() && !$configuration->useBehat()) {
            $result[] = 'symfony/browser-kit';
        }

        return $result;
    }
}
