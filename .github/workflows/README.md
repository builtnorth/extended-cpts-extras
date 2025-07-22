# GitHub Release Workflow

This directory contains a reusable GitHub Actions workflow for creating releases with proper versioning, changelog generation, and composer.json updates.

## Features

- 🏷️ **Semantic Versioning**: Supports standard version tags (v1.0.0)
- 📝 **Automatic Changelog**: Generates categorized changelog from commit messages
- 🔄 **Version Updates**: Automatically updates composer.json and package.json versions
- 🎯 **Flexible Triggers**: Can be triggered by tags or manually via workflow dispatch
- 🔧 **Configurable**: Uses repository variables for package-specific settings

## Usage in Other Packages

### 1. Copy the Workflow

Copy the `release.yml` file to your package's `.github/workflows/` directory:

```bash
mkdir -p .github/workflows
cp /path/to/polaris/.github/workflows/release.yml .github/workflows/
```

### 2. Configure Repository Variables (Optional)

Set these repository variables in GitHub Settings → Secrets and variables → Actions → Variables:

- `PACKAGE_NAME`: Internal package name (default: repository name)
- `PACKAGE_DISPLAY_NAME`: Human-readable name for releases (default: "Polaris Framework")
- `COMPOSER_NAMESPACE`: Composer package name (default: "builtnorth/polaris")
- `PACKAGE_DEPENDENCIES`: JSON object mapping package names to version constraints
- `REPOSITORY_MAPPINGS`: JSON object mapping package names to GitHub repository URLs

#### Example Configuration for Dependencies:

For `PACKAGE_DEPENDENCIES` variable:
```json
{
  "builtnorth/wp-utility": "^1.2",
  "builtnorth/wp-baseline": "^2.0",
  "builtnorth/wp-environment-indicator": "^1.5",
  "builtnorth/extended-cpts-extras": "^3.0"
}
```

For `REPOSITORY_MAPPINGS` variable:
```json
{
  "builtnorth/wp-utility": "https://github.com/builtnorth/wp-utility",
  "builtnorth/wp-baseline": "https://github.com/builtnorth/wp-baseline",
  "builtnorth/wp-environment-indicator": "https://github.com/builtnorth/wp-environment-indicator",
  "builtnorth/extended-cpts-extras": "https://github.com/builtnorth/extended-cpts-extras"
}
```

### 3. Trigger a Release

#### Option A: Manual Release (Recommended)

1. Go to Actions → Release workflow
2. Click "Run workflow"
3. Enter version number (e.g., "1.0.0" or "v1.0.0")
4. Check "pre-release" if applicable
5. Click "Run workflow"

#### Option B: Tag-based Release

```bash
git tag -a v1.0.0 -m "Release v1.0.0"
git push origin v1.0.0
```

## Commit Message Convention

For better changelog generation, use conventional commit messages:

- `feat:` or `feature:` - New features
- `fix:` or `bugfix:` - Bug fixes
- `docs:` - Documentation changes
- `style:` - Code style changes
- `refactor:` - Code refactoring
- `test:` - Test changes
- `chore:`, `build:`, `ci:` - Maintenance tasks

Example:
```
feat: add support for custom post types
fix: resolve autoloader conflict in production
docs: update installation instructions
```

## What the Workflow Does

1. **Version Detection**: Determines version from tag or manual input
2. **Version Updates**: Updates version in composer.json (and package.json if exists)
3. **Commit Changes**: Commits version updates (manual trigger only)
4. **Create Tag**: Creates and pushes tag (manual trigger only)
5. **Generate Changelog**: Creates categorized changelog from commits
6. **Create Release**: Publishes GitHub release with changelog

## Example Repository Setup

For a package like `wp-utility`:

1. Set repository variables:
   - `PACKAGE_NAME`: `wp-utility`
   - `PACKAGE_DISPLAY_NAME`: `WP Utility Library`
   - `COMPOSER_NAMESPACE`: `builtnorth/wp-utility`

2. Ensure composer.json exists with proper structure:
   ```json
   {
       "name": "builtnorth/wp-utility",
       "version": "1.0.0",
       ...
   }
   ```

3. Run the workflow to create releases

## Production Deployment

After creating releases, update your plugin/theme composer.json files:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/builtnorth/polaris"
        }
    ],
    "require": {
        "builtnorth/polaris": "^1.0"
    }
}
```

This replaces the development path repositories with proper versioned dependencies.