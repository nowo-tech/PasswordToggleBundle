# Contributing Guide

Thank you for your interest in contributing to Password Toggle Bundle! This document provides guidelines for contributing to the project.

## Code of Conduct

This project adheres to a code of conduct. By participating, you are expected to uphold this code. Please report unacceptable behavior to hectorfranco@nowo.tech.

## How Can I Contribute?

### Reporting Bugs

If you find a bug, please:

1. **Check that the bug hasn't already been reported** in the [issues](https://github.com/nowo-tech/password-toggle-bundle/issues)
2. **Create a new issue** with:
   - A descriptive title
   - Steps to reproduce the problem
   - Expected behavior vs. actual behavior
   - PHP, Symfony, and bundle versions
   - Screenshots if relevant

### Suggesting Enhancements

Enhancement suggestions are welcome:

1. **Check that the enhancement hasn't already been suggested** in the [issues](https://github.com/nowo-tech/password-toggle-bundle/issues)
2. **Create a new issue** with:
   - A descriptive title
   - Detailed description of the proposed enhancement
   - Use cases and benefits
   - Possible implementations (if you have them)

### Contributing Code

#### Setting Up the Development Environment

1. **Fork the repository** on GitHub
2. **Clone your fork**:
   ```bash
   git clone https://github.com/your-username/password-toggle-bundle.git
   cd password-toggle-bundle
   ```
3. **Install dependencies**:
   ```bash
   # With Docker (recommended)
   make install
   
   # Without Docker
   composer install
   ```

#### Code Standards

The project follows these standards:

- **PSR-12**: PHP code style
- **PHP 8.1+**: Modern PHP features
- **Strict type hints**: `declare(strict_types=1);` in all files
- **PHP-CS-Fixer**: Used to maintain code consistency

**Before committing**:

```bash
# Check code style
make cs-check
# or
composer cs-check

# Fix code style automatically
make cs-fix
# or
composer cs-fix
```

#### Tests

**The project requires 100% code coverage**. All tests must pass before merging. New features should include tests.

```bash
# Run all tests
make test
# or
composer test

# Run tests with coverage
make test-coverage
# or
composer test-coverage

# View coverage report
open coverage/index.html
```

**Test structure**:
- Tests should be in the `tests/` directory
- Each class should have its corresponding test
- Tests should be descriptive and cover edge cases
- Use mocks when appropriate
- **All code must have 100% coverage** - new features must include comprehensive tests

#### Pull Request Process

1. **Create a branch** from `main`:
   ```bash
   git checkout -b feature/my-new-feature
   # or
   git checkout -b fix/my-bug-fix
   ```

2. **Make your changes**:
   - Write clean, well-documented code
   - Add tests for new features
   - Ensure all tests pass
   - Run `make qa` to verify everything

3. **Commit your changes**:
   ```bash
   git add .
   git commit -m "feat: description of feature"
   # or
   git commit -m "fix: description of fix"
   ```
   
   **Commit conventions**:
   - `feat:` New feature
   - `fix:` Bug fix
   - `docs:` Documentation changes
   - `test:` Add or modify tests
   - `refactor:` Code refactoring
   - `style:` Formatting changes (doesn't affect functionality)
   - `chore:` Maintenance tasks

4. **Push to your fork**:
   ```bash
   git push origin feature/my-new-feature
   ```

5. **Create a Pull Request** on GitHub:
   - Clearly describe the changes
   - Mention any related issues
   - Add screenshots if relevant
   - Ensure CI passes

#### Checklist Before PR

- [ ] Code follows PSR-12 standards
- [ ] Ran `make cs-fix` (or `composer cs-fix`)
- [ ] All tests pass (`make test`)
- [ ] Code coverage is 100% (`make test-coverage`)
- [ ] Added tests for new functionality
- [ ] Documentation is updated (if necessary)
- [ ] CHANGELOG.md is updated (if necessary)
- [ ] Code is well commented
- [ ] No warnings or errors from PHPStan/Psalm (if used)

## Project Structure

```
password-toggle-bundle/
├── src/                    # Bundle source code
│   ├── DependencyInjection/ # Bundle configuration
│   ├── Form/               # Form types
│   └── Resources/          # Resources (templates, assets)
├── tests/                  # Tests
├── demo/                   # Demo project
├── .github/                # GitHub configuration
└── docs/                   # Additional documentation (if any)
```

## Demos

The project includes a demo project to test the bundle:

- `demo/` - Demo Symfony project with Docker setup

To run the demo:

```bash
# Install dependencies
cd demo
make up
make install

# Start containers
docker-compose up -d

# Access the demo
# http://localhost:8080
```

## Questions

If you have questions about contributing, you can:

- Open an issue on GitHub
- Contact the maintainers at hectorfranco@nowo.tech

## Acknowledgments

Thank you for contributing to Password Toggle Bundle. Your help makes this project better for everyone.

