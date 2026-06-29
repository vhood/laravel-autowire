.PHONY: help init restart build run stop shell test test-unit phpstan cs-check cs-fix audit composer

.DEFAULT_GOAL := help

-include .env

PROJECT_NAME ?= laravel-autowire
CURRENT_UID ?= $(shell id -u 2>/dev/null || echo 1000)
CURRENT_GID ?= $(shell id -g 2>/dev/null || echo 1000)

help:
	@echo "Available commands:"
	@echo "  make init              - Initialize the project from scratch"
	@echo "  make restart           - Restart the container"
	@echo "  make build             - Build the Docker image"
	@echo "  make run               - Run the container in the background"
	@echo "  make stop              - Stop the running container"
	@echo "  make shell             - Enter the container shell"
	@echo "  make test              - Run all QA checks (Audit, Style, PHPStan, Pest)"
	@echo "  make test-unit         - Run only unit tests via Pest"
	@echo "  make phpstan           - Run static code analysis"
	@echo "  make cs-check          - Verify code style without changing files"
	@echo "  make cs-fix            - Automatically fix code style flaws"
	@echo "  make audit             - Check dependencies for security vulnerabilities"
	@echo "  make composer-install  - Install composer dependencies"
	@echo "  make composer-update   - Update composer dependencies"

# Environment

init: environments build run composer-install

restart: stop run

environments:
	@test -f .env || cp .env-default .env

build:
	@docker build -t ${PROJECT_NAME} .

run:
	@docker run --rm -d -it --name ${PROJECT_NAME} -v ${shell pwd}:/app ${PROJECT_NAME}

stop:
	@docker stop ${PROJECT_NAME} 2>/dev/null || true

composer-install:
	@docker exec -it -u ${CURRENT_UID}:${CURRENT_GID} ${PROJECT_NAME} composer install --no-interaction

composer-update:
	@docker exec -it -u ${CURRENT_UID}:${CURRENT_GID} ${PROJECT_NAME} composer update --no-interaction

# Testing

test: audit cs-check phpstan test-unit

test-unit:
	@docker exec -it -u ${CURRENT_UID}:${CURRENT_GID} ${PROJECT_NAME} ./vendor/bin/pest --colors

phpstan:
	@docker exec -it -u ${CURRENT_UID}:${CURRENT_GID} ${PROJECT_NAME} ./vendor/bin/phpstan analyse

cs-check:
	@docker exec -it -u ${CURRENT_UID}:${CURRENT_GID} ${PROJECT_NAME} ./vendor/bin/pint --test

cs-fix:
	@docker exec -it -u ${CURRENT_UID}:${CURRENT_GID} ${PROJECT_NAME} ./vendor/bin/pint

audit:
	@docker exec -it -u ${CURRENT_UID}:${CURRENT_GID} ${PROJECT_NAME} composer audit

# Connections

shell:
	@docker exec -it -u ${CURRENT_UID}:${CURRENT_GID} ${PROJECT_NAME} /bin/sh

# PHONY

composer:
	@docker exec -it -u ${CURRENT_UID}:${CURRENT_GID} ${PROJECT_NAME} composer $(filter-out $@,$(MAKECMDGOALS))

%:
	@:
