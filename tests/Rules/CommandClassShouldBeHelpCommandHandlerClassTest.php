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
use Simtel\PHPStanRules\Rule\CommandClassShouldBeHelpCommandHandlerClass;

class CommandClassShouldBeHelpCommandHandlerClassTest extends RuleTestCase
{
    /**
     * @inheritDoc
     */
    protected function getRule(): Rule
    {
        $config = new ParserConfig(usedAttributes: []);
        $constExprParser = new ConstExprParser($config);
        return new CommandClassShouldBeHelpCommandHandlerClass(
            new PhpDocParser($config, new TypeParser($config, $constExprParser), $constExprParser),
            new Lexer($config)
        );
    }

    public function testCorrectSeeAttribute(): void
    {
        $this->analyse([__DIR__ . '/../data/command_handler_data1.php'], [
            [
                'PhpDoc command class should be include @see attribute with CommandHandler class name, but include TestClassCommand',
                10
            ]
        ]);
    }

    public function testExistsSeeAttribute(): void
    {
        $this->analyse([__DIR__ . '/../data/command_handler_data2.php'], [
            ['PhpDoc command class should be include @see attribute with CommandHandler class name', 10]
        ]);
    }

    public function testExistsPhpDoc(): void
    {
        $this->analyse([__DIR__ . '/../data/command_handler_data3.php'], [
            ['Command class should be include phpDoc with @see attribute', 7]
        ]);
    }

    public function testIfExistInvokeMethod(): void
    {
        $this->analyse([__DIR__ . '/../data/command_handler_data4.php'], []);
    }
}
