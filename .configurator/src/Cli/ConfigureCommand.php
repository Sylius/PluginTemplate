<?php

declare(strict_types=1);

namespace Sylius\PluginTemplate\Configurator\Cli;

use Sylius\PluginTemplate\Configurator\Cli\Helper\Summarizer;
use Sylius\PluginTemplate\Configurator\Generator\NameGenerator;
use Sylius\PluginTemplate\Configurator\Model\PluginConfiguration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Traversable;

final class ConfigureCommand extends Command
{
    private array $steps;

    public function __construct (
        private string $pluginTemplateDir,
        iterable $steps,
    ) {
        $this->steps = $steps instanceof Traversable ? iterator_to_array($steps) : $steps;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('configure')
            ->addArgument('vendorName', mode: InputArgument::REQUIRED, description: 'Vendor name')
            ->addArgument('pluginName', mode: InputArgument::REQUIRED, description: 'Plugin name')
            ->addArgument('packageName', mode: InputArgument::REQUIRED, description: 'Package name')
            ->addArgument('description', mode: InputArgument::REQUIRED, description: 'Plugin description')
            ->addOption('no-docker', mode: InputOption::VALUE_NONE, description: 'Removes all Docker-related files')
            ->addOption('no-psalm', mode: InputOption::VALUE_NONE, description: 'Removes Psalm-related files and packages')
            ->addOption('no-phpstan', mode: InputOption::VALUE_NONE, description: 'Removes PHPStan-related files and packages')
            ->addOption('no-ecs', mode: InputOption::VALUE_NONE, description: 'Removes Easy Coding Standard-related files and packages')
            ->addOption('no-phpunit', mode: InputOption::VALUE_NONE, description: 'Removes PHPUnit-related files and packages')
            ->addOption('no-phpspec', mode: InputOption::VALUE_NONE, description: 'Removes PHPSpec-related files and packages')
            ->addOption('no-behat', mode: InputOption::VALUE_NONE, description: 'Removes Behat-related files and packages')
            ->addOption('no-github-actions', mode: InputOption::VALUE_NONE, description: 'Does not create an example GitHub Actions workflow file')
            ->addOption('no-scaffold', mode: InputOption::VALUE_NONE, description: 'Removes all example files like Controllers, defined services and routes, etc.')
            ->addOption('database-engine', mode: InputOption::VALUE_REQUIRED)
            ->addOption('database-user', mode: InputOption::VALUE_REQUIRED)
            ->addOption('database-password', mode: InputOption::VALUE_REQUIRED)
            ->addOption('database-name', mode: InputOption::VALUE_REQUIRED)
            ->addOption('database-host', mode: InputOption::VALUE_REQUIRED)
            ->addOption('database-port', mode: InputOption::VALUE_REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        Summarizer::displaySummary($input, $output);

        $confirmAnswers = $io->confirm('Does everything look good?');

        if (!$confirmAnswers) {
            $io->error('Please re-run the command with the correct options.');
            exit(1);
        }

        $configuration = PluginConfiguration::fromArray($input->getArguments() + $input->getOptions());

        foreach ($this->steps as $step) {
            $step($io, $configuration, count($this->steps) - 1); // -1 because we don't count the requirements step
        }

        $io->success('Plugin configured successfully!🎉');

        $io->writeln([
            'All done! Now you can start developing your plugin.',
            'To set up the development environment, run:',
        ]);

        $io->block(sprintf('cd %s && make setup', $this->pluginTemplateDir), style: 'fg=green', padding: true);

        $io->writeln('If you want to use git, run:');

        $io->block('git init', style: 'fg=green', padding: true);

        $io->writeln('To start the local development server, run:');

        $io->block('make serve', style: 'fg=green', padding: true);

        $io->writeln('Happy coding!🎉🧑‍💻');

        return self::SUCCESS;
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Welcome to the Sylius Plugin configurator!');

        $io->section('Plugin information configuration');

        if (null === $input->getArgument('vendorName')) {
            $vendorName = $io->ask('What is your organization\'s name?', 'Acme');
            $input->setArgument('vendorName', $vendorName);
        }

        if (null === $input->getArgument('pluginName')) {
            $pluginName = $io->ask('How would you like to name your plugin?', 'SyliusAwesomePlugin');
            $input->setArgument('pluginName', $pluginName);
        }

        if (null === $input->getArgument('packageName')) {
            $suggestedPackageName = NameGenerator::generatePackageName(
                $input->getArgument('vendorName'),
                $input->getArgument('pluginName')
            );
            $packageName = $io->ask('What is the package name?', $suggestedPackageName);
            $input->setArgument('packageName', $packageName);
        }

        if (null === $input->getArgument('description')) {
            $description = $io->ask('What is the description?', 'My brand new Sylius plugin!');
            $input->setArgument('description', $description);
        }

        $io->section('Tooling configuration');

        if (false === $input->getOption('no-docker')) {
            $useDocker = $io->confirm('Would you like to use Docker?');
            $input->setOption('no-docker', !$useDocker);
        }

        if (false === $input->getOption('no-psalm')) {
            $usePsalm = $io->confirm('Would you like to use Psalm?');
            $input->setOption('no-psalm', !$usePsalm);
        }

        if (false === $input->getOption('no-phpstan')) {
            $usePhpStan = $io->confirm('Would you like to use PHPStan?');
            $input->setOption('no-phpstan', !$usePhpStan);
        }

        if (false === $input->getOption('no-ecs')) {
            $useEcs = $io->confirm('Would you like to use Easy Coding Standard?');
            $input->setOption('no-ecs', !$useEcs);
        }

        if (false === $input->getOption('no-phpunit')) {
            $usePhpUnit = $io->confirm('Would you like to use PHPUnit?');
            $input->setOption('no-phpunit', !$usePhpUnit);
        }

        if (false === $input->getOption('no-phpspec')) {
            $usePhpSpec = $io->confirm('Would you like to use PHPSpec?');
            $input->setOption('no-phpspec', !$usePhpSpec);
        }

        if (false === $input->getOption('no-behat')) {
            $useBehat = $io->confirm('Would you like to use Behat?');
            $input->setOption('no-behat', !$useBehat);
        }

        if (false === $input->getOption('no-github-actions')) {
            $useGitHubActions = $io->confirm('Would you like to use GitHub Actions?');
            $input->setOption('no-github-actions', !$useGitHubActions);
        }

        if (false === $input->getOption('no-scaffold')) {
            $useScaffold = $io->confirm('Would you like to keep scaffolded files?');
            $input->setOption('no-scaffold', !$useScaffold);
        }

        $io->section('Development environment configuration');

        $configureDatabase = $io->confirm('Would you like to configure the database? All gathered data will be used to configure your .env.local and .env.test.local files.');
        if ($configureDatabase) {
            if (null === $input->getOption('database-engine')) {
                $databaseChoiceQuestion = new ChoiceQuestion('What is the database engine?', ['mysql', 'pgsql'], 'mysql');

                $databaseEngine = $io->askQuestion($databaseChoiceQuestion);
                $input->setOption('database-engine', $databaseEngine);
            }

            if (null === $input->getOption('database-user')) {
                $databaseUser = $io->ask('What is the database user?', 'root');
                $input->setOption('database-user', $databaseUser);
            }

            if (null === $input->getOption('database-password')) {
                $databasePassword = $io->askHidden('What is the database password?');
                $input->setOption('database-password', $databasePassword);
            }

            if (null === $input->getOption('database-name')) {
                $databaseName = $io->ask('What is the database name?', 'sylius');
                $input->setOption('database-name', $databaseName);
            }

            if (null === $input->getOption('database-host')) {
                $databaseHost = $io->ask('What is the database host?', 'localhost');
                $input->setOption('database-host', $databaseHost);
            }

            if (null === $input->getOption('database-port')) {
                $databasePort = $io->ask('What is the database port?', '3306');
                $input->setOption('database-port', $databasePort);
            }
        }
    }
}
