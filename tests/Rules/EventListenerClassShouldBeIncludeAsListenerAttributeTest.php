<?php

declare(strict_types=1);

namespace Simtel\PHPStanRules\Tests\Rules;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Simtel\PHPStanRules\Rule\EventListenerClassShouldBeIncludeAsListenerAttribute;

class EventListenerClassShouldBeIncludeAsListenerAttributeTest extends RuleTestCase
{
    /**
     * @inheritDoc
     */
    protected function getRule(): Rule
    {
        return new EventListenerClassShouldBeIncludeAsListenerAttribute($this->createReflectionProvider());
    }

    public function testExistsNeedAttribute(): void
    {
        $this->analyse([__DIR__ . '/../Fixture/EventListener/TestClassEventListener.php'], []);
    }

    public function testExistsAttribute(): void
    {
        $this->analyse([__DIR__ . '/../Fixture/EventListener/TestNotCorrectClassEventListener.php'], [
            ['Event listener class should be include attribute #[AsEventListener]', 7]
        ]);
    }
}
