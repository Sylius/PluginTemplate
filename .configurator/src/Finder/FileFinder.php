<?php

declare(strict_types=1);

namespace Sylius\PluginTemplate\Configurator\Finder;

use Symfony\Component\Finder\Finder;
use Traversable;

final class FileFinder
{
    public function findContaining(string $path, array $subjects, array $excludedPaths = []): Traversable
    {
        $finder = new Finder();

        $finder
            ->files()
            ->in($path)
            ->exclude($excludedPaths)
            ->contains($subjects)
        ;

        return $finder->getIterator();
    }
}
