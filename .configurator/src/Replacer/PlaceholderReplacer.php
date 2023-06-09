<?php

declare(strict_types=1);

namespace Sylius\PluginTemplate\Configurator\Replacer;

final class PlaceholderReplacer
{
    public function replaceInFile(string $path, array $placeholdersWithReplacements): void
    {
        $fileContent = file_get_contents($path);

        $fileContent = $this->replace($fileContent, $placeholdersWithReplacements);

        file_put_contents($path, $fileContent);
    }

    public function replace(string $fileContent, array $placeholdersWithReplacements): string
    {
        return str_replace(
            array_keys($placeholdersWithReplacements),
            array_values($placeholdersWithReplacements),
            $fileContent,
        );
    }
}
