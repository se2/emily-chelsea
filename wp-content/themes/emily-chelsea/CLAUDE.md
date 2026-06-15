# emily-chelsea Theme — CLAUDE.md

Custom WordPress/WooCommerce theme for a jewelry e-commerce store (Emily Chelsea).
Stack: PHP 8+, WordPress, WooCommerce, ACF, FacetWP, Gravity Forms, jQuery, Laravel Mix (SCSS + JS build).

---

## Directory structure

```
emily-chelsea/
├── functions.php          # Entry point — recursively requires every .php in /inc
├── inc/
│   ├── classes/           # OOP classes (namespaced)
│   │   └── build-ring.class.php   # TTG\Build_Ring — Model / Controller / Ajax
│   ├── cpt/               # Custom post type registrations (footer, header, product, product-cta)
│   ├── hooks/             # One file per WordPress hook
│   │   ├── woo/           # WooCommerce-specific filters & actions
│   │   └── facetwp/       # FacetWP filters
│   ├── util/              # Shared utilities
│   │   ├── ttg-template.php   # TTG_Template — template rendering helper
│   │   ├── config.php
│   │   ├── product.php
│   │   └── util.php
│   ├── enqueue-styles-scripts.php
│   ├── setup.php
│   ├── woo.php
│   ├── gform.php
│   ├── acf-options-page.php
│   ├── custom-facets.php
│   └── register-widget-areas.php
├── template-parts/        # All partials (rendered via TTG_Template)
├── woocommerce/           # WooCommerce template overrides
├── gutenberg/             # Gutenberg block registrations
├── extension/             # Vendored extensions (acf-nav-menu)
├── acf-json/              # ACF field group JSON sync files — commit these
├── src/                   # Frontend build system (Laravel Mix)
│   ├── assets/scss/       # SCSS source: base.scss, components/*.scss, pages/*.scss
│   ├── assets/js/         # JS source: components/*.js, pages/*.js
│   ├── libs/              # Third-party JS (slick, isotope, sticky-kit, touch-punch)
│   ├── dist/              # Compiled output (do not edit manually)
│   └── webpack.mix.js     # Laravel Mix config
└── tools/                 # One-off admin utility scripts (not auto-loaded)
```

---

## How PHP files are loaded

`functions.php` calls `deep_scan('inc/')` and `require()`s **every `.php` file** found recursively.
**To add new functionality, create a new file inside `/inc` — it will be included automatically.**
Do not add business logic directly to `functions.php`.

---

## Template rendering

Always use `TTG_Template` — never call `get_template_part()` directly from business logic.

```php
// Render and return HTML string
$html = TTG_Template::render('template-name', ['key' => $value]);

// Alias (identical behaviour)
$html = TTG_Template::get_template_part('template-name', ['data' => $value]);

// Icons
$icon = TTG_Template::get_icon('icon-name');
```

Templates live in `/template-parts/`. The `$data` array is passed as the third argument to
`get_template_part()` and is available as `$args` inside the template.

---

## AJAX handlers

- Register with both `wp_ajax_{action}` and `wp_ajax_nopriv_{action}`.
- Always terminate with `wp_die()` after `echo wp_json_encode(...)`.
- Return shape: `{ isSuccess: bool, data?: mixed, message?: string }`.
- See `inc/hooks/wp_ajax.php` for global handlers and `inc/classes/build-ring.class.php` for the build-ring handler set.

---

## Build-ring feature (`TTG\Build_Ring`)

Three-step wizard (Setting → Stone → Confirm, or Stone → Setting → Confirm).

| Class        | Responsibility |
|---|---|
| `Model`      | Raw `$_SESSION` read/write under `BUILD_RING_SESSION_KEY` |
| `Controller` | Business logic — tray, collections, step navigation, WC cart sync |
| `Ajax`       | WordPress AJAX handlers (one method per action) |

Key concepts:
- **Tray** — one ring + one stone currently being assembled (in-progress).
- **Collections** — `uuid → {ring, stone, variation_id, attrs, ring_cart_item_line, stone_cart_item_line}` — saved complete designs.
- Tray items are also added to the WC cart immediately so pricing is always live.
- When editing an existing collection (`change_item`), the collection is restored to the tray; the original cart lines are kept until the user saves or cancels.

---

## Frontend assets (Laravel Mix)

Working directory for the build: `src/`

```bash
cd src
npm install          # first time
npm run dev          # watch / development
npm run prod         # minified production build
```

Output goes to `src/dist/`. Files are enqueued conditionally per page type in
`inc/enqueue-styles-scripts.php`.

SCSS structure:
- `assets/scss/base.scss` → `dist/css/base.min.css` (global)
- `assets/scss/components/*.scss` → `dist/css/components/*.css`
- `assets/scss/pages/*.scss` → `dist/css/pages/*.css`

JS structure:
- `assets/js/components/*.js` → `dist/js/components/*.js`
- `assets/js/pages/*.js` → `dist/js/pages/*.js`

The `ajaxUrl` is available globally as `jsData.ajaxUrl` (localized on `ttg-common`).

---

## Key WordPress hooks (non-obvious)

- `body_class` — adds `page-build-ring-wrapper` when `Controller::is_building_ring()` is true.
- `wp_head` — injects `var currentStep` when inside the build-ring flow.
- `wp_enqueue_scripts` priority `9999` — dequeues all WooCommerce default styles and enqueues custom ones.
- All WC template hooks are in `inc/hooks/woo/` — one filter/action per file.
- FacetWP hooks are in `inc/hooks/facetwp/`.

---

## Plugins relied upon

| Plugin | Used for |
|---|---|
| WooCommerce | Products, cart, checkout, orders |
| ACF (Advanced Custom Fields) | All custom fields; field groups synced via `acf-json/` |
| FacetWP | Filterable product/post archives |
| Gravity Forms | Inquiry / contact forms |

---

## ACF JSON sync

Field group definitions are saved to `acf-json/` automatically by ACF.
**Always commit `acf-json/*.json`** — they are the source of truth for field structure.

---

## Conventions

- PHP namespace: `TTG\Build_Ring` (only the build-ring classes are namespaced; everything else is global).
- Hook files: one hook per file, file name matches the hook name (e.g., `woocommerce_sidebar.php`).
- No logic in `functions.php` — it is a loader only.
- `tools/` scripts are one-off admin utilities; they are **not** auto-loaded and should not be deployed to production.
