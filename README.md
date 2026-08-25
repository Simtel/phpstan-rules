# PHPStan Extended Rules

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![PHP Version](https://img.shields.io/badge/php-%3E8.3-blue.svg)](https://php.net/)
[![PHPStan](https://img.shields.io/badge/PHPStan-^2.0-blue.svg)](https://phpstan.org/)

A collection of custom PHPStan rules that enforce coding standards and improve code quality in PHP projects. This extension provides additional static analysis rules focused on naming conventions, annotations, and PHPDoc consistency.

## 🎯 Features

This package includes three powerful rules that help maintain high code quality:

### 1. Command-Handler Relationship Rule
**Rule**: `CommandClassShouldHaveCommandHandlerSeeTag`

Enforces that classes ending with "Command" must have a `@see` PHPDoc tag pointing to their corresponding CommandHandler class.

**Example**:
```php
/**
 * @see CreateUserCommandHandler
 */
class CreateUserCommand
{
    // Command implementation
}
```

**Exception**: Classes with an `__invoke` method are exempt from this rule.

### 2. Event Listener Attribute Rule
**Rule**: `EventListenerShouldHaveAsEventListenerAttribute`

Ensures that classes ending with "EventListener" are properly annotated with the `#[AsEventListener]` attribute.

**Example**:
```php
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
class UserRegisteredEventListener
{
    // Event listener implementation
}
```

### 3. Redundant PHPDoc Return Type Rule
**Rule**: `ShouldNotPhpDocReturnWhenTypeHintExists`

Prevents redundant or conflicting `@return` PHPDoc annotations when native return type hints are already declared.

**Good**:
```php
public function getUser(): User
{
    return $this->user;
}
```

**Bad**:
```php
/**
 * @return User
 */
public function getUser(): User  // Redundant @return annotation
{
    return $this->user;
}
```

## 📦 Installation

### System Requirements

- **PHP**: >8.3
- **PHPStan**: ^2.0
- **PHPStan PHPDoc Parser**: ^2.2

### Install via Composer

Install the package as a development dependency:

```bash
composer require --dev simtel/phpstan-rules
```

## ⚙️ Configuration

### Option 1: Include All Rules (Recommended)

Add the bundled configuration to your `phpstan.neon` or `phpstan.dist.neon`:

```neon
includes:
    - vendor/simtel/phpstan-rules/rules.neon
```

### Option 2: Manual Rule Registration

For granular control, register specific rules:

```neon
parameters:
    rules:
        - Simtel\PHPStanRules\Rule\CommandClassShouldHaveCommandHandlerSeeTag
        - Simtel\PHPStanRules\Rule\EventListenerShouldHaveAsEventListenerAttribute
        - Simtel\PHPStanRules\Rule\ShouldNotPhpDocReturnWhenTypeHintExists
```

### Complete Configuration Example

```neon
includes:
    - vendor/simtel/phpstan-rules/rules.neon

parameters:
    level: 8
    paths:
        - src/
    excludePaths:
        - tests/
    ignoreErrors:
        # Exclude specific patterns if needed
        - '#Command class should be include phpDoc with @see attribute#'
          path: src/Deprecated/
        # Rules now report error identifiers, e.g.:
        # - identifier: commandClass.missingPhpDoc
        #   path: src/Deprecated/
```

## 🔧 Development

### Setting Up Development Environment

1. Clone the repository:
   ```bash
   git clone <repository-url> phpstan-rules
   cd phpstan-rules
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

### Development Commands

#### Running Tests
```bash
# Run all tests
vendor/bin/phpunit

# Run specific test class
vendor/bin/phpunit tests/Rules/CommandClassShouldBeHelpCommandHandlerClassTest.php
```

#### Code Style
```bash
# Check coding standards
vendor/bin/ecs check

# Fix coding standards automatically
vendor/bin/ecs fix
```

#### Static Analysis
```bash
# Run PHPStan on the project itself
vendor/bin/phpstan analyse
```

### Project Structure

```
.
├── src/Rule/                          # Rule implementations
│   ├── CommandClassShouldHaveCommandHandlerSeeTag.php
│   ├── EventListenerShouldHaveAsEventListenerAttribute.php
│   ├── ShouldNotPhpDocReturnWhenTypeHintExists.php
│   └── AbstractPhpDocRule.php         # Shared PHPDoc parsing base
├── tests/
│   ├── Fixture/                        # Test code samples
│   │   ├── EventListener/
│   │   └── Return/
│   ├── Rules/                          # Unit tests for rules
│   └── data/                           # Additional test data
├── composer.json                       # Package configuration
├── ecs.php                            # ECS configuration
└── README.md                          # This file
```

### Creating New Rules

1. **Implement the Rule Interface**:
   ```php
   <?php
   
   namespace Simtel\PHPStanRules\Rule;
   
   use PHPStan\Rules\Rule;
   
   /**
    * @implements Rule<NodeType>
    */
   final class YourNewRule implements Rule
   {
       public function getNodeType(): string
       {
           return NodeType::class;
       }
   
       public function processNode(Node $node, Scope $scope): array
       {
           // Rule implementation
       }
   }
   ```

2. **Create Test Cases**:
   ```php
   <?php
   
   namespace Simtel\PHPStanRules\Tests\Rules;
   
   use PHPStan\Rules\Rule;
   use PHPStan\Testing\RuleTestCase;
   
   class YourNewRuleTest extends RuleTestCase
   {
       protected function getRule(): Rule
       {
           return new YourNewRule();
       }
   
       public function testValidCase(): void
       {
           $this->analyse([__DIR__ . '/../Fixture/Valid.php'], []);
       }
   }
   ```

3. **Add Fixture Files**: Create test PHP files in `tests/Fixture/` to validate rule behavior.

### Testing Strategy

The project uses PHPUnit with PHPStan's `RuleTestCase` base class:

- **Fixture Files**: Real PHP code examples in `tests/Fixture/`
- **Data Files**: Additional test scenarios in `tests/data/`
- **Unit Tests**: Each rule has corresponding test class in `tests/Rules/`

### Contributing Guidelines

1. **Code Standards**: Follow PSR-4 autoloading and use ECS for code style
2. **Testing**: All rules must have comprehensive tests with both positive and negative cases
3. **Documentation**: Update README.md and add inline documentation for new rules
4. **Performance**: Ensure rules execute efficiently within PHPStan's analysis pipeline

## 🐛 Troubleshooting

### Common Issues

#### "Rule Not Found" Errors
**Cause**: Incorrect namespace or class name in `phpstan.neon`.

**Solution**: Verify the fully qualified class names and check for typos.

#### Rules Not Being Applied
**Cause**: Configuration file not included or rules not registered.

**Solution**: Ensure `vendor/simtel/phpstan-rules/rules.neon` is included in your PHPStan configuration.

#### Performance Issues
**Cause**: Rules may be analyzing too many files or performing expensive operations.

**Solution**: Use `excludePaths` to limit analysis scope or optimize rule logic.

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📞 Support

If you encounter any issues or have questions, please [open an issue](../../issues) on GitHub.
