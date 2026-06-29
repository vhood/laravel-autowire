# Laravel Autowire Attributes

[![Latest Version on Packagist](https://img.shields.io/packagist/v/vhood/laravel-autowire)](https://packagist.org/packages/vhood/laravel-autowire)
[![Total Downloads](https://img.shields.io/packagist/dt/vhood/laravel-autowire)](https://packagist.org/packages/vhood/laravel-autowire)
[![Software License](https://img.shields.io/github/license/vhood/laravel-autowire)](LICENSE.md)

A lightweight, production-ready dependency injection autowiring package for Laravel using native PHP 8 attributes.

This package is **perfectly tailored for Domain-Driven Design (DDD)** and Clean Architecture, as it keeps your Domain and Application layers completely free from framework code and vendor dependencies.

## Why this package?

Unlike other packages, this tool scans **only your infrastructure implementations** where the `#[Autowire]` attribute is explicitly declared. Your domain interfaces remain 100% clean and unaware of the framework.

- **DDD-compliant:** No leak of infrastructure into Core/Domain layers.
- **High Performance:** Powered by Composer's native `ClassMapGenerator` (no slow regex file parsing) and includes full production caching.
- **Zero Configuration:** Works out of the box with Laravel's package auto-discovery.

## Installation

You can install the package via composer:

```bash
composer require vhood/laravel-autowire
```

_(Optional)_ You can publish the configuration file to customize the scan directories or cache path:

```bash
php artisan vendor:publish --tag="autowire-config"
```

## Usage Example (DDD Context)

### 1. Pure Domain Layer (Clean)

Your interface inside the Domain layer has absolutely no annotations or external dependencies:

```php
namespace Vhood\Domain\Member;

interface MemberRepository
{
    public function findById(int \$id): ?Member;
}
```

### 2. Infrastructure Layer

Apply the `#[Autowire]` attribute strictly to your concrete implementation classes within the Infrastructure layer:

```php
namespace Vhood\Infrastructure\Laravel\Repository\Member;

use Vhood\Domain\Member\MemberRepository;
use Vhood\Laravel\Autowire\Attributes\Autowire;

#[Autowire(abstract: MemberRepository::class, shared: true)]
class EloquentMemberRepository implements MemberRepository
{
    // Your Eloquent implementation here...
}
```

### 3. Service Provider Registration

Call the binder service once inside your application infrastructure service provider (e.g., `ApplicationServiceProvider`):

```php
namespace Vhood\Infrastructure\Laravel\Providers;

use Illuminate\Support\ServiceProvider;
use Vhood\Laravel\Autowire\Services\BinderService;

class ApplicationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Automatically boots up and binds all annotated classes
        \$this->app->make(BinderService::class)->bind();
    }
}
```

## Advanced Features

### Singletons (`shared` option)

By default, the package registers bindings using `$app->bind()`. If you need a singleton instance, pass `shared: true` to the attribute:

```php
#[Autowire(abstract: AppService::class, shared: true)]
```

### Production Caching

Scanning directories on every HTTP request can impact performance in production. To pre-compile all attribute mappings into a fast static PHP array, use the following Artisan commands in your deploy script:

```bash
# Pre-compile mappings to bootstrap/cache/
php artisan autowire:cache

# Clear compiled cache
php artisan autowire:clear
```

## Configuration (`config/autowire.php`)

```php
return [
    /**
     * Directories that the package will scan for the #[Autowire] attribute.
     */
    'scan_directories' => [
        base_path('src/Infrastructure'),
    ],

    /**
     * The absolute path where the generated binding map will be cached.
     */
    'cache_path' => base_path('bootstrap/cache/autowire_bindings.php'),

    /**
     * Determine if the binder should rely on the cached map file.
     */
    'use_cache' => env('AUTOWIRE_CACHE', app()->isProduction()),
];
```

## Development & Testing

This package provides a Docker-based isolated environment and a `Makefile` for quick setup and development. You don't need PHP installed on your local machine.

### Setup Environment

```bash
# Initialize the project from scratch (builds docker image and installs dependencies)
make init
```

### Running Tests and Quality Tools

Before submitting pull requests, ensure all quality checks and tests pass:

```bash
# Run all QA checks (Code style verification via Laravel Pint, PHPStan, and Pest tests)
make test

# Run only Unit tests via Pest
make test-unit

# Automatically fix code style flaws
make cs-fix

# Run static code analysis
make phpstan
```

### Working with Composer

If you need to install or update dependencies inside the container, you can forward commands directly through `make`:

```bash
make composer require vendor/package --dev
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
