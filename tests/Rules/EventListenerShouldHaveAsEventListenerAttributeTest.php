<?php

declare(strict_types=1);

namespace Simtel\PHPStanRules\Tests\Rules;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Simtel\PHPStanRules\Rule\EventListenerShouldHaveAsEventListenerAttribute;
use Simtel\PHPStanRules\Rule\RuleMessages;

class EventListenerShouldHaveAsEventListenerAttributeTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new EventListenerShouldHaveAsEventListenerAttribute();
    }

    public function testExistsNeedAttribute(): void
    {
        $this->analyse([__DIR__ . '/../Fixture/EventListener/TestClassEventListener.php'], []);
    }

    public function testExistsAttribute(): void
    {
        $this->analyse([__DIR__ . '/../Fixture/EventListener/TestNotCorrectClassEventListener.php'], [
            [sprintf(RuleMessages::EVENT_LISTENER_MISSING_ATTRIBUTE, 'AsEventListener'), 7],
        ]);
    }
}
