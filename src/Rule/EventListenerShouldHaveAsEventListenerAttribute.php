<?php

declare(strict_types=1);

namespace Simtel\PHPStanRules\Rule;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<InClassNode>
 */
final class EventListenerShouldHaveAsEventListenerAttribute implements Rule
{
    public function __construct(
        private readonly string $eventListenerSuffix = 'EventListener',
        private readonly string $asEventListenerSuffix = 'AsEventListener',
    ) {
    }

    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        $classReflection = $node->getClassReflection();
        if (! $classReflection->isClass()) {
            return [];
        }

        if (! str_ends_with($classReflection->getDisplayName(), $this->eventListenerSuffix)) {
            return [];
        }

        foreach ($classReflection->getAttributes() as $attribute) {
            if (str_ends_with($attribute->getName(), $this->asEventListenerSuffix)) {
                return [];
            }
        }

        return [
            RuleErrorBuilder::message(
                sprintf(RuleMessages::EVENT_LISTENER_MISSING_ATTRIBUTE, $this->asEventListenerSuffix)
            )
                ->identifier(RuleMessages::IDENTIFIER_EVENT_LISTENER_MISSING_ATTRIBUTE)
                ->build(),
        ];
    }
}
