<?php

declare(strict_types=1);

namespace Simtel\PHPStanRules\Rule;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<ClassMethod>
 */
final class ShouldNotPhpDocReturnWhenTypeHintExists extends AbstractPhpDocRule implements Rule
{
    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (str_starts_with($node->name->name, '__')) {
            return [];
        }

        $returnType = $node->returnType;
        if ($returnType === null) {
            return [];
        }

        $nativeTypeName = match (true) {
            $returnType instanceof Identifier => $returnType->name,
            $returnType instanceof Name => $returnType->toString(),
            default => null,
        };
        if ($nativeTypeName === null) {
            return [];
        }

        $doc = $node->getDocComment()?->getText() ?? '';
        if ($doc === '') {
            return [];
        }

        foreach ($this->parsePhpDoc($doc)->getReturnTagValues() as $returnTag) {
            if ($returnTag->type instanceof IdentifierTypeNode && $returnTag->type->name === $nativeTypeName) {
                return [
                    RuleErrorBuilder::message(
                        'PhpDoc attribute @return for method ' . $node->name->name . ' can be remove'
                    )
                        ->line($node->getStartLine())
                        ->identifier('returnType.redundantPhpDoc')
                        ->build(),
                ];
            }
        }

        return [];
    }
}
