serve:
	@symfony serve --dir=tests/Application --daemon

server.stop:
	@symfony server:stop --dir=tests/Application

ecs:
	@vendor/bin/ecs

psalm:
	@vendor/bin/psalm

phpstan:
	@vendor/bin/phpstan

phpspec:
	@vendor/bin/phpspec run

phpunit:
	@vendor/bin/phpunit

behat.nojs:
	APP_ENV=test vendor/bin/behat --tags="~@javascript"

frontend.install:
	@cd tests/Application && npm install

frontend.build:
	@cd tests/Application && npm run build

frontend.setup: frontend.install frontend.build

setup:
	@composer install
	@make frontend.setup
	@cd tests/Application && APP_ENV=test bin/console doctrine:database:create --if-not-exists
	@cd tests/Application && APP_ENV=test bin/console doctrine:schema:create
	@cd tests/Application && APP_ENV=test bin/console sylius:fixtures:load -n
	@cd tests/Application && APP_ENV=test bin/console doctrine:database:create --if-not-exists -e test
	@cd tests/Application && APP_ENV=test bin/console doctrine:schema:create -e test
	@cd tests/Application && APP_ENV=test bin/console sylius:fixtures:load -n -e test
	@make serve

static.analysis: ecs psalm phpstan

static.tests: phpspec phpunit

ci: static.analysis static.tests
