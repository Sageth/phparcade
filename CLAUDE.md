# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
# Install dependencies
composer install

# Run all tests
phpunit

# Run a single test file
phpunit tests/UsersTest.php

# Static analysis
phpstan analyse

# Install production dependencies only
composer install --no-dev
```

## Architecture

PHPArcade is a PHP arcade game portal — a MySQL-backed web app that lets users run an online game arcade with user accounts, high scores, categories, ads, and an admin panel.

**Entry points:**
- `index.php` — Frontend; Phroute dispatches URL patterns to handlers in `includes/first.php`
- `Zdmin/index.php` — Admin panel; requires an active admin session

**Request flow:**
1. `index.php` loads `cfg.php` (constants, paths, CDN URLs)
2. Phroute matches the route and includes `includes/first.php`
3. `first.php` initializes the session, locale, and dispatches to action handlers (login, register, profile, etc.)
4. A Bootstrap theme template in `plugins/site/themes/{theme}/index.php` renders the response

**Core classes** (`includes/classes/`, PSR-4 namespace `PHPArcade\`):
- `mySQL.php` — PDO singleton; all DB access goes through this
- `Core.php` — Static utilities: URL helpers, config loading (`getDBConfig()` reads `config` table, `getINIConfig()` reads `phpArcade.ini`), event system
- `Games.php`, `Users.php`, `Scores.php`, `Pages.php`, `Ads.php`, `Search.php` — Domain logic
- `Languages.php` — i18n via `.po` files in `includes/locale/`

**Plugin system:** Each plugin in `plugins/*/` is self-contained. Admin plugins expose an `admin.php` that `Zdmin/index.php` auto-discovers and loads. The `plugins/site/themes/` directory holds the Bootstrap 3 and Bootstrap 4 frontend themes.

**Database:** MySQL 5.7+ / MariaDB 10.2+. Schema and stored procedures are in `installation/create_database.sql`. Site config is stored in the `config` table (key-value pairs), not only in flat files.

**Configuration:** `phpArcade.ini` (at server root, outside webroot) holds DB credentials. `cfg.php` defines constants for paths and CDN resources.
