<?php

declare(strict_types=1);

namespace Simtel\PHPStanRules\Tests\Fixture\EventListener;

#[AsEventListener]
class TestClassEventListener
{
    public function method(): void
    {
    }
}
