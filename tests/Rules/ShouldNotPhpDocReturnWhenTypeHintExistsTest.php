<?php

declare(strict_types=1);

namespace Simtel\PHPStanRules\Tests\Rules;

use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Simtel\PHPStanRules\Rule\ShouldNotPhpDocReturnWhenTypeHintExists;

class ShouldNotPhpDocReturnWhenTypeHintExistsTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        $container = self::getContainer();

        return new ShouldNotPhpDocReturnWhenTypeHintExists(
            $container->getByType(PhpDocParser::class),
            $container->getByType(Lexer::class),
        );
    }

    public function testWithError(): void
    {
        $this->analyse([__DIR__ . '/../Fixture/Return/MethodsWithTypeHintAndReturn.php'], [
            ['PhpDoc attribute @return for method someMethod can be remove', 12],
            ['PhpDoc attribute @return for method getInt can be remove', 20],
        ]);
    }

    public function testWithComplexTypes(): void
    {
        $this->analyse([__DIR__ . '/../Fixture/Return/MethodsWithComplexTypes.php'], []);
    }
}
