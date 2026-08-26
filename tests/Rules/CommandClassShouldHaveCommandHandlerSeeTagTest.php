<?php

declare(strict_types=1);

namespace Simtel\PHPStanRules\Tests\Rules;

use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Simtel\PHPStanRules\Rule\CommandClassShouldHaveCommandHandlerSeeTag;
use Simtel\PHPStanRules\Rule\RuleMessages;

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

    #[DataProvider('provideCommandCases')]
    public function testRule(string $file, array $expectedErrors): void
    {
        $this->analyse([$file], $expectedErrors);
    }

    /**
     * @return iterable<string, array{string, list<array{string, int}>}>
     */
    public static function provideCommandCases(): iterable
    {
        yield 'invalid see value' => [
            __DIR__ . '/../data/command_handler_data1.php',
            [[sprintf(RuleMessages::COMMAND_INVALID_SEE_VALUE, 'CommandHandler', 'TestClassCommand'), 10, ], ],
        ];

        yield 'missing see tag' => [
            __DIR__ . '/../data/command_handler_data2.php',
            [[sprintf(RuleMessages::COMMAND_MISSING_SEE, 'CommandHandler'), 10], ],
        ];

        yield 'missing phpdoc' => [
            __DIR__ . '/../data/command_handler_data3.php',
            [[RuleMessages::COMMAND_MISSING_PHP_DOC, 7], ],
        ];

        yield 'has invoke method' => [__DIR__ . '/../data/command_handler_data4.php', [], ];
    }
}
