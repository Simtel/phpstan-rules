<?php

declare(strict_types=1);

namespace Simtel\PHPStanRules\Tests\Rules;

use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TypeParser;
use PHPStan\PhpDocParser\ParserConfig;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Simtel\PHPStanRules\Rule\NotShouldPhpdocReturnIfExistTypeHint;

class NotShouldPhpdocReturnIfExistTypeHintTest extends RuleTestCase
{
    /**
     * @inheritDoc
     */
    public function getRule(): Rule
    {
        $config = new ParserConfig(usedAttributes: []);
        $constExprParser = new ConstExprParser($config);
        return new NotShouldPhpdocReturnIfExistTypeHint(
            $this->createReflectionProvider(),
            new PhpDocParser($config, new TypeParser($config, $constExprParser), $constExprParser),
            new Lexer($config)
        );
    }

    public function testWithError(): void
    {
        $this->analyse([__DIR__ . '/../Fixture/Return/MethodsWithTypeHintAndReturn.php'], [
            ['PhpDoc attribute @return for method someMethod can be remove', 12],
            ['PhpDoc attribute @return for method getInt can be remove', 20],
        ]);
    }
}
