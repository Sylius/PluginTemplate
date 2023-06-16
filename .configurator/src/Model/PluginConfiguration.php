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
        private bool $useDocker,
        private bool $usePsalm,
        private bool $usePhpStan,
        private bool $useEcs,
        private bool $usePhpUnit,
        private bool $usePhpSpec,
        private bool $useBehat,
        private bool $useGitHubActions,
        private bool $keepScaffoldedFiles,
        private ?string $databaseEngine = null,
        private ?string $databaseUser = null,
        private ?string $databasePassword = null,
        private ?string $databaseName = null,
        private ?string $databaseHost = null,
        private ?string $databasePort = null,
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
        return $this->useDocker;
    }

    public function usePsalm(): bool
    {
        return $this->usePsalm;
    }

    public function usePhpStan(): bool
    {
        return $this->usePhpStan;
    }

    public function useEcs(): bool
    {
        return $this->useEcs;
    }

    public function usePhpUnit(): bool
    {
        return $this->usePhpUnit;
    }

    public function usePhpSpec(): bool
    {
        return $this->usePhpSpec;
    }

    public function useBehat(): bool
    {
        return $this->useBehat;
    }

    public function useGitHubActions(): bool
    {
        return $this->useGitHubActions;
    }

    public function removeScaffoldedFiles(): bool
    {
        return !$this->keepScaffoldedFiles;
    }

    public function isDatabaseConfigured(): bool
    {
        return $this->databaseEngine !== null;
    }

    public function getDatabaseConnectionString(): string
    {
        if (!$this->isDatabaseConfigured()) {
            throw new \RuntimeException('Database is not configured');
        }

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
            !$data['no-docker'],
            !$data['no-psalm'],
            !$data['no-phpstan'],
            !$data['no-ecs'],
            !$data['no-phpunit'],
            !$data['no-phpspec'],
            !$data['no-behat'],
            !$data['no-github-actions'],
            !$data['no-scaffold'],
            $data['database-engine'],
            $data['database-user'],
            $data['database-password'],
            $data['database-name'],
            $data['database-host'],
            $data['database-port'],
        );
    }
}
