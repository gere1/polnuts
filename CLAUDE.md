# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
composer dev                 # runs server + queue listener + pail (logs) + vite concurrently — the normal way to run this app locally
php artisan serve             # app only, no asset watching
npm run dev                   # vite dev server (asset hot reload)
npm run build                 # production asset build (resources/js/app.js, resources/css/app.css -> public/build)

php artisan test               # full test suite (phpunit.xml: Unit + Feature suites, sqlite :memory:)
php artisan test --filter=X    # single test by method/class name
vendor/bin/phpunit tests/Feature/Auth/AuthenticationTest.php   # single file

vendor/bin/pint                # Laravel Pint code style fixer (PHP)
php artisan migrate            # run pending migrations against the configured DB (see below — not sqlite locally)
```

There is no git repository initialized in this checkout (working tree only). Don't assume `git log`/`git blame` are available for history.

## Work log

After every significant change (new feature, behavior change, non-trivial fix — not typo fixes or pure formatting), append an entry to `docs/WORKLOG.md` with: the date, a short description of what was done, and which files changed. Newest entry on top. Do this as part of the same turn as the change, not as a separate follow-up task.

## Local environment gotcha: MySQL instant ADD COLUMN

The local DB is MySQL (8.4.x via Laragon), **not** the sqlite used by tests. When a migration adds **multiple** columns with different `->default(...)` values to a table that already has rows, put each column in its **own** `Schema::table()` call rather than batching them in one closure — MySQL's instant ADD COLUMN has a bug class that can silently write the wrong default into existing rows when several columns are batched in one ALTER. After running such a migration, spot-check the existing row(s), don't just trust `DESCRIBE`.

## Architecture

This is a Laravel 13 app with two distinct halves sharing the same models: a **public multilingual site** rendered from an admin-managed page builder, and the **admin panel** (`/admin/*`, behind `auth`) that edits it.

### Page builder: Page → Row → RowItem

- `Page` has many `Row`s (own, ordered by `position`), and can also share other pages' rows via a `page_row` pivot (`sharedRows()` / `sharedPages()`), each with its own pivot `position`. `Page::displayRows()` merges own + shared rows into one page-specific ordered list — this is what controllers pass to the view, never `$page->rows` directly.
- Each `Row` has a `type` (one of `Row::TYPES`: slider, news, text, products, map, form) and a JSON `settings` column holding everything type-specific (columns, height, layout, background, padding, etc.) plus generic layout knobs described below. There is no per-type table — `resources/views/site/rows/{type}.blade.php` renders each, included from `resources/views/site/page.blade.php`.
- `RowItem` belongs to a `Row` and holds per-item content (title/subtitle/body/image/link) for row types that repeat content (slider slides, multi-column text blocks, etc.).
- `Row::styleAttr()` builds the inline `style=""` for a single, normally-rendered row (background image/color/gradient, text color, padding). It intentionally does **not** apply a background by default — see the ambient background section below — only when `settings.override_background` is set, or a `background_image` is uploaded.

### Site-wide ambient background + per-row overrides

`resources/views/layouts/site.blade.php` renders a fixed, full-viewport `#scroll-gradient-bg` layer (z-index -1) whose gradient colors come from `Setting` (`gradient_top_start/end`, `gradient_bottom_start/end`) and is animated client-side in `resources/js/app.js` (interpolates by scroll progress down the whole document). `Setting::background_mode` (`gradient`|`solid`) toggles this off in favor of the older flat `content_bg_color` + `.bg-content` on `<body>`. Individual rows stay transparent over this by default; a row opts back into its own background via `settings.override_background` (see `Row::styleAttr()`).

### Row grouping (side-by-side sections)

A row can set `settings.group_size` (2-4) to visually merge with the next `group_size - 1` rows into one responsive grid (`resources/views/site/page.blade.php` walks `$rows` with an index, consuming `group_size` rows per group instead of looping one-by-one — grid column classes must stay literal strings, e.g. `md:grid-cols-3`, for Tailwind's JIT scanner to find them). Each row type's blade template accepts an optional `$bare` flag that strips its own outer `<section>`/background/padding when the group has `settings.group_shared_background` enabled, so the shared background (own dedicated `group_background_*`/`group_padding_*` settings, intentionally separate from the row's normal background settings) is drawn once by the group wrapper instead of once per member.

### Multilingual routing — always use `localizedRoute()`

`Setting::default_locale` (admin-configurable, one of `ka`/`en`/`de`) gets **unprefixed** canonical routes (`home`, `products.index`, ...). The other locales are mirrored under `/{locale}` with `loc.`-prefixed route names, registered via thin closures in `routes/web.php` that forward to the same controller methods (kept as closures rather than the same array-callable so `{locale}` doesn't shift into the model-binding parameter's position). Never call `route()` directly for a site-facing link — use the `localizedRoute($name, $params, $locale = null)` helper (`app/helpers.php`), which picks the right (prefixed or not) route name based on the current/given locale. `SetLocale` middleware (aliased `setlocale`) resolves the active locale per-request and 404s a locale that isn't enabled in `Setting`.

### Translatable content

`Page`, `Row`, `RowItem`, `Article`, `Product`, `MenuItem` use `spatie/laravel-translatable` (`$translatable` arrays; JSON columns under the hood). `AppServiceProvider` sets the translatable fallback locale to `Setting::default_locale` (not hardcoded), with `fallbackAny: true`. Use the `translatableRules()` helper (`app/helpers.php`) to build `{field}.{locale}` validation rules per language, marking only the current default locale as `required`.

### Settings-driven theming

`Setting::current()` (`firstOrCreate`, singleton row) drives almost everything global: site name/logo/favicon, brand/text/header/footer colors exposed as CSS custom properties in `layouts/site.blade.php` (`--brand-primary`, `--header-bg`, etc. — consumed by Tailwind utility classes like `bg-header`/`text-brand` in `tailwind.config.js`), the ambient background settings above, active `font` (`Setting::FONTS`), and which locales are enabled (`Setting::LOCALES`, `isLocaleEnabled()`).

### Rich text

`resources/views/components/rich-editor.blade.php` wraps Quill (loaded from CDN, not npm) for `body` fields on products/text rows. It registers a custom style-based `size` attributor with an explicit px whitelist (Quill's default only offers small/normal/large/huge) and adds `color`/`background` toolbar buttons.

### Assets

Tailwind (v3, via `tailwind.config.js` + the classic `postcss.config.js` plugin, not the v4 Vite plugin) + Alpine.js, built through `laravel-vite-plugin`. `resources/js/app.js` also owns the scroll-gradient animation described above. Swiper (sliders/carousels) and Sortable.js (admin drag-reorder) are loaded from CDN in the relevant Blade views, not bundled.
