<?php

declare(strict_types=1);

namespace Sylius\PluginTemplate\Configurator\Model;

final class PluginConfiguration
{
    public function __construct (
        private string $vendorName,
        private string $pluginName,
        private string $packageName,
        private string $description,
        private array $packages,
        private bool $useGitHubActions,
        private bool $removeScaffoldedFiles,
        private string $databaseEngine,
        private string $databaseUser,
        private string $databasePassword,
        private string $databaseName,
        private string $databaseHost,
        private string $databasePort,
    ) {
    }

    public function getVendorName(): string
    {
        return $this->vendorName;
    }

    public function getPluginName(): string
    {
        return $this->pluginName;
    }

    public function getPackageName(): string
    {
        return $this->packageName;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function useDocker(): bool
    {
        return in_array('docker', $this->packages, true);
    }

    public function usePsalm(): bool
    {
        return in_array('psalm', $this->packages, true);
    }

    public function usePhpStan(): bool
    {
        return in_array('phpstan', $this->packages, true);
    }

    public function useEcs(): bool
    {
        return in_array('ecs', $this->packages, true);
    }

    public function usePhpUnit(): bool
    {
        return in_array('phpunit', $this->packages, true);
    }

    public function usePhpSpec(): bool
    {
        return in_array('phpspec', $this->packages, true);
    }

    public function useBehat(): bool
    {
        return in_array('behat', $this->packages, true);
    }

    public function useGitHubActions(): bool
    {
        return $this->useGitHubActions;
    }

    public function removeScaffoldedFiles(): bool
    {
        return $this->removeScaffoldedFiles;
    }

    public function getDatabaseConnectionString(): string
    {
        return sprintf(
            '%s://%s:%s@%s:%s/%s',
            $this->databaseEngine,
            $this->databaseUser,
            $this->databasePassword,
            $this->databaseHost,
            $this->databasePort,
            $this->databaseName,
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['vendorName'],
            $data['pluginName'],
            $data['packageName'],
            $data['description'],
            $data['packages'],
            $data['useGitHubActions'],
            $data['removeScaffoldedFiles'],
            $data['databaseEngine'],
            $data['databaseUser'],
            $data['databasePassword'],
            $data['databaseName'],
            $data['databaseHost'],
            $data['databasePort'],
        );
    }
}
