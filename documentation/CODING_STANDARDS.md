# Coding Standards & Style Guide

## 1. PHP Standards
We follow **PSR-12** coding standards.

### Naming Conventions
- **Classes:** `PascalCase` (e.g., `VehicleController`)
- **Methods:** `camelCase` (e.g., `storeRating`)
- **Variables:** `camelCase` (e.g., `$userProfile`)
- **Constants:** `UPPER_CASE` (e.g., `MAX_ATTEMPTS`)
- **Database Tables:** `snake_case` plural (e.g., `user_activities`)

### Code Structure
- Use **Strict Types**: `declare(strict_types=1);` at the top of PHP files.
- **Return Types**: Always declare return types for methods.
  ```php
  public function index(): View
  {
      return view('home');
  }
  ```
- **Dependency Injection**: Inject dependencies via constructor or method arguments.

## 2. Laravel Best Practices
- **Controllers**: Keep them thin. Move complex logic to Services or Actions.
- **Models**: Use `$fillable` or `$guarded` to prevent mass assignment vulnerabilities.
- **Views**: Use Blade Components (`x-component`) for reusable UI elements.
- **Routes**: Name all routes using dot notation (e.g., `vehicle.show`).

## 3. Frontend Standards (Tailwind CSS)
- **Utility First**: Use utility classes directly in HTML.
- **Configuration**: Define custom colors and fonts in `tailwind.config.js`, do not hardcode arbitrary values (e.g., avoid `text-[#123456]`, use `text-slate-900`).
- **Responsive Design**: Mobile-first approach. Use `md:`, `lg:` prefixes for larger screens.

### Design System (Cyberpunk Theme)
- **Backgrounds**: `bg-slate-950` (Main), `bg-slate-900` (Cards).
- **Text**: `text-slate-300` (Body), `text-slate-100` (Headings).
- **Accents**: 
  - Primary: `cyan-500` / `cyan-400`
  - Success: `emerald-500`
  - Warning: `amber-500`
  - Danger: `red-500`
- **Fonts**: 
  - Body: `Inter`
  - Data/Tech: `JetBrains Mono`

## 4. Git Workflow
- **Branching**: Use feature branches (`feature/add-login`, `fix/nav-bug`).
- **Commits**: Use conventional commits.
  - `feat: add KYC upload form`
  - `fix: resolve mobile padding issue`
  - `docs: update API reference`
