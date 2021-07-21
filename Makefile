.PHONY: start restart up stop composer-install-dev cs-fix cs-check composer-require composer-require-dev test-unit console console-run

start: composer-install-dev up

restart: stop start

up:
	docker-compose up -d --remove-orphans php web mariadb

stop:
	docker-compose down -v

composer-install-dev:
	docker-compose run --rm --no-deps composer composer install --ignore-platform-reqs

composer-require:
	docker-compose run --rm --no-deps composer composer require $(dependency) --ignore-platform-reqs

composer-dumpautoload:
	docker-compose run --rm --no-deps composer composer dumpautoload

composer-require-dev:
	docker-compose run --rm --no-deps composer composer require --dev $(dependency) --ignore-platform-reqs

bootstrap-symfony-app:
	docker-compose run --rm --no-deps composer composer create-project symfony/skeleton app

cs-fix:
	docker-compose run --rm --no-deps cs-fixer php-cs-fixer fix

cs-check:
	docker-compose run --rm --no-deps cs-fixer php-cs-fixer fix --dry-run -v

test-unit:
	docker-compose run --rm --no-deps php ./bin/phpunit --configuration phpunit.xml.dist --testsuite 'unit'

console:
	docker-compose run --rm --no-deps php ./bin/console

console-run:
	docker-compose run --rm --no-deps php ./bin/console $(command)