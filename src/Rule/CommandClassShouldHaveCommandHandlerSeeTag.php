<?php

declare(strict_types=1);

namespace Simtel\PHPStanRules\Rule;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\PhpDocParser\Ast\PhpDoc\GenericTagValueNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<Class_>
 */
final class CommandClassShouldHaveCommandHandlerSeeTag extends AbstractPhpDocRule implements Rule
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

        if (! str_ends_with($node->name->name, 'Command')) {
            return [];
        }

        foreach ($node->getMethods() as $method) {
            if ($method->name->name === '__invoke') {
                return [];
            }
        }

        $doc = $node->getDocComment()?->getText() ?? '';
        if ($doc === '') {
            return [
                RuleErrorBuilder::message('Command class should be include phpDoc with @see attribute')
                    ->identifier('commandClass.missingPhpDoc')
                    ->build(),
            ];
        }

        $hasSeeTag = false;
        foreach ($this->parsePhpDoc($doc)->getTags() as $tag) {
            if ($tag->name !== '@see') {
                continue;
            }
            if (! $tag->value instanceof GenericTagValueNode) {
                continue;
            }
            $hasSeeTag = true;
            if (! str_ends_with($tag->value->value, 'CommandHandler')) {
                return [
                    RuleErrorBuilder::message(
                        sprintf(
                            'PhpDoc command class should be include @see attribute with CommandHandler class name, but include %s',
                            $tag->value->value
                        )
                    )
                        ->identifier('commandClass.invalidSeeValue')
                        ->build(),
                ];
            }
        }

        if (! $hasSeeTag) {
            return [
                RuleErrorBuilder::message(
                    'PhpDoc command class should be include @see attribute with CommandHandler class name'
                )
                    ->identifier('commandClass.missingSee')
                    ->build(),
            ];
        }

        return [];
    }
}
