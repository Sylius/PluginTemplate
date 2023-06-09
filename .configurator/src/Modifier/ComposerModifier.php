<?php

declare(strict_types=1);

namespace Sylius\PluginTemplate\Configurator\Modifier;

final class ComposerModifier
{
    public function __construct (
        private string $pluginTemplateDir,
    ) {
    }

    public function removePackage(string $packageName, string $composerFileName = 'composer.json'): void
    {
        $composerJsonPath = sprintf('%s/%s', $this->pluginTemplateDir, $composerFileName);
        $composerJsonContent = file_get_contents($composerJsonPath);

        if (false === $composerJsonContent) {
            throw new \RuntimeException('Cannot read composer.json file');
        }

        $composerJson = json_decode($composerJsonContent, true);

        if (isset($composerJson['require'][$packageName])) {
            unset($composerJson['require'][$packageName]);
        }

        if (isset($composerJson['require-dev'][$packageName])) {
            unset($composerJson['require-dev'][$packageName]);
        }

        file_put_contents(
            $composerJsonPath,
            json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );
    }
}
