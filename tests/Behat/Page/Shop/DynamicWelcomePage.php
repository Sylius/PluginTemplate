<?php

declare(strict_types=1);

namespace Tests\:full_namespace\Behat\Page\Shop;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

class DynamicWelcomePage extends SymfonyPage implements WelcomePageInterface
{
    /**
     * @inheritdoc
     */
    public function getGreeting(): string
    {
        /** @var string $greeting */
        $greeting = $this->getSession()->getPage()->waitFor(3, function (): string {
            $greeting = $this->getElement('greeting')->getText();

            if ('Loading...' === $greeting) {
                return '';
            }

            return $greeting;
        });

        return $greeting;
    }

    /**
     * @inheritdoc
     */
    public function getRouteName(): string
    {
        return ':config_key_dynamic_welcome';
    }

    /**
     * @inheritdoc
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'greeting' => '#greeting',
        ]);
    }
}
