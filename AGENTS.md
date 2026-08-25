# AGENTS.md

Custom PHPStan rules package (`simtel/phpstan-rules`). Adds static-analysis rules as a PHPStan extension distributed via `rules.neon`. Namespace: `Simtel\PHPStanRules\` → `src/`, `Simtel\PHPStanRules\Tests\` → `tests/`. PHP >=8.3, PHPStan ^2.0, PHPUnit ^12.

## Commands

- Tests: `vendor/bin/phpunit` — all tests pass (8). Single test: `vendor/bin/phpunit tests/Rules/CommandClassShouldHaveCommandHandlerSeeTagTest.php`
- Code style: `vendor/bin/ecs check` (or `ecs check --fix`; there is no `ecs fix`). Uses PSR-12 + symplify + spaces + `NoUnusedImports`. Run after changes.
- Static analysis: `vendor/bin/phpstan analyse` — self-analysis of `src/` at level max, must stay at 0 errors.
- CI (`.github/workflows/php.yml`) only runs `composer validate --strict`, `composer install`, and `vendor/bin/phpunit`.

## Architecture

- `AbstractPhpDocRule` (in `src/Rule/`) is the shared base for rules that parse PHPDoc: it injects `PhpDocParser` + `Lexer` and exposes `parsePhpDoc(string $doc): PhpDocNode`. Subclasses implement `Rule` themselves.
- `EventListenerShouldHaveAsEventListenerAttribute` inspects `$node->attrGroups` directly (no reflection, no base class).
- `ShouldNotPhpDocReturnWhenTypeHintExists` works on `ClassMethod` nodes and reads the native type from `$node->returnType` (no reflection needed). Only `Identifier`/`Name` native types and `IdentifierTypeNode` PHPDoc types are compared — union/nullable/generic types are skipped, not errors.
- Errors are reported via `RuleErrorBuilder::message(...)->identifier('rule.group')` (never throw). PHPStan 2.x requires identifiers.

## Adding a rule

1. Create `src/Rule/<Name>.php` implementing `PHPStan\Rules\Rule` with `@implements Rule<NodeType>` and `getNodeType()` + `processNode()`. Extend `AbstractPhpDocRule` if it parses PHPDoc.
2. Register it in `rules.neon` (this is what consumers include).
3. Add a test extending `PHPStan\Testing\RuleTestCase` in `tests/Rules/`. In `getRule()`, fetch parser/lexer from the container: `self::getContainer()->getByType(PhpDocParser::class)` / `getByType(Lexer::class)` — do not build them manually.
4. Add fixture/data PHP files under `tests/Fixture/` or `tests/data/`. Command-rule data files live in `tests/data/command_handler_data*.php`.
5. Update the README feature list.

## Gotchas

- Rule classes are `final`, extend `AbstractPhpDocRule` when parsing PHPDoc, `declare(strict_types=1)`, PSR-12 style, no comments.
- `rules.neon` and the README list the 3 distributed rules — keep in sync when adding/removing.
- Tests use PHPStan's `RuleTestCase::analyse()`; expectations include exact line numbers, so changing a fixture's layout breaks tests. Append new methods to a fixture rather than inserting before existing ones.
- `phpunit.xml.dist` uses the migrated PHPUnit 12 schema (`<source>` instead of `<coverage>`); `.phpunit.cache/` is gitignored.
