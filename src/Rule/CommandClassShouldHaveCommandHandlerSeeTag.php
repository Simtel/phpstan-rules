<?php

declare(strict_types=1);

namespace Simtel\PHPStanRules\Rule;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\GenericTagValueNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<InClassNode>
 */
final class CommandClassShouldHaveCommandHandlerSeeTag extends AbstractPhpDocRule implements Rule
{
    public function __construct(
        PhpDocParser $phpDocParser,
        Lexer $phpDocLexer,
        private readonly string $commandSuffix = 'Command',
        private readonly string $commandHandlerSuffix = 'CommandHandler',
    ) {
        parent::__construct($phpDocParser, $phpDocLexer);
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

        if (! str_ends_with($classReflection->getDisplayName(), $this->commandSuffix)) {
            return [];
        }

        if ($classReflection->hasNativeMethod('__invoke')) {
            return [];
        }

        $doc = $node->getOriginalNode()
            ->getDocComment()?->getText() ?? '';
        if ($doc === '') {
            return [
                RuleErrorBuilder::message(RuleMessages::COMMAND_MISSING_PHP_DOC)
                    ->identifier(RuleMessages::IDENTIFIER_COMMAND_MISSING_PHP_DOC)
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
            if (! str_ends_with($tag->value->value, $this->commandHandlerSuffix)) {
                return [
                    RuleErrorBuilder::message(
                        sprintf(
                            RuleMessages::COMMAND_INVALID_SEE_VALUE,
                            $this->commandHandlerSuffix,
                            $tag->value->value
                        )
                    )
                        ->identifier(RuleMessages::IDENTIFIER_COMMAND_INVALID_SEE_VALUE)
                        ->build(),
                ];
            }
        }

        if (! $hasSeeTag) {
            return [
                RuleErrorBuilder::message(sprintf(RuleMessages::COMMAND_MISSING_SEE, $this->commandHandlerSuffix))
                    ->identifier(RuleMessages::IDENTIFIER_COMMAND_MISSING_SEE)
                    ->build(),
            ];
        }

        return [];
    }
}
