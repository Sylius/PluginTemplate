<?php

declare(strict_types=1);

namespace Sylius\PluginTemplate\Configurator\Cleaner;

final class SectionCleaner
{
    public function removeSection(string $filePath, string $sectionName): void
    {
        $content = file_get_contents($filePath);

        file_put_contents(
            $filePath,
            preg_replace(sprintf('/<section:%s>.*<\/section:%s>\n/sU', $sectionName, $sectionName), '', $content) ?: $content
        );
    }

    public function leaveSection(string $filePath, string $sectionName): void
    {
        $content = file_get_contents($filePath);

        $result = preg_replace(sprintf('/<section:%s>\n/s', $sectionName), '', $content) ?: $content;
        $result = preg_replace(sprintf('/<\/section:%s>\n/s', $sectionName), '', $result) ?: $result;

        file_put_contents($filePath, $result);
    }
}
