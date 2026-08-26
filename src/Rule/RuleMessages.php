<?php

declare(strict_types=1);

namespace Simtel\PHPStanRules\Rule;

final class RuleMessages
{
    public const COMMAND_MISSING_PHP_DOC = 'Command class should be include phpDoc with @see attribute';

    public const COMMAND_MISSING_SEE = 'PhpDoc command class should be include @see attribute with %s class name';

    public const COMMAND_INVALID_SEE_VALUE = 'PhpDoc command class should be include @see attribute with %s class name, but include %s';

    public const EVENT_LISTENER_MISSING_ATTRIBUTE = 'Event listener class should be include attribute #[%s]';

    public const RETURN_REDUNDANT_PHP_DOC = 'PhpDoc attribute @return for method %s can be remove';

    public const IDENTIFIER_COMMAND_MISSING_PHP_DOC = 'commandClass.missingPhpDoc';

    public const IDENTIFIER_COMMAND_MISSING_SEE = 'commandClass.missingSee';

    public const IDENTIFIER_COMMAND_INVALID_SEE_VALUE = 'commandClass.invalidSeeValue';

    public const IDENTIFIER_EVENT_LISTENER_MISSING_ATTRIBUTE = 'eventListener.missingAttribute';

    public const IDENTIFIER_RETURN_REDUNDANT_PHP_DOC = 'returnType.redundantPhpDoc';
}
