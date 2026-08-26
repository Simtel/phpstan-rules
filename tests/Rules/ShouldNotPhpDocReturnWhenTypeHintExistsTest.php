<?php

declare(strict_types=1);

namespace Simtel\PHPStanRules\Tests\Rules;

use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Simtel\PHPStanRules\Rule\RuleMessages;
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
            [sprintf(RuleMessages::RETURN_REDUNDANT_PHP_DOC, 'someMethod'), 12],
            [sprintf(RuleMessages::RETURN_REDUNDANT_PHP_DOC, 'getInt'), 20],
        ]);
    }

    public function testWithComplexTypes(): void
    {
        $this->analyse([__DIR__ . '/../Fixture/Return/MethodsWithComplexTypes.php'], []);
    }
}
