<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator) {
    $containerConfigurator->extension('sylius_ui', [
        'events' => [
            'sylius.admin.layout.javascripts' => [
                'blocks' => [
                    ':config_key_scripts' => [
                        'template' => '@:plugin_class/Admin/_scripts.html.twig',
                        'priority' => 5,
                    ],
                ],
            ],
            'sylius.admin.layout.stylesheets' => [
                'blocks' => [
                    ':config_key_scripts' => [
                        'template' => '@:plugin_class/Admin/_styles.html.twig',
                        'priority' => 5,
                    ],
                ],
            ],
            'sylius.shop.layout.javascripts' => [
                'blocks' => [
                    ':config_key_scripts' => [
                        'template' => '@:plugin_class/Shop/_scripts.html.twig',
                        'priority' => 5,
                    ],
                ],
            ],
            'sylius.shop.layout.stylesheets' => [
                'blocks' => [
                    ':config_key_scripts' => [
                        'template' => '@:plugin_class/Shop/_styles.html.twig',
                        'priority' => 5,
                    ],
                ],
            ],
        ],
    ]);
};
