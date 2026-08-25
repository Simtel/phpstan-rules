<?php

declare(strict_types=1);

namespace Simtel\PHPStanRules\Rule;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<Class_>
 */
final class EventListenerShouldHaveAsEventListenerAttribute implements Rule
{
    public function getNodeType(): string
    {
        return Class_::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if ($node->name === null) {
            return [];
        }

        if (! str_ends_with($node->name->name, 'EventListener')) {
            return [];
        }

        foreach ($node->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attribute) {
                if (str_ends_with($attribute->name->toString(), 'AsEventListener')) {
                    return [];
                }
            }
        }

        return [
            RuleErrorBuilder::message('Event listener class should be include attribute #[AsEventListener]')
                ->identifier('eventListener.missingAttribute')
                ->build(),
        ];
    }
}
