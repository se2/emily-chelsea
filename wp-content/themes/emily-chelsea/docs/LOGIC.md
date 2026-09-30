# emily-chelsea — Business Logic Notes

Companion to `CLAUDE.md` (structure/conventions). This file documents **what the code
actually does** — the business rules, non-obvious behavior, and known tech debt — so a
new developer doesn't have to reverse-engineer it from 50+ hook files.

See also `WEBSITE-RULES.md` — the plain-language, admin-facing version of these rules
(syncs, filters, build-a-ring, product/cart rules), including the **hourly Meta product
feed** (`tools/generate-datafeed.php`, required from `functions.php` — the one `tools/`
script that IS live in production; regenerates `/wp-content/uploads/meta-feed.csv` and
supports admin-only `?regen_meta_feed=1`).

---

## 1. Build-ring feature (`inc/classes/build-ring.class.php`)

The most complex feature in the theme: a session-driven wizard for assembling a custom
ring (Setting + Stone) that stays in sync with the WooCommerce cart live, before checkout.

### Session shape (`Model`)

Thin wrapper around `$_SESSION[BUILD_RING_SESSION_KEY]`:

```
tray:        uuid → { uuid, order, type, product_id, variation_id, attrs, cart_line_item }
collections: uuid → { ring, stone, variation_id, attrs, ring_cart_item_line, stone_cart_item_line }
uuid:        current tray's collection key
init_mode:   START_WITH_SETTING | START_WITH_STONE (locked on first set, drives step order)
mode:        current SETTING | STONE toggle
active_step: current step index
attrs, variation
```

- **Tray** = one in-progress ring+stone design.
- **Collections** = saved/finished designs (both ring and stone present).

### Cart sync

`add_item_to_tray()` removes any existing tray item of the same `type` (ring/stone) from
the WC cart, then re-adds via `WC()->cart->add_to_cart(..., ['unique_key' => uuid])`.
`unique_key` is what lets two cart lines with the identical product+variation stay
distinct (one per design) — without it WC would merge them into one line with qty 2.

### Step engine

`get_steps($initMode)` is a hardcoded lookup:
- `START_WITH_SETTING` → `[SETTING, STONE, CONFIRM]`
- `START_WITH_STONE` → `[STONE, SETTING, CONFIRM]`

`init_mode` is set once (first `set_mode()` call) and never changes afterward except via
an explicit reset/toggle — it's the source of truth for step order for the rest of the
session.

### `select_product()` — the core Ajax handler

1. Validates stock/purchasability (`product_is_valid`) before touching cart/session.
2. **New design** (`current_uuid` empty) → `add_item_to_tray()`.
3. **Editing an existing collection** (`current_uuid` present) → mutates
   `$currentCollection` directly and calls `add_to_cart` with
   `unique_key = uuid . '_ring' / '_stone' / '_stone_option'`.
4. A third param, `stone_option` (picking a stone bundled with a ring), is handled
   independently of `type`.
5. **Step-skip logic**: if the next step would be STONE but the ring selection already
   implies no separate stone is needed (or vice versa), the wizard jumps ahead to
   whichever step actually needs input — the step sequence is not strictly linear.
6. On reaching CONFIRM with a finished design: `Controller::reset()` clears the **tray
   only** (collection + its cart lines persist), so the next design starts from an empty
   tray while the finished one stays in `collections`.

### Editing a saved collection

`change_item` → `restore_collection_to_tray`:
- If a *different* tray is already in progress: auto-saves it into `collections` if
  complete, or removes its cart lines if incomplete — **before** loading the requested
  collection into the tray.
- Blocked without `?force=1` when a tray is pending (`hasPendingTray`) — the frontend is
  expected to prompt the user first.
- `cancel_editing` **does not** touch WC cart items — only clears tray/uuid session
  state — because the tray items reference the *same* cart lines as the untouched
  collection.

### Pre-checkout guards

- `clear_uncomplete_design` (Ajax) purges any collection missing a ring or stone (+ its
  cart lines) — meant to run right before checkout so partial designs never become an
  order.
- `is_valid_collections` checks every collection has a `variation_id`.

### Ajax action reference

| Action | Purpose | Response |
|---|---|---|
| `toggle_mode` | flip SETTING↔STONE mode | `{mode, isSuccess}` |
| `set_mode` | set mode from `?mode` | `{mode, isSuccess}` |
| `select_product` | core add/edit logic | `{reload, isSuccess, message, data}` |
| `get_init_data` | full UI state on load | `Controller::get_data()` |
| `update_product_attrs` | store chosen variation attrs, no cart change | `{isSuccess, data}` |
| `get_subtotal` | sum of **complete** collections only | `{isSuccess, data: wc_price}` |
| `get_product_tray_product_info` | rendered HTML for one product | `{isSuccess, data: html}` |
| `get_stone_options` | rendered stone-picker HTML for current ring | `{isSuccess, data: html}` |
| `product_is_valid` | stock/purchasability check | `{isSuccess, data: message}` |
| `set_step` | jump to arbitrary step | `{isSuccess}` |
| `remove_design` | delete a saved collection + cart lines | `{isSuccess}` |
| `set_init_mode` | set init_mode + mode from query | `{isSuccess}` |
| `reset` | clear tray/page state; blocked w/o `?force=1` if tray pending | `{isSuccess, data?}` |
| `change_item` | restore a collection into tray; blocked w/o `?force=1` | `{isSuccess, data}` |
| `cancel_editing` | abort edit, cart lines untouched | `{isSuccess, data}` |
| `go_back_step` | decrement step | `{isSuccess, isFirstStep, data}` |
| `remove_tray_item` | remove one tray item by uuid | `{isSuccess}` |
| `refresh_collection_ring` | re-render ring card after attr change | `{data, design_total, isSuccess}` |
| `clear_uncomplete_design` | purge incomplete collections pre-checkout | `{isSuccess}` |
| `is_valid_collections` | all collections have variation_id? | `{isSuccess, isValid, message}` |

### Misc

- `Controller::get_default_stone` — ring products can set a per-product ACF
  `default_stone`, falling back to a global Theme Options value.
- `is_building_ring()` (used everywhere incl. cache exclusion) — true if the
  `page-build-ring.php` template, OR (singular product AND `?mode=` present AND a
  session mode already exists AND the product is eligible: tagged **`build-a-ring`**
  for rings, or in the **`center-stones`** category for stones — see below).
- Body class `page-build-ring-wrapper` + inline `var currentStep = N;` are injected via
  `body_class`/`wp_head` hooks at the bottom of this same class file whenever
  `is_building_ring()` is true.

### Product eligibility (updated 2026-09-22)

Which products can be picked as a **ring/setting** vs. a **stone** in the wizard is
gated differently per side, and both places must stay in sync:

| Side | Eligibility | Enforced in |
|---|---|---|
| Ring / setting | In the **`rings`** product category AND **not** tagged **`exclude-build-a-ring`** (opt-out) | `template-parts/build-ring-step-1.php` (listing query) and `Controller::is_building_ring()` (gates the single-product build-ring UI) |
| Stone | In the **`center-stones`** product category | Same two places |

History: on 2026-08-16 rings were meant to switch from the `rings` category to an
**opt-in** `build-a-ring` tag (stakeholder decision — curated subset, safer default).
In practice the two gates shipped inconsistently: the step-1 listing was implemented
as **opt-out** (`rings` cat minus `exclude-build-a-ring` tag) while
`is_building_ring()` required the opt-in `build-a-ring` tag — and no products were
ever tagged `build-a-ring`. Result: every ring listed in step 1 but its product page
refused to enter build-ring mode. On 2026-09-22 both gates were unified on the
**opt-out** convention (matching what the listing already shipped and requiring no
bulk tagging). To hide a ring from build-a-ring, tag it `exclude-build-a-ring`.
If the stakeholder still wants strict opt-in, both places must be flipped together
AND every buildable ring must be tagged `build-a-ring` first.

⚠️ If a future change edits the eligibility condition in only one of the two places
above, a ring will list in step 1 but fail to activate the build-ring UI on its
product page (or vice versa) — keep both checks using the same condition if either
is touched again.

---

## 2. Custom Post Types (`inc/cpt/*`)

- **`header` / `footer`** — not content, **style presets**. Each post holds a set of
  ACF fields (colors, logo, social icons, background incl. video/parallax) consumed by
  `TTG_Config::get_header_config_by_id()` / `get_footer_config_by_id()`. Which preset
  applies to a given page is chosen via the `header_style` / `footer_style` ACF field on
  that page (global default lives on the Theme Options page).
  ⚠️ `header.php`'s CPT is registered with `rewrite => ['slug' => 'footer']` — copy-paste
  bug from `footer.php`, should be `'header'`.
- **`product-cta`** — title/editor/thumbnail/excerpt. Attached to products via the ACF
  repeater `product_ctas`, rendered on the single product page via
  `woocommerce_after_single_product_summary.php` → `single-product-cta` template part.
- **`product`** (`inc/cpt/product.php`) — empty file, dead code.

---

## 3. WooCommerce customizations (`inc/hooks/woo/*.php`, `inc/woo.php`)

### Product loop / archive
- Forces 4 columns/row (`loop_shop_columns.php`) and full-size thumbnails.
- `loop_shop_per_page.php`: 24 per page, but has an `is_singular('product')` branch
  intending 3-per-page — likely dead code since this filter fires in loop/archive
  context, not on singular product pages.
- Default result-count/notices/ordering UI stripped and replaced with a custom
  `products-filter` template part (FacetWP-driven).
- Product loop title area: "Center Stones INCLUDED" badge
  (`TTG_Product::is_included_stone()`), hover image swap via ACF `hover_feature_image`,
  primary category name appended after title, default "Add to cart" button removed
  entirely from the loop.
- Related products capped at 4.

### Single product page
- Default breadcrumb hook removed; breadcrumb (or, in build-ring mode, the wizard steps
  UI) is rendered instead via a custom `main-content-top` action.
- **Build-ring mode branch** (`woocommerce_single_product_summary.php`): shows only
  stock label + anchor + `product-service` part, no "add stone" button. **Normal mode**:
  shows the "add stone" button.
- **Special products** (ACF `is_special_product`): custom buttons instead of the
  standard add-to-cart form.
- **Out-of-stock simple (non-special) products**: cart form hidden, replaced with an
  "Inquire" CTA — these products cannot be back-ordered from the front end.
- Default WC product data tabs removed entirely; replaced with the ACF `product_ctas`
  repeater + a `product-gallery` template part. Mobile-only "Continue Shopping" link
  added.
- **"On backorder" is rebranded as "Made to Order"** storewide (status text + label) —
  deliberate, not a bug. Consistent with `woocommerce_hide_invisible_variations.php`
  forcing all variations visible (WC's default out-of-stock/incomplete-variation hiding
  is disabled) — the store treats backorder as a first-class sellable state.
- `pa_metal-type` variation options get `" (Ready to Ship)"` appended when in-stock
  variations of that metal exist (`TTG_Product::is_instock_meta_type()`).
- Variation dropdown placeholder relabeled `"Choose your {Attribute}"`.
- Quantity input hidden entirely when min===max stock-managed quantity (only one
  purchasable qty possible).

### Cart / checkout
- Header cart counter refreshed via `woocommerce_add_to_cart_fragments`.
- `woocommerce_cart_item_set_quantity.php` is registered but its callback body is
  **empty** — dead code.
- "Pick up in store" checkbox persisted as order meta on checkout, shown in wp-admin
  order edit screen.
- **Stone-required rule** (`TTG_Product::is_valid_cart()`): a product with ACF
  `center_stone = notInclude` requires a center-stone-category product also be in the
  cart. Enforced directly in `woocommerce/cart/cart-totals.php` and
  `woocommerce/checkout/payment.php` template markup (gates the checkout/payment
  button) — **not** via the standard `woocommerce_check_cart_items` validation hook, so
  it only blocks at those two render points. `add_cart_notices()` (would surface a
  `wc_add_notice` error) exists but its only call site is commented out in
  `wp_head.php` — effectively unused today.

### Breadcrumbs
- On product/category pages, breadcrumb "home" is retargeted from the site home to the
  **Shop page**.

### Sidebar
- `woocommerce_sidebar.php` — `remove_all_actions('woocommerce_sidebar')`: sidebar is
  disabled theme-wide.

### `inc/woo.php`
- Renders the sitewide store-notice banner (ACF `store_notice`/`active_store_notice`),
  with layout-shift compensation for the sticky header (partially unfinished — some JS
  is commented out).
- ⚠️ **Dormant discount backdoor**: an `init` hook checks `$_GET['ukirot']` and
  `die()`s immediately — but the code *after* that `die()` (unreachable today) would
  apply a hardcoded 20%-off + free-shipping to category ID 705 for anyone hitting that
  query param. Currently inert; should be deleted outright rather than left as live
  disabled logic.

---

## 4. FacetWP customizations

- `facetwp_facet_display_value.php` — relabels the raw "instock" value to "In Stock and
  Ready to Ship".
- `facetwp_index_row.php` — **`available_in_fairmined_gold`**: only indexes rows whose
  raw value contains the literal string `"fairmined-gold"` (hardcoded match, everything
  else excluded from the index). **`band_width`**: cast to float so it sorts/filters
  numerically instead of lexicographically.
- `facetwp_is_main_query.php` — prevents FacetWP from hijacking the blog home query as
  its "main query".
- `facetwp_result_count.php` — overrides default "Showing X–Y of Z" text with just
  `"{total} results"`.
- `inc/custom-facets.php` — doesn't register new facets; overrides the "show more"
  overflow label and forces `numDisplayed = 0` (no truncation) for ~20 hardcoded facet
  names (style, stones, clarity, color, metal_type, size, etc.) defined in the FacetWP
  admin UI, not in code.

---

## 5. Global AJAX (`inc/hooks/wp_ajax.php`)

- `get_cart_counter` — returns WC cart item count.
- `get_products_by_attr` — given a variable product + a `pa_metal-type` value, returns a
  `<select>` of `pa_size` options for that metal, prioritizing ones with real in-stock
  variations (annotated "(Ready to Ship)"), falling back to the full attribute term list
  otherwise. Powers the metal→size cascading dropdown (paired with `wp_footer.php`'s
  inline JS that re-triggers the gallery image swap on metal change).

Build-ring-specific AJAX is documented in §1.

---

## 6. Utility layer (`inc/util/*.php`)

- **`TTG_Util`** — template helpers (`generate_html_attrs`, `generate_classes`,
  `hex2rgba`, `get_assets_url`), `get_main_term()` (Yoast primary term w/ fallback), and
  **`get_acf_key()`** — resolves *which post's ACF fields* back a given page's
  header/footer/config: 404→options, product→shop page **unless** the product has
  `is_independence_header_config` set, taxonomy→same pattern, shop→shop page,
  home→posts page, search→configured search page.
  ⚠️ `TTG_Config::get_header_config()` / `get_footer_config()` implement a **similar but
  not identical** resolution path and don't check `is_independence_header_config` at
  all — two divergent "which config applies here" implementations worth reconciling.
- **`TTG_Product`** — stone-requirement business rule (see §3 cart/checkout), plus
  `is_instock_meta_type()` used by both the AJAX size-dropdown handler (§5) and the
  variation-label filter (§3).
- **`TTG_Config`** — pulls header/footer CPT (or options-page default) style fields; the
  actual reason the `header`/`footer` CPTs exist (§2).
- **`TTG_Template`** — documented in `CLAUDE.md`; no undocumented methods found.

---

## 7. Other hooks

- `body_attr_style.php` — emits `TTG_Config::get_header_config()` as `--header-*` CSS
  custom properties on the body — how header-style CPT data actually reaches frontend
  CSS (var injection, not inline styles per element). Also sets larger hero-banner
  height defaults on generic pages vs. shop/account/tax pages.
- `body_class.php` — adds `hide-quantity` / `is-product-special` body classes from ACF
  product flags.
- `pre_get_posts.php` — search includes both `post` and `product` types. Shop/category
  queries: exclude ACF `exclude_product_terms`, exclude `is_special_product` /
  `hide_product_from_catalog_listing` flagged products, **and exclude out-of-stock
  products via meta_query** (this — not a `post__not_in` approach, which is present but
  commented out — is the real out-of-stock exclusion mechanism).
- `save_post.php` — stamps `ttg_post_type` meta (0=post, 1=product) on save; consumer not
  identified (likely search/sort elsewhere).
- `wp_head.php` — forces `html{margin:0}` for logged-in non-admins (counteracts the WP
  admin bar); loads Microsoft Clarity analytics unconditionally on every page.
- `wp_footer.php` — inline JS: on `pa_metal-type` variation change, manually re-triggers
  the WC gallery image swap after an 800ms delay (workaround — WC doesn't do this
  automatically for this interaction).
- `excerpt_length.php` — excerpt forced to 15 words sitewide.
- `rocket-exclude-build-ring.php` — excludes the build-ring page (resolved dynamically,
  not a hardcoded slug) from WP Rocket full-page caching (session-driven flow can't be
  cached), and excludes build-ring JS + jQuery UI drag/drop files from
  minify/concat/delay-execution (breaks the drag-drop wizard otherwise).

---

## 8. Theme setup (`inc/setup.php`, `inc/acf-options-page.php`)

- Standard theme supports (title-tag, post-thumbnails, custom-logo, WooCommerce, one nav
  menu location).
- `the_content` filter: runs `force_balance_tags()`, then rewrites empty `<p></p>`
  (including `&nbsp;`-only paragraphs) into `<p class="empty">&nbsp;</p>` instead of
  letting WP strip them — deliberately preserves intentional spacing paragraphs from the
  WYSIWYG editor.
- One ACF options page ("Theme Options") — the `'options'` context used throughout
  (header/footer defaults, store notice, stone/ring taxonomy terms, etc.).

---

## 9. `template-parts/` — major groupings

- **`build-ring-*`** (13 files) — wizard UI: steps, tray, mini-collection, confirm form,
  stone options, collection items.
- **`product/`, `woo-*`** — gallery, service block, custom buttons, CTA, filter bar.
- **`content-single-product-*`** — normal vs. build-ring variants of the single product
  template (the theme branches the *entire* product template, not just summary hooks).
- General layout — header, footer, hero-banner, main-navigation, page-heading.
- Blog — post-item variants, related-posts, blog-filter.
- `icons/`, `order/` (pick-up-in-store admin partial) subdirectories.

---

## 10. Gutenberg blocks (`gutenberg/`)

Registered via an external `TTG_Blocks` helper in `gutenberg/init.php`:
`content-with-image`, `content-with-image-2`, `container`, `products`,
`products-special`, `posts`, `build-ring-selection` (the only block with its own JS
asset — the embeddable drag-drop ring picker). Extra CSS is conditionally enqueued when
core `quote` or `image` blocks are present on a page.

---

## 11. Known tech debt / worth revisiting

- `inc/cpt/header.php` — wrong rewrite slug (`'footer'` instead of `'header'`).
- `inc/cpt/product.php` — empty/dead file.
- `inc/gform.php` — empty; no Gravity Forms customizations exist despite the plugin
  being listed as relied-upon in `CLAUDE.md`.
- `inc/hooks/woo/woocommerce_cart_item_set_quantity.php` — registered with an empty
  callback body.
- `inc/hooks/woo/loop_shop_per_page.php` — `is_singular('product')` branch is likely
  unreachable in this filter's context.
- `inc/woo.php` — dormant `?ukirot=` discount backdoor behind a `die()`; delete rather
  than leave disabled.
- `TTG_Util::get_acf_key()` vs `TTG_Config::get_header_config()`/`get_footer_config()` —
  two different implementations of "which post's config applies here," and only one of
  them checks `is_independence_header_config`.
- `TTG_Product::add_cart_notices()` — dead code (only call site is commented out).
- `save_post.php`'s `ttg_post_type` meta — consumer not traced; verify it's still used
  before touching this hook.
