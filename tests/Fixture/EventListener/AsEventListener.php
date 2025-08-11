<?php

declare(strict_types=1);

namespace Simtel\PHPStanRules\Tests\Fixture\EventListener;

use Attribute;

#[Attribute]
class AsEventListener
{
    public function __construct()
    {
    }
}
