# Contributing Guide

Thank you for considering contributing to Laravel Commit Lint!

## Prerequisites

- PHP >= 8.0
- Composer
- Git
- PHPUnit
- Bash (for hook scripts)

Ensure these are installed and available in your environment before contributing.

## How to Contribute

### Conventional Commits Example

```
feat(auth): add login functionality

fix(hook): correct regex for commit message validation
```

See [Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0/) for more details.

## Code of Conduct
Please be respectful and follow the [Contributor Covenant](https://www.contributor-covenant.org/).

## Reporting Issues
Open an issue with details and steps to reproduce.

For questions, reach out via GitHub Discussions or open an issue.


## Development

  - Requires Bash, PHP, and PHPUnit to be available in your environment.

## Testing

Run tests and generate coverage reports and clover XML file:
```bash
 vendor/bin/phpunit --colors=always --testdox
```

Coverage enforcement: The pre-push hook will block pushes if coverage is below 80%. Review the HTML report in the `coverage/` directory for details.

## Development Setup

To contribute to this project, please set up the git hooks to ensure commit message linting and test coverage enforcement:

1. **Copy hooks to your local git hooks directory:**
   ```sh
   cp .github/hooks/* .git/hooks/
   chmod +x .git/hooks/commit-msg .git/hooks/pre-push
   ```

2. **Verify hooks are executable:**
   ```sh
   ls -l .git/hooks/commit-msg .git/hooks/pre-push
   ```

3. **Contribute as usual:**
   - Make your changes.
   - Commit with a message following the Conventional Commits format.
   - Push your branch. The pre-push hook will run tests and enforce minimum 80% coverage.

If you encounter any issues with the hooks, ensure you have the required dependencies installed and that your environment allows execution of shell scripts.

## Resources

- [Laravel Package Development Documentation](https://laravel.com/docs/12.x/packages)
- [Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0/)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)

