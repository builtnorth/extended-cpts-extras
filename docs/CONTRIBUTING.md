# Contributing to Extended CPTs Extras

Thanks for your interest in improving this package.

## Reporting Issues

Open an issue describing what you expected, what happened, and how to reproduce
it. Include the package version and your PHP and WordPress versions.

**Security vulnerabilities do not belong in public issues** — see
[SECURITY.md](SECURITY.md).

## Pull Requests

1. Fork the repository and branch from `dev`.
2. Make your change, with tests covering it.
3. Run the suite and the linter before pushing:
   ```bash
   composer test
   composer lint
   ```
4. Open a pull request against `dev` explaining what changed and why.

## Coding Standards

This package follows the Built North coding standards (WordPress standards with
modern PHP on top), enforced by `composer lint`. `composer lint:fix` resolves
most violations automatically.

Commit subjects follow [Conventional Commits](https://www.conventionalcommits.org/)
— `fix:`, `feat:`, `refactor:`, `docs:` and so on. Release tooling reads these
to determine the next version, so the prefix matters.
