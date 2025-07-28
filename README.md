# Laravel Commit Lint

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mubbi/laravel-commit-lint.svg?style=flat-square)](https://packagist.org/packages/mubbi/laravel-commit-lint)
[![Build Status](https://img.shields.io/github/actions/workflow/status/mubbi/laravel-commit-lint/ci.yml?branch=main&style=flat-square)](https://github.com/mubbi/laravel-commit-lint/actions/workflows/ci.yml)
[![Tests](https://img.shields.io/github/actions/workflow/status/mubbi/laravel-commit-lint/ci.yml?label=tests&branch=main&style=flat-square)](https://github.com/mubbi/laravel-commit-lint/actions/workflows/ci.yml)


## Requirements

- Laravel >= 12.0
- PHP >= 8.2

## Overview

Laravel Commit Lint helps teams enforce [Conventional Commits](https://www.conventionalcommits.org/) in Laravel projects by automatically validating commit messages using a Git hook. This ensures consistent commit history and enables better automation and tooling.

## Features

- Automatic commit message validation using a `commit-msg` hook
- Customizable hook installation path via Artisan command
- Clear error messages and guidance for invalid commit messages
- Skips validation for merge, WIP, and revert commits
- Extensible via Laravel’s service provider and command structure

## Installation

```bash
composer require mubbi/laravel-commit-lint --dev
php artisan commitlint:install
```


## Usage

After installation, every commit will be checked for Conventional Commit compliance.


If your message does not match the required format, the commit will be rejected with guidance.

### Example of valid commit messages
- feat: add user authentication
- fix: resolve issue with email validation
- docs: update API documentation
- refactor: improve query performance
- chore: update dependencies
- style: format code according to PSR-12
- test: add unit tests for login

### Example of invalid commit messages
- updated stuff
- bug fix
- changes
- fixed it
- wip: working on something (WIP is skipped, but not recommended for final commits)

## How it works
After installation, the package places a `commit-msg` hook in your `.git/hooks` directory (or a custom path if specified). This hook runs on every commit and checks your commit message against the Conventional Commits specification using a regex. If the message is invalid, the commit is rejected and guidance is shown.

The validation script automatically skips validation for merge, WIP, and revert commits.

## Configuration
You can install the hook to a custom path:

```bash
php artisan commitlint:install /custom/path/to/commit-msg
```

You may also specify a custom stub file for the hook script:

```bash
php artisan commitlint:install --stub=/path/to/custom-stub
```

## Troubleshooting
### Common Issues
- **Hook not working:** Ensure your repository has a `.git/hooks` directory and that the `commit-msg` file is executable (`chmod +x .git/hooks/commit-msg`).
- **Artisan command not found:** Make sure the package is installed as a dev dependency and your Laravel app's autoload files are up to date (`composer dump-autoload`).
- **Commit rejected unexpectedly:** Check your commit message format and ensure it matches the Conventional Commits spec. See valid examples above.
- **Custom hook path not working:** Verify the path exists and is writable.

If you encounter other issues, please [open an issue on GitHub](https://github.com/mubbi/laravel-commit-lint/issues) with details.

## Uninstallation

To remove the commit lint hook, simply delete the `commit-msg` file from your `.git/hooks` directory:

```bash
rm .git/hooks/commit-msg
```

To remove the package:

```bash
composer remove mubbi/laravel-commit-lint
```

## FAQ

**Q: Does this work with all git clients?**
A: Yes, as long as your client supports git hooks.

**Q: Can I customize the commit message rules?**
A: You can modify the stub or extend the validation logic in the package.

**Q: Will this block merge commits?**
A: No, merge, WIP, and revert commits are skipped.


## Contributing

Contributions are welcome! Please open issues or submit pull requests via GitHub. Follow Conventional Commits for your messages.

For questions and discussions, visit [GitHub Discussions](https://github.com/mubbi/laravel-commit-lint/discussions).

For more details, see the [Contributing Guide](.github/CONTRIBUTING.md).


## License

This project is licensed under the [MIT License](LICENSE).


## Security

Please refer to our [Security Policy](.github/SECURITY.md) for reporting vulnerabilities and security concerns.
