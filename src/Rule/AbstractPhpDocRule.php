<?php

declare(strict_types=1);

namespace Simtel\PHPStanRules\Rule;

use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;

abstract class AbstractPhpDocRule
{
    public function __construct(
        protected readonly PhpDocParser $phpDocParser,
        protected readonly Lexer $phpDocLexer,
    ) {
    }

    protected function parsePhpDoc(string $doc): PhpDocNode
    {
        return $this->phpDocParser->parse(new TokenIterator($this->phpDocLexer->tokenize($doc)));
    }
}
