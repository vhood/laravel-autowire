# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com),
and this project adheres to [Semantic Versioning](https://semver.org).

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
