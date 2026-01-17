# Contributing Guidelines

Thank you for considering contributing to **MATAJALAN_OS**! We welcome contributions from the community to make our roads safer and our code cleaner.

## How to Contribute

### 1. Reporting Bugs
- Check the Issues tab to see if the bug has already been reported.
- Open a new Issue using the "Bug Report" template.
- Include clear steps to reproduce, expected behavior, and screenshots if possible.

### 2. Suggesting Features
- Open a new Issue using the "Feature Request" template.
- Explain the "Why" and "How" of your idea.

### 3. Submitting Code (Pull Requests)
1. **Fork the Repository** to your own GitHub account.
2. **Clone** your fork locally.
3. **Create a Branch** for your feature or fix:
   ```bash
   git checkout -b feature/my-awesome-feature
   ```
4. **Make Changes** following our [Coding Standards](CODING_STANDARDS.md).
5. **Test Your Code** to ensure no regressions.
6. **Commit** your changes with descriptive messages.
7. **Push** to your fork.
8. **Open a Pull Request** (PR) to the `main` branch of the original repository.

## Development Workflow

- **Install Dependencies**: `composer install` && `npm install`
- **Run Migrations**: `php artisan migrate`
- **Start Dev Server**: `npm run dev` (Vite) & `php artisan serve` (Laravel)

## Code Review Process
- All PRs require review by at least one maintainer.
- Automated tests (CI) must pass before merging.
- Ensure your code is formatted correctly (`laravel/pint` is recommended).
