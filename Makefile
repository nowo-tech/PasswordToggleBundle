# Makefile for Password Toggle Bundle
# Simplifies Docker commands for development.
# All dev targets use the root docker-compose.yml (single file).

COMPOSE_FILE := docker-compose.yml
COMPOSE := docker-compose -f $(COMPOSE_FILE)
SERVICE_PHP := php

.PHONY: help up down build shell install test test-coverage cs-check cs-fix rector rector-dry phpstan qa release-check release-check-demos composer-sync clean update validate assets setup-hooks

# Default target
help:
	@echo "Password Toggle Bundle - Development Commands"
	@echo ""
	@echo "Usage: make <target>"
	@echo ""
	@echo "Targets:"
	@echo "  up            Start Docker container"
	@echo "  down          Stop Docker container"
	@echo "  build         Rebuild Docker image (no cache)"
	@echo "  shell         Open shell in container"
	@echo "  install       Install Composer dependencies (starts container if needed)"
	@echo "  test          Run PHPUnit tests"
	@echo "  test-coverage Run tests with code coverage"
	@echo "  cs-check      Check code style"
	@echo "  cs-fix        Fix code style"
	@echo "  rector        Apply Rector refactoring"
	@echo "  rector-dry    Run Rector in dry-run mode"
	@echo "  phpstan       Run PHPStan static analysis"
	@echo "  qa            Run all QA checks (cs-check + test)"
	@echo "  release-check Pre-release: cs-fix, cs-check, rector-dry, phpstan, test-coverage, demo healthchecks"
	@echo "  composer-sync Validate composer.json and align composer.lock (no install)"
	@echo "  clean         Remove vendor and cache"
	@echo "  update        Update composer.lock (composer update)"
	@echo "  validate      Run composer validate --strict"
	@echo "  assets        No-op (no frontend assets in this bundle)"
	@echo "  setup-hooks   Install git pre-commit hooks"
	@echo ""
	@echo "Demos: use make -C demo or make -C demo/<demo-name>"
	@echo ""

# Rebuild Docker image (no cache)
build:
	$(COMPOSE) build --no-cache

# Build and start container
up:
	$(COMPOSE) build
	$(COMPOSE) up -d
	@echo "Waiting for container to be ready..."
	@sleep 2
	@echo "Installing dependencies..."
	$(COMPOSE) exec -T $(SERVICE_PHP) composer install --no-interaction
	@echo "✅ Container ready!"

# Stop container
down:
	$(COMPOSE) down

# Open shell in container
shell: ensure-up
	$(COMPOSE) exec $(SERVICE_PHP) sh

# Ensure container is running (start if not). Used by install, test, cs-check, cs-fix, qa, rector, phpstan.
ensure-up:
	@if ! $(COMPOSE) exec -T $(SERVICE_PHP) true 2>/dev/null; then \
		echo "Starting container..."; \
		$(COMPOSE) up -d; \
		sleep 3; \
		$(COMPOSE) exec -T $(SERVICE_PHP) composer install --no-interaction; \
	fi

# Install dependencies
install: ensure-up
	$(COMPOSE) exec -T $(SERVICE_PHP) composer install

# Run tests (no -T so PHPUnit shows colors in console)
test: ensure-up
	$(COMPOSE) exec $(SERVICE_PHP) composer test

# Run tests with coverage (no -T so coverage is shown in console with colors)
test-coverage: ensure-up
	$(COMPOSE) exec $(SERVICE_PHP) composer test-coverage

# Check code style
cs-check: ensure-up
	$(COMPOSE) exec -T $(SERVICE_PHP) composer cs-check

# Fix code style
cs-fix: ensure-up
	$(COMPOSE) exec -T $(SERVICE_PHP) composer cs-fix

# Rector
rector: ensure-up
	$(COMPOSE) exec -T $(SERVICE_PHP) composer rector

# Rector dry-run
rector-dry: ensure-up
	$(COMPOSE) exec -T $(SERVICE_PHP) composer rector-dry

# PHPStan
phpstan: ensure-up
	$(COMPOSE) exec -T $(SERVICE_PHP) composer phpstan

# Run all QA
qa: ensure-up
	$(COMPOSE) exec -T $(SERVICE_PHP) composer qa

# Pre-release checks (no demos healthcheck if demo/Makefile has no release-verify; optional)
release-check: cs-fix cs-check rector-dry phpstan test-coverage release-check-demos

release-check-demos:
	@if [ -f demo/Makefile ]; then $(MAKE) -C demo release-check 2>/dev/null || true; else true; fi

# Validate composer and sync lock (no install)
composer-sync: ensure-up
	$(COMPOSE) exec -T $(SERVICE_PHP) composer validate --strict
	$(COMPOSE) exec -T $(SERVICE_PHP) composer update --lock --no-install

# Clean vendor and cache
clean:
	rm -rf vendor
	rm -rf .phpunit.cache
	rm -rf coverage
	rm -f coverage.xml
	rm -f .php-cs-fixer.cache

# Update composer.lock
update: ensure-up
	$(COMPOSE) exec -T $(SERVICE_PHP) composer update

# Validate composer.json
validate: ensure-up
	$(COMPOSE) exec -T $(SERVICE_PHP) composer validate --strict

# No-op for bundles without frontend assets
assets:
	@echo "No frontend assets in this bundle."

# Setup git hooks for pre-commit checks
setup-hooks:
	chmod +x .githooks/pre-commit
	git config core.hooksPath .githooks
	@echo "✅ Git hooks installed! CS-check and tests will run before each commit."
