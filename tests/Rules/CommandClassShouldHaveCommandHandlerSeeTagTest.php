<?php

declare(strict_types=1);

namespace Simtel\PHPStanRules\Tests\Rules;

use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Simtel\PHPStanRules\Rule\CommandClassShouldHaveCommandHandlerSeeTag;

class CommandClassShouldHaveCommandHandlerSeeTagTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        $container = self::getContainer();

        return new CommandClassShouldHaveCommandHandlerSeeTag(
            $container->getByType(PhpDocParser::class),
            $container->getByType(Lexer::class),
        );
    }

    public function testCorrectSeeAttribute(): void
    {
        $this->analyse([__DIR__ . '/../data/command_handler_data1.php'], [
            [
                'PhpDoc command class should be include @see attribute with CommandHandler class name, but include TestClassCommand',
                10,
            ],
        ]);
    }

    public function testExistsSeeAttribute(): void
    {
        $this->analyse([__DIR__ . '/../data/command_handler_data2.php'], [
            ['PhpDoc command class should be include @see attribute with CommandHandler class name', 10],
        ]);
    }

    public function testExistsPhpDoc(): void
    {
        $this->analyse([__DIR__ . '/../data/command_handler_data3.php'], [
            ['Command class should be include phpDoc with @see attribute', 7],
        ]);
    }

    public function testIfExistInvokeMethod(): void
    {
        $this->analyse([__DIR__ . '/../data/command_handler_data4.php'], []);
    }
}
