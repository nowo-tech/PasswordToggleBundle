# Makefile for Password Toggle Bundle
# Simplifies Docker commands for development.
# All dev targets use the root docker-compose.yml (single file).

COMPOSE_FILE := docker-compose.yml
# Prefer Compose V2 plugin (GitHub Actions / modern Docker Desktop); fall back to docker-compose V1 (REQ-MAKE-010).
COMPOSE_BIN := $(shell docker compose version >/dev/null 2>&1 && echo "docker compose" || echo "docker-compose")
COMPOSE     := $(COMPOSE_BIN) -f $(COMPOSE_FILE)
SERVICE_PHP := php

.PHONY: help up down down-dev build shell install test test-coverage coverage-php-percent cs-check cs-fix rector rector-dry phpstan qa release-check release-check-demos demo-smoke composer-sync clean update validate assets setup-hooks check-no-cursor-coauthor strip-cursor-coauthor-from-history check-twig-extra

# Default target
help:
	@echo "Password Toggle Bundle - Development Commands"
	@echo ""
	@echo "Usage: make <target>"
	@echo ""
	@echo "Targets:"
	@echo "  up            Start Docker container"
	@echo "  down          Stop Docker container"
	@echo "  down-dev      Stop root $(COMPOSE) (dev) and remove orphans"
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
	@echo "  demo-smoke    REQ-TEST-011: boot demo + HTTP 200 (make -C demo demo-smoke)"
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

down-dev:
	$(COMPOSE) down --remove-orphans

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
	$(COMPOSE) exec $(SERVICE_PHP) composer test-coverage | tee coverage-php.txt
	./.scripts/php-coverage-percent.sh coverage-php.txt

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

check-twig-extra:
	@chmod +x .scripts/check-twig-extra.sh
	@./.scripts/check-twig-extra.sh
release-check: check-no-cursor-coauthor check-twig-extra ensure-up composer-sync cs-fix cs-check rector-dry phpstan test-coverage release-check-demos

release-check-demos:
	@if [ -f demo/Makefile ]; then $(MAKE) -C demo release-check; else echo "No demo/Makefile — skip release-check-demos"; fi

demo-smoke:
	@if [ -f demo/Makefile ]; then $(MAKE) -C demo demo-smoke; else echo "No demo/Makefile — skip demo-smoke"; fi

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
check-no-cursor-coauthor:
	@chmod +x .scripts/check-no-cursor-coauthor.sh
	@./.scripts/check-no-cursor-coauthor.sh HEAD

setup-hooks:
	@chmod +x .githooks/pre-commit 2>/dev/null || true
	@chmod +x .githooks/commit-msg 2>/dev/null || true
	@git config core.hooksPath .githooks
	@echo "✅ Git hooks installed (.githooks — includes commit-msg for REQ-GIT-001)."


# REQ-MAKE-008: update-deps (REQ-MAKE-008)
BUNDLE_ROOT := $(abspath $(dir $(lastword $(MAKEFILE_LIST))))
# Optional: monorepo helper absent on standalone GitHub Actions checkout (REQ-MAKE-009).
-include $(BUNDLE_ROOT)/../.scripts/Makefile.update-deps.mk

strip-cursor-coauthor-from-history:
	@chmod +x .scripts/strip-cursor-coauthor-from-history.sh
	@./.scripts/strip-cursor-coauthor-from-history.sh main

twig-lint: ensure-up
	@$(COMPOSE) exec -T $(SERVICE_PHP) composer twig:lint || $(COMPOSE) exec -T $(SERVICE_PHP) ./vendor/bin/twig-cs-fixer lint --config=.twig-cs-fixer.php
