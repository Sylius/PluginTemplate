<?php

declare(strict_types=1);

use Sylius\PluginTemplate\Configurator\Cleaner\SectionCleaner;
use Sylius\PluginTemplate\Configurator\Cli\ConfigureCommand\Step0RequirementsChecker;
use Sylius\PluginTemplate\Configurator\Cli\ConfigureCommand\Step1ReplacePlaceholders;
use Sylius\PluginTemplate\Configurator\Cli\ConfigureCommand\Step2RemoveUnusedFiles;
use Sylius\PluginTemplate\Configurator\Cli\ConfigureCommand\Step3UpdateComposerDependencies;
use Sylius\PluginTemplate\Configurator\Cli\ConfigureCommand\Step4RemoveScaffoldedFiles;
use Sylius\PluginTemplate\Configurator\Cli\ConfigureCommand\Step5CleanUpSections;
use Sylius\PluginTemplate\Configurator\Cli\ConfigureCommand\Step6RenameFiles;
use Sylius\PluginTemplate\Configurator\Cli\ConfigureCommand\Step7GeneratingMakefile;
use Sylius\PluginTemplate\Configurator\Cli\ConfigureCommand\Step8FinishSetup;
use Sylius\PluginTemplate\Configurator\Finder\FileFinder;
use Sylius\PluginTemplate\Configurator\Modifier\ComposerModifier;
use Sylius\PluginTemplate\Configurator\Replacer\PlaceholderReplacer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator) {
    $services = $containerConfigurator->services();

    $services
        ->set(Step0RequirementsChecker::class)
        ->tag('configurator.step', ['priority' => 1])
    ;

    $services
        ->set(Step1ReplacePlaceholders::class)
        ->args([
            service(FileFinder::class),
            service(PlaceholderReplacer::class),
            '%configurator.plugin_template_dir%',
        ])
        ->tag('configurator.step', ['priority' => 0])
    ;

    $services
        ->set(Step2RemoveUnusedFiles::class)
        ->args([
            '%configurator.plugin_template_dir%',
        ])
        ->tag('configurator.step', ['priority' => -1])
    ;

    $services
        ->set(Step3UpdateComposerDependencies::class)
        ->args([
            service(ComposerModifier::class),
        ])
        ->tag('configurator.step', ['priority' => -2])
    ;

    $services
        ->set(Step4RemoveScaffoldedFiles::class)
        ->args([
            '%configurator.plugin_template_dir%',
        ])
        ->tag('configurator.step', ['priority' => -3])
    ;

    $services
        ->set(Step5CleanUpSections::class)
        ->args([
            service(SectionCleaner::class),
            '%configurator.plugin_template_dir%',
        ])
        ->tag('configurator.step', ['priority' => -4])
    ;

    $services
        ->set(Step6RenameFiles::class)
        ->args([
            '%configurator.plugin_template_dir%',
        ])
        ->tag('configurator.step', ['priority' => -5])
    ;

    $services
        ->set(Step7GeneratingMakefile::class)
        ->args([
            '%configurator.plugin_template_dir%',
        ])
        ->tag('configurator.step', ['priority' => -6])
    ;

    $services
        ->set(Step8FinishSetup::class)
        ->args([
            '%configurator.plugin_template_dir%',
        ])
        ->tag('configurator.step', ['priority' => -7])
    ;
};
