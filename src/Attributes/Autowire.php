<?php

declare(strict_types=1);

namespace Vhood\Laravel\Autowire\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Autowire
{
    public function __construct(
        public string $abstract,
        public bool $shared = false,
    ) {}
}
