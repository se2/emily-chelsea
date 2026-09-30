# Emily Chelsea — Website Rules Reference

Plain-language documentation of every rule, sync, and automated behavior set up on the
website. Written for anyone managing the store (not just developers). For code-level
detail see `LOGIC.md`; for theme structure see `CLAUDE.md`.

Last updated: 2026-08-28

---

## 1. Automated syncs & scheduled jobs

### Meta (Facebook/Instagram) product feed — hourly
- The site automatically regenerates a product data feed **every hour** via WP-Cron
  (`tools/generate-datafeed.php`, loaded from `functions.php`).
- Output file: `/wp-content/uploads/meta-feed.csv` — this is the URL Meta Commerce
  Manager pulls from.
- **Which products are included:** published products in the categories
  `bracelets`, `pins`, `earrings`, `necklaces`, `other-rings`. Products outside these
  categories (e.g. engagement rings, center stones) are **not** in the feed.
- Variable products are exported **one row per variation** (variation price, stock
  status, variation image, grouped under the parent via `item_group_id`).
- Fixed values: brand = "Emily Chelsea Jewelry", condition = "new", currency = USD.
- Google product categories are mapped from the WooCommerce category
  (e.g. `necklaces` → *Apparel & Accessories > Jewelry > Necklaces*).
- **Manual regeneration:** a logged-in administrator can visit any page with
  `?regen_meta_feed=1` appended to force an immediate rebuild and see the feed URL.

### ACF field sync (`acf-json/`)
- All custom field definitions (product config, theme options, block fields) are saved
  as JSON files in the theme's `acf-json/` folder whenever a field group is edited.
- These files are the **source of truth** — they are committed to git and deployed like
  code. After a deploy, ACF shows a "Sync available" notice in
  **Custom Fields → Field Groups**; syncing applies the new definitions to the database.

### Deployment sync (developer-side)
- Code is deployed over SFTP by `deploy/deploy.js`: it uploads only the theme files
  changed in a git commit range (default: last commit), and deletes remotely what was
  deleted in git. Supports `--dry-run`, `--files=...` (explicit list), `--range=...`.
- `deploy/pull.js` does the reverse — pulls the live theme down over SFTP (optionally a
  single folder via `--path=`), used to sync local with production.
- `node_modules`, `.cache`, `.DS_Store` and the `deploy/` folder itself are never
  uploaded.

---

## 2. Product filters (FacetWP)

Product/blog listing filters are powered by the **FacetWP** plugin. The facets
themselves (Style, Stones, Clarity, Color, Metal Type, Size, Shape, Origin, Texture,
Carat Size, Side Stones, Jewelry Type, category filter, etc.) are defined in
**Settings → FacetWP** in wp-admin — not in code. The theme then applies these rules on
top:

### Display rules (code)
- Every facet's option list is shown **in full** — the "show more / show less"
  truncation is disabled (`numDisplayed = 0`) for ~20 known facets, and each facet's
  overflow label is set to a custom heading (e.g. "METAL TYPE", "SHAPE", "Carat Size").
- The stock facet's raw `instock` value is relabeled to **"In Stock and Ready to Ship"**.
- The results counter reads simply **"{N} results"** instead of WooCommerce's
  "Showing X–Y of Z".

### Indexing rules (what the filters can "see")
- **Available in Fairmined Gold** facet: only indexes values containing
  `fairmined-gold` — anything else is excluded from that filter entirely.
- **Band Width** values are converted to numbers so they sort/filter numerically
  (0.9, 1.5, 10) instead of alphabetically (0.9, 1.5 … then 10 misplaced).
- FacetWP is prevented from taking over the blog home page query.

### Which filter bar appears where (Theme Options)
The filter bar shown above product listings is assembled from **Theme Options →
Products**:
- **Default Filter** — the standard set of facet shortcodes shown on shop/category
  pages (each row: label + FacetWP shortcode, optional custom-dropdown styling).
- **Custom Filter** — per-category / per-page overrides: match by taxonomy term, page,
  or URL, and supply a different set of facet shortcodes for that context.
- **Blog** tab holds the blog search + blog filter shortcodes; **Search** tab holds the
  search-page sort shortcode.

So: *what a filter contains* is managed in FacetWP settings; *which filters show on
which page* is managed in Theme Options.

---

## 3. Build-A-Ring

A three-step wizard for assembling a custom ring (Setting + Stone). Two entry flows,
each with its own landing page (set in **Theme Options → Build Ring**):
- **Start with Setting** → Setting → Stone → Confirm
- **Start with Stone** → Stone → Setting → Confirm
The order is locked once the customer starts and stays fixed for their session.

### Which products appear in the wizard (important!)
| Wizard side | Rule |
|---|---|
| Settings (rings) | Product must have the **`build-a-ring` product tag** |
| Center stones | Product must be in the **`center-stones` product category** |

- The `rings` category alone is **not** enough — a ring that should be buildable must
  be explicitly tagged `build-a-ring`. This is deliberate (opt-in), so new ring
  products never appear in the wizard by accident.
- The stone side is category-driven: anything in `center-stones` shows automatically.
- The "stone" and "ring" categories themselves are configurable in
  **Theme Options → Products → Stone Term / Ring Term**.

### How it behaves
- The in-progress design (the "tray") is mirrored into the WooCommerce cart
  immediately, so pricing/stock are always live.
- Completed designs are saved as **collections**; a customer can build several designs
  in one session — each keeps its own cart lines even if the products are identical.
- Editing a saved design puts it back in the tray; if another design was in progress,
  the customer is warned before it's replaced (auto-saved if complete, discarded if
  not). Cancelling an edit leaves the cart untouched.
- **Default stone**: each buildable ring can name its own default stone (ACF field on
  the product); otherwise the site-wide default from Theme Options is used.
- **Pre-checkout guard**: designs missing a ring or a stone are purged (with their cart
  lines) before checkout, so half-finished designs can never become an order.
- The build-ring page is **excluded from WP Rocket page caching** (it's session-driven),
  and its JavaScript is excluded from minify/combine/delay — do not change those Rocket
  exclusions or the drag-and-drop wizard breaks.

---

## 4. Product & catalog rules

### What shows in the shop / category listings
- **Out-of-stock products are hidden** from shop and category listings automatically.
- Products flagged **"Is Special Product"** or **"Hide product from catalog listing"**
  (Product Config fields on the product edit screen) are excluded from listings.
- Terms selected in **Theme Options → Products → Exclude Terms from Shop Page** are
  excluded from the shop page.
- Layout: 4 products per row, 24 per page; "Add to cart" buttons are never shown in
  the product grid; related products are capped at 4.
- Site search returns both blog posts **and** products.

### Product-page behavior driven by Product Config (ACF fields per product)
| Field | Effect |
|---|---|
| Center Stone = `include` | "Center Stones INCLUDED" badge in listings |
| Center Stone = `notInclude` | Product **requires a center stone in the cart** to check out (see §5) and shows an "ADD A CENTER STONE" prompt |
| Is Special Product | Replaces the normal add-to-cart form with custom CTA buttons |
| Hide Quantity | Hides the quantity selector |
| Hide Product Service | Hides the services/benefits block |
| CTAs | Attaches reusable **Product CTA** posts (rendered below the summary — the standard WooCommerce tabs are removed site-wide) |
| Gallery / Video Gallery | Custom image + video gallery section |
| Hover Feature Image | Second image shown on hover in the product grid |

The badge icon/wording ("… INCLUDED", "ADD A CENTER STONE") is editable per category on
the stone category's term edit screen (Combine Product Indicator fields).

### Stock & availability wording
- **"On backorder" is renamed "Made to Order"** everywhere — this is intentional.
  Backorder is treated as a normal, sellable state; variations are never hidden for
  being incomplete/out of stock.
- Metal-type options gain a **"(Ready to Ship)"** suffix when that metal has real
  in-stock variations; the size dropdown re-populates per selected metal, prioritizing
  in-stock sizes.
- Variation dropdowns read "Choose your {Attribute}".
- **Out-of-stock simple products** (non-special) hide the cart form and show an
  "Inquire" button instead — they cannot be purchased from the front end.
- The quantity box disappears when only one quantity is purchasable.

---

## 5. Cart & checkout rules

- **Stone-required rule:** if any cart item has *Center Stone = Not Included*, the cart
  must also contain a product from the center-stone category, or the checkout/payment
  button is blocked. (Enforced on the cart totals and payment sections.)
- **Pick up in store:** a checkbox at checkout, saved on the order and shown to staff
  on the wp-admin order screen.
- The header cart counter updates live via AJAX fragments.
- Breadcrumbs on product/category pages point "home" to the **Shop page**, not the
  site front page.
- The WooCommerce Product Dependencies plugin is also consulted (`tied products /
  categories`) for products that depend on other products.

---

## 6. Site-wide settings (Theme Options)

All under **Theme Options** in wp-admin:

- **Store notice banner** — on/off toggle, message (WYSIWYG), background & text colors.
- **Header/Footer style presets** — the `Header` and `Footer` post types are *style
  presets* (colors, logo, backgrounds, socials), not content. Each page/product can
  pick its own preset (Header/Footer Page Config fields); the site-wide defaults live
  in Theme Options. Products normally inherit the Shop page's preset unless
  "Is Independence Header Config" is ticked on the product.
- **Products tab** — stone/ring terms, default stone, filter bars (§2), category
  purchase dependencies, product services (icons + labels, visible per category),
  inquiry form, "Book an appointment" link.
- **Blog / Search / 404 tabs** — search shortcode, blog filters, designated search and
  404 pages.
- **Build Ring tab** — the two wizard entry pages (§3).

---

## 7. Content & appearance rules

- Blog excerpts are capped at **15 words** site-wide.
- Empty paragraphs typed in the editor are deliberately **preserved** as spacing
  (normally WordPress strips them).
- Hero banners are configurable per page/post/product/category (ACF Hero Banner
  fields), with taller defaults on general pages than on shop/account pages.
- Custom Gutenberg blocks available in the editor: Content With Image (1 & 2),
  Container, Products, Products Special, Posts, and **Build Ring Selection** (the
  embeddable drag-and-drop ring picker).

---

## 8. Analytics & caching

- **Microsoft Clarity** analytics loads on every page.
- **WP Rocket** page caching runs site-wide **except** the build-ring flow (§3).

---

## 9. One-off admin utilities (`tools/`)

Maintenance scripts, not part of normal site behavior (and — except the Meta feed
generator — not loaded by the theme):

- `generate-datafeed.php` — the Meta feed (§1). **This one IS active in production.**
- `remove-duplicate.php` — deletes duplicate variations (same attribute combination).
- `remove-products.php` — deletes a hardcoded list of products by ID/SKU.
- `update-taxable.php` — CSV-driven image alt-text/taxable updater (mostly commented
  out).
- These act immediately when triggered — use with care and only with a backup.

---

## Quick answers

- **"Why doesn't my new ring show in Build-A-Ring?"** → It needs the `build-a-ring`
  product tag (§3).
- **"Why is a product missing from the shop page?"** → It's out of stock, flagged
  special/hidden, or in an excluded term (§4).
- **"Why can't a customer check out?"** → A "Not Included" center-stone product is in
  the cart without a stone (§5), or an incomplete build-ring design was purged.
- **"Why does the feed not have product X?"** → The Meta feed only covers bracelets,
  pins, earrings, necklaces and other-rings (§1).
- **"Where do I edit the filters?"** → Facet contents: Settings → FacetWP. Which
  filters appear where: Theme Options → Products (§2).
