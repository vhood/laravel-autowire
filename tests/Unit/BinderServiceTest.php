<?php

namespace Vhood\Laravel\AutowireTests\Unit;

use Vhood\Laravel\AutowireTests\Fixtures\EloquentUserRepository;
use Vhood\Laravel\AutowireTests\Fixtures\UserRepository;

test('it automatically binds interfaces to implementations via attributes', function () {
    expect(app()->bound(UserRepository::class))->toBeTrue();

    $resolved = app(UserRepository::class);
    expect($resolved)->toBeInstanceOf(EloquentUserRepository::class);
});

test('it respects the shared option to create singletons', function () {
    $instance1 = app(UserRepository::class);
    $instance2 = app(UserRepository::class);

    expect($instance1)->toBe($instance2);
});
