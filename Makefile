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

frontend.setup:
	@cd tests/Application && npm install && npm run build

static.analysis: ecs psalm phpstan

static.tests: phpspec phpunit

ci: static.analysis static.tests
