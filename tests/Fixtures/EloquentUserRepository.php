<?php

declare(strict_types=1);

namespace Vhood\Laravel\AutowireTests\Fixtures;

use Vhood\Laravel\Autowire\Attributes\Autowire;

#[Autowire(abstract: UserRepository::class, shared: true)]
class EloquentUserRepository implements UserRepository {}
