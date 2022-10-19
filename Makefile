.PHONY: help

COMPOSER=$(PHP_DOCKER_COMPOSE_EXEC) php -d memory_limit=-1 /usr/local/bin/composer
SYMFONY_CONSOLE=$(PHP_DOCKER_COMPOSE_EXEC) bin/console

## —— Symfony 🎶 ———————————————————————————————————————————————————————————————
cc:	## Vider le cache
	$(SYMFONY_CONSOLE) cache:clear

php-cs-fixer-fix:
	php vendor/bin/php-cs-fixer fix -v

quality-assurance-static-analyse:
	php vendor/bin/phpstan analyse src/