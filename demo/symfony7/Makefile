# Makefile for Symfony 7.0 Demo (Password Toggle Bundle)
# Run from this directory: make up, make install, etc.

.PHONY: help up down build install shell logs test test-coverage cache-clear update-bundle ensure-up restart

help:
	@echo "Symfony 7.0 Demo - Password Toggle Bundle"
	@echo ""
	@echo "  make up             Start containers"
	@echo "  make down           Stop containers"
	@echo "  make build          Build images (no cache)"
	@echo "  make install        Install dependencies (composer install in container)"
	@echo "  make shell          Open shell in PHP container"
	@echo "  make logs           Show container logs"
	@echo "  make test           Run tests"
	@echo "  make test-coverage  Run tests with coverage"
	@echo "  make cache-clear    Clear Symfony cache"
	@echo "  make update-bundle  Update nowo-tech/password-toggle-bundle and clear cache"
	@echo ""

up:
	@if [ ! -f .env ]; then cp .env.example .env 2>/dev/null || true; fi
	@$(call run-compose,up -d)
	@echo "Waiting for container to be ready..."
	@sleep 5
	@echo "Installing dependencies..."
	@$(call run-compose,exec -T -e COMPOSER_MEMORY_LIMIT=-1 php composer install --no-interaction)
	@PORT=$$(grep "^PORT=" .env 2>/dev/null | cut -d= -f2 | tr -d '\r'); \
	[ -z "$$PORT" ] && PORT=$$(grep "^PORT=" .env.example 2>/dev/null | cut -d= -f2 | tr -d '\r'); \
	echo "Demo started at: http://localhost:$$PORT"
down:
	@$(call run-compose,down)

restart: ensure-up
	@$(call run-compose,restart)

build:
	@$(call run-compose,build --no-cache)

ensure-up:
	@if ! $(COMPOSE) exec -T php true 2>/dev/null; then \
		echo "Starting container..."; \
		$(COMPOSE) up -d; \
		sleep 3; \
	fi

install: ensure-up
	@$(call run-compose,exec -T -e COMPOSER_MEMORY_LIMIT=-1 php composer install --no-interaction)

shell: ensure-up
	@$(call run-compose,exec php sh)

logs:
	@$(call run-compose,logs -f)

test: ensure-up
	@$(call run-compose,exec -e APP_ENV=test php composer test)

test-coverage: ensure-up
	@$(call run-compose,exec -e APP_ENV=test php composer test-coverage)

cache-clear: ensure-up
	@$(call run-compose,exec php php bin/console cache:clear)

update-bundle: ensure-up
	@$(call run-compose,exec -T php composer dump-autoload --optimize)
	@$(call run-compose,exec php php bin/console cache:clear --env=dev --no-warmup) || true
	@echo "Bundle updated. Reload the page in the browser."


# REQ-MAKE-008: update-deps (REQ-MAKE-008)
# Prefer Compose V2 plugin (GitHub Actions / modern Docker Desktop); fall back to docker-compose V1 (REQ-MAKE-010).
COMPOSE := $(shell docker compose version >/dev/null 2>&1 && echo "docker compose" || echo "docker-compose")
# WSL Docker Desktop: bare execve of `docker` can return EACCES; always run Compose via shell.
run-compose = $(SHELL) -c '$(COMPOSE) $(1)'
SERVICE_PHP := php
BUNDLE_ROOT := $(abspath $(dir $(lastword $(MAKEFILE_LIST)))/../..)
# Optional: monorepo helper absent on standalone GitHub Actions checkout (REQ-MAKE-009).
-include $(BUNDLE_ROOT)/../.scripts/Makefile.demo-update-deps.mk
