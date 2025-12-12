# Password Toggle Bundle - Demo

This directory contains four demo projects demonstrating the usage of the Password Toggle Bundle with different Symfony and PHP versions.

## Features

- Four separate demo projects for different Symfony/PHP combinations
- Simple login form with password toggle functionality
- Bootstrap 5 styling
- Tabler icons for the toggle button
- Docker setup for easy development
- Independent Docker containers for each demo

## Demo Projects

The bundle includes four demo projects:

1. **Symfony 6.4 Demo** - PHP 8.2 (Port 8001 by default, configurable via `.env`)
2. **Symfony 7.0 Demo** - PHP 8.2 (Port 8001 by default, configurable via `.env`)
3. **Symfony 8.0 Demo** - PHP 8.4 (Port 8001 by default, configurable via `.env`)
4. **Symfony 8.0 Demo with PHP 8.5** - PHP 8.5 (Port 8001 by default, configurable via `.env`)

## Requirements

- Docker and Docker Compose
- Or PHP 8.1+ to 8.5 (8.2+ for Symfony 7.0+, 8.4+ for Symfony 8.0) and Composer (for local development)

## Quick Start with Docker

Each demo has its own `docker-compose.yml` and can be run independently. You can start any demo you want:

### Symfony 6.4 Demo (PHP 8.2)

```bash
# Navigate to the demo directory
cd demo/demo-symfony6

# Start containers
docker-compose up -d

# Install dependencies
docker-compose exec php composer install

# Access at: http://localhost:8001 (default port, configurable via PORT env variable)
```

Or using the Makefile from the `demo/` directory:

```bash
cd demo
make up-symfony6
make install-symfony6
```

### Symfony 7.0 Demo (PHP 8.2)

```bash
# Navigate to the demo directory
cd demo/demo-symfony7

# Start containers
docker-compose up -d

# Install dependencies
docker-compose exec php composer install

# Access at: http://localhost:8001 (default port, configurable via PORT env variable)
```

Or using the Makefile:

```bash
cd demo
make up-symfony7
make install-symfony7
```

### Symfony 8.0 Demo (PHP 8.4)

```bash
# Navigate to the demo directory
cd demo/demo-symfony8

# Start containers
docker-compose up -d

# Install dependencies
docker-compose exec php composer install

# Access at: http://localhost:8001 (default port, configurable via PORT env variable)
```

Or using the Makefile:

```bash
cd demo
make up-symfony8
make install-symfony8
```

### Symfony 8.0 Demo with PHP 8.5

```bash
# Navigate to the demo directory
cd demo/demo-symfony8-php85

# Start containers
docker-compose up -d

# Install dependencies
docker-compose exec php composer install

# Access at: http://localhost:8001 (default port, configurable via PORT env variable)
```

Or using the Makefile:

```bash
cd demo
make up-symfony8-php85
make install-symfony8-php85
```

### Stop Containers

Stop a specific demo:

```bash
# Stop Symfony 6.4 demo
cd demo/demo-symfony6
docker-compose down

# Or using Makefile
cd demo
make down-symfony6
```

Similar commands for other demos:
- Symfony 7.0: `make down-symfony7`
- Symfony 8.0: `make down-symfony8`
- Symfony 8.0 + PHP 8.5: `make down-symfony8-php85`

## Local Development (without Docker)

### Symfony 6.4 Demo

1. **Navigate to the demo directory:**
   ```bash
   cd demo/demo-symfony6
   ```

2. **Install dependencies:**
   ```bash
   composer install
   ```

3. **Start the Symfony server:**
   ```bash
   symfony server:start
   ```

### Symfony 7.0 Demo

1. **Navigate to the demo directory:**
   ```bash
   cd demo/demo-symfony7
   ```

2. **Install dependencies:**
   ```bash
   composer install
   ```

3. **Start the Symfony server:**
   ```bash
   symfony server:start
   ```

### Symfony 8.0 Demo

1. **Navigate to the demo directory:**
   ```bash
   cd demo/demo-symfony8
   ```

2. **Install dependencies:**
   ```bash
   composer install
   ```

3. **Start the Symfony server:**
   ```bash
   symfony server:start
   ```

### Symfony 8.0 Demo with PHP 8.5

1. **Navigate to the demo directory:**
   ```bash
   cd demo/demo-symfony8-php85
   ```

2. **Install dependencies:**
   ```bash
   composer install
   ```

3. **Start the Symfony server:**
   ```bash
   symfony server:start
   ```

## What's Included

Each demo includes:

- **DemoController**: A simple controller with a form that uses the PasswordType
- **Form Template**: A Bootstrap-styled form template showing the password toggle in action
- **Docker Setup**: Complete Docker configuration with PHP-FPM and Nginx
- **Dockerfile**: Custom PHP-FPM image with Composer pre-installed
- **Test Suite**: Complete PHPUnit test suite to verify bundle integration

## Demo Structure

```
demo/
├── demo-symfony6/          # Symfony 6.4 demo (Port 8001 by default, PHP 8.2)
│   ├── docker-compose.yml  # Independent docker-compose for this demo
│   ├── Dockerfile          # PHP 8.2-FPM image with Composer
│   ├── nginx.conf          # Nginx configuration
│   ├── composer.json       # Dependencies for Symfony 6.4
│   ├── .env                # Port configuration (default: 8001)
│   ├── .env.example        # Example port configuration file
│   └── ...
├── demo-symfony7/          # Symfony 7.0 demo (Port 8001 by default, PHP 8.2)
│   ├── docker-compose.yml  # Independent docker-compose for this demo
│   ├── Dockerfile          # PHP 8.2-FPM image with Composer
│   ├── nginx.conf          # Nginx configuration
│   ├── composer.json       # Dependencies for Symfony 7.0
│   ├── .env                # Port configuration (default: 8001)
│   ├── .env.example        # Example port configuration file
│   └── ...
├── demo-symfony8/          # Symfony 8.0 demo (Port 8001 by default, PHP 8.4)
│   ├── docker-compose.yml  # Independent docker-compose for this demo
│   ├── Dockerfile          # PHP 8.4-FPM image with Composer
│   ├── nginx.conf          # Nginx configuration
│   ├── composer.json       # Dependencies for Symfony 8.0
│   ├── .env                # Port configuration (default: 8001)
│   ├── .env.example        # Example port configuration file
│   └── ...
├── demo-symfony8-php85/    # Symfony 8.0 demo with PHP 8.5 (Port 8001 by default)
│   ├── docker-compose.yml  # Independent docker-compose for this demo
│   ├── Dockerfile          # PHP 8.5-FPM image with Composer
│   ├── nginx.conf          # Nginx configuration
│   ├── composer.json       # Dependencies for Symfony 8.0
│   ├── .env                # Port configuration (default: 8001)
│   ├── .env.example        # Example port configuration file
│   └── ...
└── Makefile                # Helper commands for all demos
```

Each demo is completely independent with its own `docker-compose.yml` and `nginx.conf`.

## How It Works

The demo uses the `PasswordType` form type from the bundle:

```php
->add('password', PasswordType::class, [
    'label' => 'Password',
    'toggle' => true,
    'visible_icon' => 'tabler:eye-off',
    'hidden_icon' => 'tabler:eye',
    'visible_label' => 'Show password',
    'hidden_label' => 'Hide password',
])
```

The form automatically includes:
- A password input field
- A toggle button with eye icons
- JavaScript functionality to show/hide the password
- Accessibility labels (ARIA)

## Customization

You can customize the password field by modifying the options in each demo's `src/Controller/DemoController.php`:

- `toggle`: Enable/disable toggle functionality
- `visible_icon`: Icon when password is hidden
- `hidden_icon`: Icon when password is visible
- `visible_label`: Label when password is hidden
- `hidden_label`: Label when password is visible
- `button_classes`: CSS classes for the toggle button
- `toggle_container_classes`: CSS classes for the container

## Port Configuration

Each demo includes a `.env` file with the default port configuration:

- **Symfony 6.4**: Port 8001 (configured in `demo-symfony6/.env`)
- **Symfony 7.0**: Port 8001 (configured in `demo-symfony7/.env`)
- **Symfony 8.0**: Port 8001 (configured in `demo-symfony8/.env`)
- **Symfony 8.0 + PHP 8.5**: Port 8001 (configured in `demo-symfony8-php85/.env`)

### Changing the Port

If a port is already in use, you can customize it by editing the `.env` file in the demo directory:

```bash
# Edit the .env file
cd demo/demo-symfony6
nano .env  # or use your preferred editor

# Change the PORT value
PORT=8080
```

Then restart the containers:

```bash
docker-compose down
docker-compose up -d
```

The `docker-compose.yml` files use `${PORT:-8001}` syntax, which means:
- If `PORT` is set in the `.env` file, it will use that value
- If `PORT` is not set, it will use the default value (8001 for all demos)

You can also override the port temporarily using an environment variable:

```bash
PORT=8080 docker-compose up -d
```

## Troubleshooting

### Composer install fails

Make sure the bundle is properly linked. The demos use a path repository to link to the parent bundle. If you're running this outside the bundle directory, you may need to adjust the repository path in `composer.json`.

### PHP version compatibility

Make sure you're using the correct PHP version for each demo:
- Symfony 6.4: PHP >= 8.1
- Symfony 7.0: PHP >= 8.2
- Symfony 8.0: PHP >= 8.4

The Dockerfiles are configured with the correct PHP versions, so using Docker is recommended.

### Port already in use

If port 8001 is already in use, you can change it by setting the `PORT` environment variable:

```bash
# Stop the containers first
cd demo/demo-symfony8
docker-compose down

# Start with a different port
PORT=8002 docker-compose up -d
```

Or edit the `.env` file in the demo directory to set a permanent port.

### Nginx configuration issues

If you encounter "File not found" errors, make sure:
- The nginx configuration uses the correct path: `/app/public$fastcgi_script_name`
- The containers are running: `docker-compose ps`
- The cache is cleared: `docker-compose exec php php bin/console cache:clear`

### Routes not loading

If routes are not loading, verify:
- The `routes.yaml` file includes the controllers configuration for attribute-based routing
- The controller uses the `#[Route]` attribute correctly
- The cache is cleared after configuration changes

## Testing

Each demo includes its own test suite to verify that the Password Toggle Bundle works correctly with the specific Symfony version.

### Run Tests

```bash
# Run tests for Symfony 6.4 demo
cd demo/demo-symfony6
docker-compose exec php vendor/bin/phpunit

# Run tests for Symfony 7.0 demo
cd demo/demo-symfony7
docker-compose exec php vendor/bin/phpunit

# Run tests for Symfony 8.0 demo
cd demo/demo-symfony8
docker-compose exec php vendor/bin/phpunit

# Run tests for Symfony 8.0 + PHP 8.5 demo
cd demo/demo-symfony8-php85
docker-compose exec php vendor/bin/phpunit
```

Or using the Makefile from the `demo/` directory:

```bash
cd demo

# Run tests for a specific demo
make test-symfony6
make test-symfony7
make test-symfony8
make test-symfony8-php85

# Run all tests
make test-all
```

### Test Structure

Each demo includes:
- **Controller Tests**: Verify that the demo controller works correctly
- **Bundle Integration Tests**: Verify that the Password Toggle Bundle is properly integrated
- Tests verify:
  - Form page accessibility
  - Form fields presence (username, password)
  - Password toggle functionality (icons, buttons)
  - Form submission
  - Bundle registration

## License

This demo is part of the Password Toggle Bundle project and follows the same MIT license.
