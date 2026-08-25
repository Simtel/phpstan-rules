<?php

declare(strict_types=1);

namespace Simtel\PHPStanRules\Tests\Fixture\Return;

class MethodsWithComplexTypes
{
    public function union(): int|string
    {
        return 1;
    }

    /**
     * @return int
     */
    public function nullable(): ?int
    {
        return null;
    }

    /**
     * @return array<string, int>
     */
    public function generic(): array
    {
        return [];
    }
}
