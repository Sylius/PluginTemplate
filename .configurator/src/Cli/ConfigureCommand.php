<?php

declare(strict_types=1);

namespace Sylius\PluginTemplate\Configurator\Cli;

use Sylius\PluginTemplate\Configurator\Cli\Helper\Summarizer;
use Sylius\PluginTemplate\Configurator\Generator\NameGenerator;
use Sylius\PluginTemplate\Configurator\Model\PluginConfiguration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Traversable;

final class ConfigureCommand extends Command
{
    public const AVAILABLE_PACKAGES = [
        'docker' => 'Docker',
        'psalm' => 'Psalm',
        'phpstan' => 'PHPStan',
        'ecs' => 'Easy Coding Standard',
        'phpunit' => 'PHPUnit',
        'phpspec' => 'PHPSpec',
        'behat' => 'Behat',
    ];

    private array $steps;

    public function __construct (
        iterable $steps,
    ) {
        $this->steps = $steps instanceof Traversable ? iterator_to_array($steps) : $steps;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('configure')
            ->addOption('vendorName', mode: InputOption::VALUE_REQUIRED)
            ->addOption('pluginName', mode: InputOption::VALUE_REQUIRED)
            ->addOption('packageName', mode: InputOption::VALUE_REQUIRED)
            ->addOption('description', mode: InputOption::VALUE_REQUIRED)
            ->addOption('packages', mode: InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED)
            ->addOption('useGitHubActions', mode: InputOption::VALUE_REQUIRED)
            ->addOption('removeScaffoldedFiles', mode: InputOption::VALUE_REQUIRED)
            ->addOption('databaseEngine', mode: InputOption::VALUE_REQUIRED)
            ->addOption('databaseUser', mode: InputOption::VALUE_REQUIRED)
            ->addOption('databasePassword', mode: InputOption::VALUE_REQUIRED)
            ->addOption('databaseName', mode: InputOption::VALUE_REQUIRED)
            ->addOption('databaseHost', mode: InputOption::VALUE_REQUIRED)
            ->addOption('databasePort', mode: InputOption::VALUE_REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $configuration = PluginConfiguration::fromArray($input->getOptions());

        foreach ($this->steps as $step) {
            $step($io, $configuration, count($this->steps) - 1); // -1 because we don't count the requirements step
        }

        return self::SUCCESS;
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Welcome to the Sylius Plugin configurator!');

        $io->section('Plugin information configuration');

        if (null === $input->getOption('vendorName')) {
            $vendorName = $io->ask('What is your organization\'s name?', 'Acme');
            $input->setOption('vendorName', $vendorName);
        }

        if (null === $input->getOption('pluginName')) {
            $pluginName = $io->ask('How would you like to name your plugin?', 'SyliusAwesomePlugin');
            $input->setOption('pluginName', $pluginName);
        }

        if (null === $input->getOption('packageName')) {
            $suggestedPackageName = NameGenerator::generatePackageName(
                $input->getOption('vendorName'),
                $input->getOption('pluginName')
            );
            $packageName = $io->ask('What is the package name?', $suggestedPackageName);
            $input->setOption('packageName', $packageName);
        }

        if (null === $input->getOption('description')) {
            $description = $io->ask('What is the description?', 'My brand new Sylius plugin!');
            $input->setOption('description', $description);
        }

        $io->info('Tooling configuration');

        if ([] === $input->getOption('packages')) {
            $chosenPackages = [];

            foreach (self::AVAILABLE_PACKAGES as $package => $name) {
                $databaseChoiceQuestion = new ConfirmationQuestion(sprintf('Would you like to use %s?', $name), true);
                $databaseChoiceQuestion->setAutocompleterValues(['y', 'yes', 'n', 'no']);

                if ($io->askQuestion($databaseChoiceQuestion)) {
                    $chosenPackages[] = $package;
                }
            }

            $input->setOption('packages', $chosenPackages);
        }

        if (null === $input->getOption('useGitHubActions')) {
            $useGitHubActions = $io->confirm('Would you like to generate an example GitHub Actions workflow file?');
            $input->setOption('useGitHubActions', $useGitHubActions);
        }

        if (null === $input->getOption('removeScaffoldedFiles')) {
            $removeScaffoldedFiles = $io->confirm('Would you like to keep scaffolded files?');
            $input->setOption('removeScaffoldedFiles', $removeScaffoldedFiles);
        }

        $io->info('Database configuration');

        if (null === $input->getOption('databaseEngine')) {
            $databaseChoiceQuestion = new ChoiceQuestion('What is the database engine?', ['mysql', 'pgsql'], 'mysql');

            $databaseEngine = $io->askQuestion($databaseChoiceQuestion);
            $input->setOption('databaseEngine', $databaseEngine);
        }

        if (null === $input->getOption('databaseUser')) {
            $databaseUser = $io->ask('What is the database user?', 'root');
            $input->setOption('databaseUser', $databaseUser);
        }

        if (null === $input->getOption('databasePassword')) {
            $databasePassword = $io->askHidden('What is the database password?');
            $input->setOption('databasePassword', $databasePassword);
        }

        if (null === $input->getOption('databaseName')) {
            $databaseName = $io->ask('What is the database name?', 'sylius');
            $input->setOption('databaseName', $databaseName);
        }

        if (null === $input->getOption('databaseHost')) {
            $databaseHost = $io->ask('What is the database host?', 'localhost');
            $input->setOption('databaseHost', $databaseHost);
        }

        if (null === $input->getOption('databasePort')) {
            $databasePort = $io->ask('What is the database port?', '3306');
            $input->setOption('databasePort', $databasePort);
        }

        Summarizer::displaySummary($input, $output);

        $confirmAnswers = $io->confirm('Does everything look good?');

        if (!$confirmAnswers) {
            $io->error('Please re-run the command with the correct options.');
            exit(1);
        }
    }
}
