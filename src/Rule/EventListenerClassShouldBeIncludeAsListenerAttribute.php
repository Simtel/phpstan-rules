<?php

declare(strict_types=1);

namespace Simtel\PHPStanRules\Rule;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<Class_>
 */
final class EventListenerClassShouldBeIncludeAsListenerAttribute implements Rule
{

    public function __construct(private readonly ReflectionProvider $reflectionProvider)
    {
    }

    public function getNodeType(): string
    {
        return Class_::class;
    }

    /**
     * @param Class_ $node
     * @param Scope $scope
     *
     * @return string[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $className = '';
        if ($node->name !== null) {
            $className = $node->name->name;
        }

        if (!str_ends_with($className, 'EventListener')) {
            return [];
        }

        $fullyQualifiedClassName = $node->namespacedName?->toString();
        if ($fullyQualifiedClassName === null) {
            return [];
        }
        $attributes = $this->reflectionProvider
            ->getClass($fullyQualifiedClassName)
            ->getNativeReflection()
            ->getAttributes();

        $find = false;
        foreach ($attributes as $attribute) {
            if (str_ends_with($attribute->getName(), 'AsEventListener')) {
                $find = true;
            }
        }

        if ($find === false) {
            return [
                RuleErrorBuilder::message('Event listener class should be include attribute #[AsEventListener]')->build(
                ),
            ];
        }

        return [];
    }
}
