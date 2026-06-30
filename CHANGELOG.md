# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com),
and this project adheres to [Semantic Versioning](https://semver.org).

## [1.0.5](https://github.com/vhood/laravel-autowire/releases/tag/v1.0.5) - 2026-06-30

### Added

- Log warning (`Log::warning`) when production caching is enabled but the cache file does not exist, improving visibility for incorrect deployment setups.

### Changed

- Overhauled `README.md` to document the fallback caching behavior and modern service provider registration methods for Laravel 10 through 13.

## [1.0.4](https://github.com/vhood/laravel-autowire/releases/tag/v1.0.4) - 2026-06-29

### Fixed

- Resolved bootstrapping container crash (`Target class [env] does not exist`) in Laravel 13 by removing `app()->isProduction()` from the configuration file.

### Changed

- Refactored `BinderService` to dynamically evaluate the production environment, allowing explicit cache overriding via the `AUTOWIRE_CACHE` environment variable.

## [1.0.3](https://github.com/vhood/laravel-autowire/releases/tag/v1.0.3) - 2026-06-29

### Fixed

- Removed the global `env()` helper from the default configuration file to prevent container resolution conflicts (`Class "env" does not exist`) in Laravel 13.

## [1.0.2](https://github.com/vhood/laravel-autowire/releases/tag/v1.0.2) - 2026-06-29

### Added

- Official support for `symfony/finder:^8.0` to ensure compatibility with Laravel 13.

## [1.0.1](https://github.com/vhood/laravel-autowire/releases/tag/v1.0.1) - 2026-06-29

### Added

- Official support for Laravel 13.x and PHP 8.4 inside `composer.json`.
- Expanded GitHub Actions CI matrix to test against Laravel 13 and Pest 4.

## [1.0.0](https://github.com/vhood/laravel-autowire/releases/tag/v1.0.0) - 2026-06-29

### Added

- Initial release of the package.
- Core `#[Autowire]` attribute for clean infrastructure-level DI binding.
- `BinderService` powered by high-performance Composer `ClassMapGenerator`.
- Artisan commands `autowire:cache` and `autowire:clear` for production environments.
- Isolated Docker development environment and full Pest test suite.
