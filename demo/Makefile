# Makefile for Password Toggle Bundle Demo
# Each demo has its own docker-compose.yml and can be run independently

.PHONY: help up-symfony6 up-symfony7 up-symfony8 up-symfony8-php85 down-symfony6 down-symfony7 down-symfony8 down-symfony8-php85 install-symfony6 install-symfony7 install-symfony8 install-symfony8-php85 shell-symfony6 shell-symfony7 shell-symfony8 shell-symfony8-php85 logs-symfony6 logs-symfony7 logs-symfony8 logs-symfony8-php85 test-symfony6 test-symfony7 test-symfony8 test-symfony8-php85 test-all clean

# Default target
help:
	@echo "Password Toggle Bundle - Demo Commands"
	@echo ""
	@echo "Usage: make <target>"
	@echo ""
	@echo "Symfony 6.4 Demo (Port 8001, PHP 8.2):"
	@echo "  up-symfony6        Start Symfony 6.4 demo containers"
	@echo "  down-symfony6      Stop Symfony 6.4 demo containers"
	@echo "  install-symfony6   Install dependencies for Symfony 6.4 demo"
	@echo "  shell-symfony6     Open shell in Symfony 6.4 PHP container"
	@echo "  logs-symfony6      Show Symfony 6.4 container logs"
	@echo ""
	@echo "Symfony 7.0 Demo (Port 8001, PHP 8.2):"
	@echo "  up-symfony7        Start Symfony 7.0 demo containers"
	@echo "  down-symfony7      Stop Symfony 7.0 demo containers"
	@echo "  install-symfony7   Install dependencies for Symfony 7.0 demo"
	@echo "  shell-symfony7     Open shell in Symfony 7.0 PHP container"
	@echo "  logs-symfony7      Show Symfony 7.0 container logs"
	@echo ""
	@echo "Symfony 8.0 Demo (Port 8001, PHP 8.4):"
	@echo "  up-symfony8        Start Symfony 8.0 demo containers"
	@echo "  down-symfony8      Stop Symfony 8.0 demo containers"
	@echo "  install-symfony8   Install dependencies for Symfony 8.0 demo"
	@echo "  shell-symfony8     Open shell in Symfony 8.0 PHP container"
	@echo "  logs-symfony8      Show Symfony 8.0 container logs"
	@echo ""
	@echo "Symfony 8.0 Demo with PHP 8.5 (Port 8001):"
	@echo "  up-symfony8-php85        Start Symfony 8.0 + PHP 8.5 demo containers"
	@echo "  down-symfony8-php85      Stop Symfony 8.0 + PHP 8.5 demo containers"
	@echo "  install-symfony8-php85   Install dependencies for Symfony 8.0 + PHP 8.5 demo"
	@echo "  shell-symfony8-php85     Open shell in Symfony 8.0 + PHP 8.5 PHP container"
	@echo "  logs-symfony8-php85      Show Symfony 8.0 + PHP 8.5 container logs"
	@echo ""
	@echo "Testing:"
	@echo "  test-symfony6      Run tests for Symfony 6.4 demo"
	@echo "  test-symfony7      Run tests for Symfony 7.0 demo"
	@echo "  test-symfony8      Run tests for Symfony 8.0 demo"
	@echo "  test-symfony8-php85 Run tests for Symfony 8.0 + PHP 8.5 demo"
	@echo "  test-all           Run tests for all demos"
	@echo ""
	@echo "Other commands:"
	@echo "  clean              Remove vendor and cache from all demos"
	@echo ""
	@echo "Access URLs:"
	@echo "  Symfony 6.4: http://localhost:8001"
	@echo "  Symfony 7.0: http://localhost:8001"
	@echo "  Symfony 8.0: http://localhost:8001"
	@echo "  Symfony 8.0 + PHP 8.5: http://localhost:8001"

# Symfony 6.4 Demo
up-symfony6:
	@echo "Building Docker image for Symfony 6.4 demo..."
	cd demo-symfony6 && docker-compose build
	@echo "Starting Symfony 6.4 demo..."
	cd demo-symfony6 && docker-compose up -d
	@echo "✅ Symfony 6.4 demo started!"
	@echo "🌐 Access at: http://localhost:8001"

down-symfony6:
	@echo "Stopping Symfony 6.4 demo..."
	cd demo-symfony6 && docker-compose down
	@echo "✅ Symfony 6.4 demo stopped!"

install-symfony6:
	@echo "Installing Composer dependencies for Symfony 6.4..."
	cd demo-symfony6 && docker-compose exec -T php composer update --no-interaction
	@echo "✅ Symfony 6.4 dependencies installed!"
	@echo "🌐 Access at: http://localhost:8001"

shell-symfony6:
	cd demo-symfony6 && docker-compose exec php sh

logs-symfony6:
	cd demo-symfony6 && docker-compose logs -f

# Symfony 7.0 Demo
up-symfony7:
	@echo "Building Docker image for Symfony 7.0 demo..."
	cd demo-symfony7 && docker-compose build
	@echo "Starting Symfony 7.0 demo..."
	cd demo-symfony7 && docker-compose up -d
	@echo "✅ Symfony 7.0 demo started!"
	@echo "🌐 Access at: http://localhost:8001"

down-symfony7:
	@echo "Stopping Symfony 7.0 demo..."
	cd demo-symfony7 && docker-compose down
	@echo "✅ Symfony 7.0 demo stopped!"

install-symfony7:
	@echo "Installing Composer dependencies for Symfony 7.0..."
	cd demo-symfony7 && docker-compose exec -T php composer update --no-interaction
	@echo "✅ Symfony 7.0 dependencies installed!"
	@echo "🌐 Access at: http://localhost:8001"

shell-symfony7:
	cd demo-symfony7 && docker-compose exec php sh

logs-symfony7:
	cd demo-symfony7 && docker-compose logs -f

# Symfony 8.0 Demo
up-symfony8:
	@echo "Building Docker image for Symfony 8.0 demo..."
	cd demo-symfony8 && docker-compose build
	@echo "Starting Symfony 8.0 demo..."
	cd demo-symfony8 && docker-compose up -d
	@echo "✅ Symfony 8.0 demo started!"
	@echo "🌐 Access at: http://localhost:8001"

down-symfony8:
	@echo "Stopping Symfony 8.0 demo..."
	cd demo-symfony8 && docker-compose down
	@echo "✅ Symfony 8.0 demo stopped!"

install-symfony8:
	@echo "Installing Composer dependencies for Symfony 8.0..."
	cd demo-symfony8 && docker-compose exec -T php composer update --no-interaction
	@echo "✅ Symfony 8.0 dependencies installed!"
	@echo "🌐 Access at: http://localhost:8001"

shell-symfony8:
	cd demo-symfony8 && docker-compose exec php sh

logs-symfony8:
	cd demo-symfony8 && docker-compose logs -f

# Symfony 8.0 Demo with PHP 8.5
up-symfony8-php85:
	@echo "Building Docker image for Symfony 8.0 + PHP 8.5 demo..."
	cd demo-symfony8-php85 && docker-compose build
	@echo "Starting Symfony 8.0 + PHP 8.5 demo..."
	cd demo-symfony8-php85 && docker-compose up -d
	@echo "✅ Symfony 8.0 + PHP 8.5 demo started!"
	@echo "🌐 Access at: http://localhost:8001"

down-symfony8-php85:
	@echo "Stopping Symfony 8.0 + PHP 8.5 demo..."
	cd demo-symfony8-php85 && docker-compose down
	@echo "✅ Symfony 8.0 + PHP 8.5 demo stopped!"

install-symfony8-php85:
	@echo "Installing Composer dependencies for Symfony 8.0 + PHP 8.5..."
	cd demo-symfony8-php85 && docker-compose exec -T php composer update --no-interaction
	@echo "✅ Symfony 8.0 + PHP 8.5 dependencies installed!"
	@echo "🌐 Access at: http://localhost:8001"

shell-symfony8-php85:
	cd demo-symfony8-php85 && docker-compose exec php sh

logs-symfony8-php85:
	cd demo-symfony8-php85 && docker-compose logs -f

# Run tests for Symfony 6.4 demo
test-symfony6:
	@echo "Running tests for Symfony 6.4 demo..."
	cd demo-symfony6 && docker-compose exec -T php vendor/bin/phpunit
	@echo "✅ Tests completed!"

# Run tests for Symfony 7.0 demo
test-symfony7:
	@echo "Running tests for Symfony 7.0 demo..."
	cd demo-symfony7 && docker-compose exec -T php vendor/bin/phpunit
	@echo "✅ Tests completed!"

# Run tests for Symfony 8.0 demo
test-symfony8:
	@echo "Running tests for Symfony 8.0 demo..."
	cd demo-symfony8 && docker-compose exec -T php vendor/bin/phpunit
	@echo "✅ Tests completed!"

# Run tests for Symfony 8.0 + PHP 8.5 demo
test-symfony8-php85:
	@echo "Running tests for Symfony 8.0 + PHP 8.5 demo..."
	cd demo-symfony8-php85 && docker-compose exec -T php vendor/bin/phpunit
	@echo "✅ Tests completed!"

# Run all tests
test-all:
	@echo "Running tests for all demos..."
	@$(MAKE) test-symfony6
	@$(MAKE) test-symfony7
	@$(MAKE) test-symfony8
	@$(MAKE) test-symfony8-php85
	@echo "✅ All tests completed!"

# Clean vendor and cache from all demos
clean:
	@echo "Cleaning vendor and cache from all demos..."
	rm -rf demo-symfony6/vendor demo-symfony6/var demo-symfony6/.phpunit.cache
	rm -rf demo-symfony7/vendor demo-symfony7/var demo-symfony7/.phpunit.cache
	rm -rf demo-symfony8/vendor demo-symfony8/var demo-symfony8/.phpunit.cache
	rm -rf demo-symfony8-php85/vendor demo-symfony8-php85/var demo-symfony8-php85/.phpunit.cache
	@echo "✅ Cleaned!"
