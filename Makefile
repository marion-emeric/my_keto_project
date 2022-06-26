PHP_CONTAINER = "php"
DB_CONTAINER = "database"
PATH_ENV = "./.env.local"

# Executables
EXEC_PHP      = php
COMPOSER      = composer
GIT           = git

# Alias
SYMFONY       = php ./bin/console

# Executables: vendors
PHPUNIT       = ./vendor/bin/phpunit
PHPSTAN       = ./vendor/bin/phpstan
PHP_CS_FIXER  = ./vendor/bin/php-cs-fixer
PHPMETRICS    = ./vendor/bin/phpmetrics

# Executables: local only
SYMFONY_BIN   = symfony
DOCKER        = docker
DOCKER_COMP   = docker-compose

# Misc
.DEFAULT_GOAL = help
.PHONY        = help up down logs composer php sh console cc

## -- Makefile help --
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Docker 🐳 ————————————————————————————————————————————————————————————————
up: ## Build the images and start the containers
	@$(DOCKER_COMP) --env-file $(PATH_ENV) up -d --build --force-recreate

down: ## Stop the docker hub
	@$(DOCKER_COMP) --env-file $(PATH_ENV) down --remove-orphans

## -- Composer ————————————————————————————————————————————————————————————————
composer: ## Run composer; use c= to pass arguments example c="req my/package" or c="install"
	@$(eval c ?=)
	@$(DOCKER_COMP) --env-file $(PATH_ENV) exec $(PHP_CONTAINER) composer $(c)

## -- PHP ————————————————————————————————————————————————————————————————
php: ## Run php command line; use c= to pass arguments example c="-v"
	@$(eval c ?=)
	@$(DOCKER_COMP) --env-file $(PATH_ENV) exec $(PHP_CONTAINER) php $(c)

phpsh: ## Connect to the PHP container
	@$(DOCKER_COMP) --env-file $(PATH_ENV) exec $(PHP_CONTAINER) bash

## -- DB ————————————————————————————————————————————————————————————————
dbinit: ## Initialize database
	@./docker/mysql/init.sh

db: ## Run mysql command line; use c= to pass arguments example c="--version"
	@$(eval c ?=)
	@$(DOCKER_COMP) --env-file $(PATH_ENV) exec $(DB_CONTAINER) mysql $(c)

dbsh: ## Connect to the DB container
	@$(DOCKER_COMP) --env-file $(PATH_ENV) exec $(DB_CONTAINER) bash

## —— Symfony 🎵 ———————————————————————————————————————————————————————————————

console: ## Run console in the container; use c= to pass arguments example c="cache:clear"
	@$(eval c ?=)
	@$(DOCKER_COMP) --env-file $(PATH_ENV)  exec $(PHP_CONTAINER) $(SYMFONY) $(c)

cc: ## Cache clear
	@$(DOCKER_COMP) --env-file $(PATH_ENV) exec $(PHP_CONTAINER) $(SYMFONY) c:c

sf: ## List all Symfony commands
	@$(DOCKER_COMP) --env-file $(PATH_ENV) exec $(PHP_CONTAINER) $(SYMFONY) list

warmup: ## Clear the cache. DID YOU CLEAR YOUR CACHE????
	@$(DOCKER_COMP) --env-file $(PATH_ENV) exec $(PHP_CONTAINER) $(SYMFONY) cache:warmup

symfony:## Run symfony command in the container;
	@$(eval c ?=)
	@@$(DOCKER_COMP) --env-file $(PATH_ENV) exec $(PHP_CONTAINER) $(SYMFONY_BIN) $(c)

## —— Tests ✅ —————————————————————————————————————————————————————————————————

phpunit: ## PHP Unit Test
	@$(DOCKER_COMP) --env-file $(PATH_ENV) exec $(PHP_CONTAINER) $(PHPUNIT)

## —— Coding standards ✨ ——————————————————————————————————————————————————————

stan: ## Run PHPStan
	@$(DOCKER_COMP) --env-file $(PATH_ENV) exec $(PHP_CONTAINER) $(PHPSTAN) analyse src tests -l max

cs-fixer: ## Fix files with php-cs-fixer
	@$(DOCKER_COMP) --env-file $(PATH_ENV) exec $(PHP_CONTAINER) $(PHP_CS_FIXER) fix src --config=.php-cs-fixer.php

