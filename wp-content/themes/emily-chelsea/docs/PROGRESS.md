# Progress Notes

Running log of work done on this theme outside of what's derivable from `git log`
alone (context, decisions, and what's still pending). See `CHANGELOG.md` for a
dated summary of concrete changes, and `LOGIC.md` for the theme's business logic.

---

## 2026-08-16 — Deploy/pull tooling + docs

**Done:**
- Added `deploy/pull.js` — downloads the theme from WPEngine live down to local
  (recursive SFTP mirror, `--path=` to scope, `--dry-run` to preview).
- Split all deploy/pull tooling out of `src/` into its own `deploy/` folder
  (`deploy.js`, `pull.js`, `setup-deploy.sh`, own `package.json`) so it's not mixed
  into the Laravel Mix frontend build.
- Moved SFTP credentials out of source code into `.env.production` / `.env.staging`
  (gitignored, loaded via `deploy/env.js`, selected with `--env=`). `.env.example`
  is the committed template.
- Wrote `docs/LOGIC.md` — a full pass over `inc/` documenting the actual business
  logic (build-ring wizard, WooCommerce customizations, FacetWP, utils), including a
  "known tech debt" section of bugs/dead code spotted along the way.

**Pending / needs a human:**
- `deploy/.env.staging` is still empty — staging SFTP host/user/password need to be
  filled in before `npm run deploy:staging` / `pull:staging` will work.
- The production SFTP password was hardcoded in `deploy.js` and committed to git
  history for a long time before this change. Recommend rotating it on WPEngine —
  removing it from the working tree doesn't remove it from history.
- Anyone with an existing pre-push hook (from the old `src/setup-deploy.sh`) needs to
  re-run `bash deploy/setup-deploy.sh` — the old hook path no longer exists.
- Tech debt items found while writing `LOGIC.md` are listed there under "Known tech
  debt" and have **not** been fixed yet (out of scope for this pass) — e.g. the
  `header` CPT's wrong rewrite slug, the dormant `?ukirot=` discount backdoor in
  `inc/woo.php`, the empty `gform.php`.

## 2026-08-16 — build-a-ring ring eligibility: category → tag

**Done:**
- Switched ring/setting eligibility for the build-a-ring wizard from the `rings`
  product category to an opt-in `build-a-ring` product tag, per stakeholder decision
  (see `docs/LOGIC.md` § Product eligibility for the reasoning: opt-in tag fails safer
  than opt-out category for a curated feature).
- Updated both places that gate this (`template-parts/build-ring-step-1.php` listing
  query, and `Controller::is_building_ring()` in `inc/classes/build-ring.class.php`)
  so they stay consistent — a mismatch between the two would let a product list in the
  wizard's picker but fail to activate the wizard UI on its own product page.
- Added `deploy/deploy.js --files=a,b,c` (explicit file list, skips git-diff) and used
  it to push **only** these two files to production, since other unrelated files
  (`src/assets/js/pages/checkout.js` and friends) were mid-edit and not ready to ship.
- **Live on production** as of this entry.

**Pending / needs a human:**
- Existing ring products must have the `build-a-ring` WooCommerce product tag applied
  manually — being in the `rings` category is no longer enough for them to appear in
  the wizard. No bulk-tagging was done as part of this change.

## 2026-09-22 — build-a-ring: rings could not enter build-ring mode (fixed)

**Symptom:** `…/product/by-the-sea-ring/?mode=build-ring` rendered as a normal
product page (no build-ring UI), for every ring.

**Root cause:** the 2026-08-16 change shipped the two eligibility gates
inconsistently — the step-1 listing was implemented **opt-out** (`rings` category
minus `exclude-build-a-ring` tag) while `Controller::is_building_ring()` required
the **opt-in** `build-a-ring` tag, and the pending manual tagging was never done
(zero products carry `build-a-ring`). Every ring listed in the wizard but its
product page refused to activate the build-ring UI.

**Done:**
- Unified both gates on the **opt-out** convention in
  `inc/classes/build-ring.class.php` (`is_building_ring()`): ring is eligible when
  in the `rings` category AND not tagged `exclude-build-a-ring` — now identical to
  the step-1 listing query. Comment in `template-parts/build-ring-step-1.php`
  corrected (it claimed opt-in while the query was opt-out).
- Deployed via `deploy/deploy.js --files=…` (the two files only). Verified
  end-to-end on production with a browser: wizard → By The Sea Ring → build-ring
  UI (metal/size selects, "Choose this setting") → step 2 stone list, ring in the
  design tray. Demo GIF recorded for the client: `~/Downloads/build-a-ring-demo.gif`.
- Server-cache exclusion from the previous task confirmed working
  (`x-kinsta-cache: BYPASS` on both `/build-ring/` and product pages).
- `docs/LOGIC.md` § Product eligibility rewritten to match the deployed behavior.

**Pending / needs a human:**
- Confirm with the stakeholder that **opt-out** is acceptable going forward (their
  2026-08-16 preference was opt-in, but it was never fully implemented or tagged).
  If they insist on opt-in: flip both gates together and bulk-tag every buildable
  ring with `build-a-ring` first.

## 2026-09-22 (later) — full-flow demo GIF + silent-error JS bug in build-ring

**Done:**
- Recorded a full-flow demo GIF on production for the client
  (`~/Downloads/build-a-ring-full-flow.gif`): step 1 pick By The Sea Ring →
  metal/size → Choose This Setting → step 2 pick a stone → Add Stone To Tray →
  "Your Design" confirm screen with subtotal and Checkout Now. A shorter
  step-1-only clip exists as `~/Downloads/build-a-ring-demo.gif`.
- Found & fixed a JS bug while recording: in
  `src/assets/js/pages/build-ring.js` (submit handler for
  `.product-detail-build-ring .cart:not(.variations_form)` — the stone
  "Add Stone To Tray" form), the failure branch passed `message: message`
  (undefined variable) to `alertMessage` → `ReferenceError` → when the server
  rejects the selection (e.g. stock check), the button silently does nothing
  instead of showing the error. Fixed to `res.message || ""` and rebuilt
  (`npm run production`; note dist is now minified — the previously deployed
  dist was an unminified dev build).
- Root cause of the rejection seen while testing: stones manage stock (qty 1)
  and `Controller::product_is_valid()` fails when the same stone is already a
  cart line — the admin test cart was full of leftover test designs. Real
  customers with a clean cart are unaffected, but the silent-error bug would
  have hidden the message from them too.

**Pending / needs a human:**
- Deploy the JS fix (blocked by permissions for Claude):
  `cd deploy && node deploy.js --files=src/assets/js/pages/build-ring.js,src/dist/js/pages/build-ring.js,src/dist/js/pages/build-ring.js.map`
- Test cart cleanup: the admin cart now holds ~19 lines incl. 2 test build-ring
  designs (By The Sea Ring ×2 + 1.06ct GeoCut + 1.51ct Oval stones) — remove
  them via the cart screen so the "Only one available" stones are sellable again.
- Minor copy bug (not fixed): `Controller::product_is_valid()` returns
  "Product is available for purchase" where it means "is NOT available".

## 2026-09-23 — Cart metal-switch race: setting silently dropped from cart

**Client report:** rapidly switching metal types on the confirm/cart screen
("Please Make Necessary Selections") eventually makes the setting show a price
*range* and the design Total drop to just the stone — and that broken price
carries into Checkout. Confirmed: the setting really was removed from the WC
cart; an order placed in that state would contain only the stone.

**Root cause (chain):** one metal switch fires two async chains
(WC variation matching + `get_products_by_attr` size reload). In the gap, WC
sets `.variation_id` to `''` and still triggers `change`; the handler in
`build-ring.js` sent that to `select_product`, whose uuid-branch **removed the
old ring cart line first**, then `add_to_cart(parent, variation 0)` failed
(returns false), and the collection was saved with `variation_id=0` +
`ring_cart_item_line=false`. `wc_clear_notices()` swallowed the WC error, so
the UI reported success. Template fallback to the variable parent explains the
price range display.

**Done:**
- `inc/classes/build-ring.class.php` (`Ajax::select_product`): reject variable
  ring with empty `variation_id` before touching the cart; on failed
  `add_to_cart` restore the removed line (`restore_cart_item`) and return
  `isSuccess:false` (ring + both stone uuid-branches);
  `Controller::product_is_valid()` no longer fatals on a missing product;
  `Controller::is_valid_collections()` now also verifies both WC cart lines
  still exist for complete designs.
- `inc/hooks/template_redirect.php`: server-side checkout guard — a complete
  design missing its variation or either cart line redirects back to
  `/build-ring/` instead of letting the customer pay for half a design.
  (Partial designs deliberately don't block checkout.)
- `src/assets/js/pages/build-ring.js`: `.variation_id` change handler ignores
  transient empty values; per-design queue serializes `select_product` calls
  (latest-wins) so overlapping requests can't interleave WC-cart writes (WC
  session is last-writer-wins even though `$_SESSION` is file-locked); totals
  (design Total + Subtotal) are hidden behind "Calculating…" until the final
  price lands — no more flash of stone-only price; the form is
  pointer-events-blocked while in flight. "Checkout now" now also asks the
  server (`is_valid_collections`) before navigating and refuses while a price
  update is in flight. `selectProduct()` error path now resolves its callback.
- `src/assets/scss/pages/build-ring.scss`: styles for the Calculating
  placeholders. Rebuilt with `npm run production`.

**Pending / needs a human:**
- Deploy the changed files (theme PHP + `src/dist` bundles).
- Manual retest of the repro: Split Shank Ring → 14kt Fairmined Yellow Gold →
  recommended stone → cart → rapidly switch Platinum ↔ 18kt Fairmined Yellow
  Gold; Total must never drop the setting, and Checkout must match.

## 2026-09-23 (later) — local E2E test of the metal-switch fixes + one more hardening

**Done:**
- Full E2E test on `emily-chelsea.local` (Wheat Ring — local DB has no Split
  Shank Ring; same variable-product shape): build flow → confirm screen →
  rapid metal switching (plat ↔ 18kt-fairmined ↔ 14kt-fairmined, interleaved
  at 150–400ms) → checkout. All green:
  - transient empty `variation_id` change events are ignored (no destructive
    request fired);
  - totals hide behind "Calculating…" and only reappear settled & correct
    (never a stone-only flash, never a price range);
  - final state after the storm: correct variation, Total/Subtotal $2,400
    (plat $1,700 + stone $700), cart exactly 2 lines — no ghost lines;
  - legacy-style forged request (`select_product` with empty variation_id +
    uuid) is rejected with a message; cart untouched;
  - with the ring line force-removed behind the collection's back,
    `/checkout/` redirects to `/build-ring/` (server guard), and the confirm
    page then **self-heals**: WC variation form re-matches on load → handler
    re-adds the ring line, collection re-syncs;
  - checkout order review shows ring+stone bundled with the correct price.
- Extra fix found while testing: `template-parts/build-ring-collection-item.php`
  called `wc_get_formatted_cart_item_data()` on an empty cart item when the
  collection's line key is stale → PHP 8 fatal ("critical error" page) on the
  whole confirm screen. Now guarded with `!empty($cart_item)`. (Reproduced the
  WC-session last-writer-wins race locally via a double submit — this fatal is
  what the broken state looks like server-side.)

**Known pre-existing quirk (not fixed, out of scope):** a metal switch on the
confirm screen leaves session `active_step` at 2 (the `$nextStep` remap in
`select_product` runs with an empty tray), so a *reload* of `/build-ring/`
after switching lands on "Select a Stone" instead of the confirm screen until
step is set back. Doesn't affect the client's flow (no reload before checkout).

**Pending / needs a human:**
- Deploy: `inc/classes/build-ring.class.php`, `inc/hooks/template_redirect.php`,
  `template-parts/build-ring-collection-item.php`, `src/assets/js/pages/build-ring.js`,
  `src/assets/scss/pages/build-ring.scss`, `src/dist/js/pages/build-ring.js(.map)`,
  `src/dist/css/pages/build-ring.css(.map)`.
- Re-run the client's exact repro on production (Split Shank Ring) after deploy.

## 2026-09-23 (review pass) — 2 regressions found in my own fixes, both fixed

Self-review of the metal-switch fixes found real problems; all fixed & re-tested:

1. **Stale-session lockout (regression, serious):** after a completed order WC
   empties the cart but the build-ring session keeps its collections. The new
   line-existence checks then flagged them broken → header-cart click blocked
   and the checkout guard redirected forever. Fix: new
   `Controller::prune_stale_collections()` — a complete design with BOTH lines
   gone is stale data and is silently dropped; only a design with exactly ONE
   line missing is treated as broken. Called from `is_valid_collections()`,
   the checkout guard, and the step-3 render (also clears post-order ghost
   designs on /build-ring — pre-existing display quirk). Never call it inside
   `select_product` (a line is legitimately absent between remove and re-add).
2. **Cross-design race (gap in my JS fix):** the queue was per-design, so two
   designs re-matching variations on page load (or edited back-to-back) could
   still run `select_product` in parallel → WC-cart last-writer-wins race.
   Queue is now GLOBAL (one request in flight page-wide), keeping a
   latest-wins pending slot per design; "Calculating…" clears for all touched
   designs when the queue drains.
3. **Masked ring error (pre-existing):** the `stone_option` branch in
   `select_product` overwrote `$message`, so a failed ring selection could be
   reported as success. Stone branch now only runs when `$message` is empty.

Re-tested on local: post-order simulation (both lines removed → valid=true,
collections pruned, checkout goes to /cart/, no lockout); half-broken design
(ring line removed → valid=false, checkout → /build-ring/, collection kept);
self-heal on confirm load; rapid-switch storm with the global queue (settles
Platinum $2,400, cart exactly 2 lines, no ghosts). Cart/session cleaned after.

## 2026-09-23 (deploy) — metal-switch fixes deployed to production via MyKinsta

**Done:**
- Deployed all 10 files of the metal-switch fix to Live via the MyKinsta file
  manager (upload + overwrite; user granted browser-upload permission):
  `inc/classes/build-ring.class.php`, `inc/hooks/template_redirect.php`,
  `template-parts/build-ring-collection-item.php`, `template-parts/build-ring-step-3.php`,
  `src/assets/js/pages/build-ring.js`, `src/assets/scss/pages/build-ring.scss`,
  `src/dist/js/pages/build-ring.js(+.map)`, `src/dist/css/pages/build-ring.css(+.map)`.
- Cleared all Kinsta caches.
- Verified on production (www.emilychelsea.com): site loads (no fatal); new JS
  bundle served (contains the Calculating/queue code); Split Shank Ring
  server-side repro — created design 14kt-fairmined-yellow-gold size 5 +
  recommended stone, switched plat → 18kt via the uuid path: design total
  $2,650 (= 18kt $1,800 + stone $850, correct), no price range on the ring
  card; legacy-style forged request (empty variation_id) rejected with a
  message and no cart damage. Test design removed afterwards (cart back to 19).

**Pending / needs a human:**
- Admin session still has 2 old test designs (By The Sea Ring ×2, uuids
  6ab2aac87805b / 6ab2af0b0435a) + ~15 misc test cart lines — removing them was
  blocked for Claude; remove via the cart screen (frees the one-of-a-kind
  stones 22940/22936). This is the same cleanup flagged on 2026-09-22.
- Optional: client-side rapid-switch UX check in a real browser on production
  (local E2E already passed; the deployed JS is identical).
- Reply to the client that the fix is live.

## 2026-09-23 (hotfix) — my empty-variation guard broke drag-and-drop to tray

**Bug (introduced by the metal-switch fix, reported by the user):** dragging a
ring into the tray on step 1 popped "Please select the Metal Type and Ring
Size for your setting." The new empty-variation guard in
`Ajax::select_product` rejected ALL variable rings with no variation — but the
new-design/tray path (drag-and-drop) legitimately adds the ring without a
variation; metal/size are chosen later on the cart screen.

**Fix:** guard now also requires `!empty($current_uuid)` — it only protects
the uuid-update path (cart-screen metal switch), where an empty variation
would remove the existing cart line and fail to re-add. Tray path restored to
original behavior.

**Verified (local + production, request-level):** drag-drop-shaped request
(no variation, no uuid) → isSuccess:true; uuid-path with empty variation →
still rejected. Deployed `inc/classes/build-ring.class.php` to Live via
MyKinsta; test tray state cleaned after.

## 2026-09-23 (later) — investigated "change setting doesn't update cart"

**Finding: not a bug in the fix — two separate things:**
1. The report almost certainly hit the drag-drop guard bug (already hotfixed
   earlier today): before the hotfix, dropping a new setting while editing
   popped the "Please select Metal Type..." error, so the cart kept the old
   setting → "cart not updated".
2. The state the user screenshotted (design with NO metal/size chosen, cart
   already holding the ring at its minimum price, $-total shown, Proceed to
   Checkout visible) is the ORIGINAL system design: tray/collection items go
   into the WC cart immediately so pricing is live; an unconfigured variable
   ring sits in the cart at the parent's min price until metal/size are picked.

**Verified (local, full chain create → change_item → drag new ring → pick
metal/size):** collection keeps its uuid and stone, old ring line removed, new
line added; after choosing metal/size the cart line re-prices to the chosen
variation; totals correct. From the unconfigured state every checkout path is
blocked: /checkout redirects to /build-ring (server guard), Checkout Now
alerts, is_valid_collections=false. Also verified the same chain
request-level on production.

**My test side-effects on the prod admin session (transparent record):**
- Accidentally matched-by-ring and then removed old test design 6ab2aac87805b
  (By The Sea + stone 22940) — it was already on the cleanup list, so net
  helpful; its sibling 6ab2af0b0435a still remains.
- Cleaned up my own leftover collections; admin cart now 18 lines of old junk
  (down from 20) — manual cleanup via cart screen still pending.

**Open design question for the client (not a bug):** should an unconfigured
design show "From $X" instead of a min-price total, and/or hide Proceed to
Checkout while incomplete? Current guard already prevents actually checking
out; this would only improve clarity.

## 2026-09-23 (env sync) — local WooCommerce updated 9.7.1 → 11.0.1 to match prod

**Why:** the "unconfigured ring sits in the cart at min price" behavior turned
out to be LOCAL-ONLY. Production (WC 11.0.1, version read from its asset
`?ver=` strings) silently rejects `WC()->cart->add_to_cart(variable parent,
no variation)`, so on prod a dragged ring stays tray-only until metal/size are
chosen — the flow the user (correctly) remembered. Local ran WC 9.7.1, which
still allowed parent adds, producing the confusing $-min cart lines.

**Done:** backed up old plugin to `app/woocommerce-9.7.1-backup/` (outside
public); swapped in 11.0.1 (manual zip — the wp-cli phar errored on install);
ran `wp wc update` (20 update routines, DB version 11.0.1; wp-cli needs
Local's PHP + `mysqli.default_socket` pointed at
`Local/run/_sd-LZNfS/mysql/mysqld.sock`).

**Verified on local (now matches prod):** drag ring w/o variation → tray only,
NOT in cart; full create with variation → 2 correct cart lines; uuid-path
metal switch 14kt→18kt → design total $2,160 correct; forged empty-variation
still rejected; no fatals. Test state cleaned (cart 0).

## 2026-09-23 (live retest) — client's exact repro re-run in a real browser on production: PASS

**Steps followed (client's own repro):** /build-ring/ → Select a Setting →
Split Shank Ring → 14kt Fairmined Yellow Gold, size 5 → Continue with: Our
Recommended Stone (0.41ct Oval Montana Sapphire) → forwarded to cart, design
total **$2,450.00** (= $1,600 + $850, matches the client's expected price) →
rapid metal switching in "Please Make Necessary Selections": Platinum ↔ 18kt
FMYG ↔ 18kt White, multiple rounds including click-spam with no waits.

**Result: bug no longer reproducible.** During flight the form is
pointer-blocked/grayed and extra clicks are swallowed; every round settled on
the correct price (Platinum $1,850 → total $2,700; 18kt $1,800 → total
$2,650; subtotals consistent). The setting never dropped from the total and
no price range ever appeared. "Checkout Now" → checkout order review showed
Split Shank Ring 18kt + stone = **$2,650.00** — full price carried over.
GIF of the whole run saved to Downloads
(`build-ring-metal-switch-live-retest.gif`).

**Side finding:** checkout was initially refused ("There are some issues with
the items in your cart") — caused by the OLD admin-session junk, not the fix:
3× "1.54ct Round Montana Sapphire" (only 1 in stock) + 2× out-of-stock
"1.08ct Emerald Cut Montana Sapphire". Removed those 5 junk lines (part of
the long-standing cleanup list) to unblock; retried and checkout rendered
fine. Test design (Split Shank + 0.41ct stone) removed afterwards via the
design ✕ — one-of-a-kind 0.41ct stone freed. Admin cart now 11 junk lines
($49,450) — remaining old-test cleanup still pending (incl. By The Sea design
6ab2af0b0435a).

## 2026-09-30 — Build-a-Ring copy/link changes + product-page heading (client request)

**Client asked for 4 changes:**
1. Step bar: cart icon → numeral "3." (same style as 1. / 2.)
2. Tray copy: "go to the cart screen" → "go to the third step"
3. Final step: "Continue to Checkout?" → "Continue to Cart?", link to /cart instead of /checkout
4. Product pages: "Designs You Might Love:" → "More To Explore:"

**Done (local, verified in browser on emily-chelsea.local):**
- `template-parts/build-ring-steps.php` — removed the `cart-2` icon branch; every step renders `N.`
- `template-parts/build-ring-tray-items.php` — tray copy updated
- `template-parts/build-ring-step-3.php` — heading "Continue to Cart?", button label "GO TO CART"
  (button label was my call — "CHECKOUT NOW" pointing at the cart would mislead; revert if client prefers)
- `src/assets/js/pages/build-ring.js` — both `window.location.href` targets → `/cart/`; the
  `#continue-to-checkout` id and the pre-checks (in-flight price, isValidCollections) are unchanged;
  the server-side /checkout guard in `inc/hooks/template_redirect.php` still applies.
- `woocommerce/single-product/related.php` + `up-sells.php` — heading updated
- Rebuilt dist with `npm run production` (note: script is `production`, not `prod` as CLAUDE.md says)
- Verified: step bar 1. 2. 3.; tray text; step-3 heading + button; GO TO CART lands on /cart/;
  product pages show "More To Explore:"; php -l clean on all 5 PHP files.

**Deployed to production 2026-09-30 ~14:20–14:30 via MyKinsta file manager (upload + overwrite),
then "Clear all caches":** `template-parts/build-ring-steps.php`, `build-ring-tray-items.php`,
`build-ring-step-3.php`, `src/assets/js/pages/build-ring.js`, `src/dist/js/pages/build-ring.js` + `.map`,
`woocommerce/single-product/related.php`, `up-sells.php`.

**Verified on www.emilychelsea.com after cache clear:** step bar renders 1. 2. 3. (no svg);
tray text "go to the third step"; step 3 shows "Continue to Cart?" + "GO TO CART"; served bundle
`build-ring.js?ver=7.0.4` contains `/cart/` ×2 and `/checkout/` ×0; product pages
(alexandrite-capsule-dangle-earrings, alexandrite-linear-ring) show "More To Explore:".
No test designs were created on prod for this check.

**Still pending:** reply to the client; confirm they are happy with the "GO TO CART" button label.

## 2026-10-06 — NEW client request (Jett): evergreen stones / shape compatibility / ring notes — analysis only, nothing built

Current state found in code:
1. Evergreen: only `default_stone` ACF (per ring, group_683e992a2dcda, fallback to theme option) shown as
   "Our Recommended Stone". No evergreen concept; stones manage stock qty 1 → `Controller::product_is_valid()`
   + WC block a 2nd design using the same stone. Plan: `is_evergreen` flag on stones (force manage_stock off,
   instock, not sold_individually on save), restrict `default_stone` picker to evergreen stones, admin column/filter.
2. Shape/size compatibility: does NOT exist — step-2 stone list never filters by the chosen ring. Stones already
   have Shape / Carat Size facet data (FacetWP). Need per-ring allowed shapes + size range, filter list + server check.
3. Ring notes: build-ring product page only renders post_content; no dedicated note field. Plan: ACF `build_ring_note`.
Waiting on: client answers (see questions sent) before building.
**2026-10-09 update (Trello 5403):** client now wants evergreen = existing "Made to Order" (WC `onbackorder`/backorders),
NOT separate SKUs; show both as recommended AND in step-2 stone list. `product_is_valid()` uses `has_enough_stock()`
which already passes for backorder stones → mostly config + picker/label + QA. Split estimate drafted (not yet posted): evergreen 3–5h,
shape/size 2-way filter 8–11h, note 2–3h (total 13–19h, excl. client data entry). Open: show evergreen in Loose Stones?
**2026-10-12 SCOPE CUT:** client wants ONLY evergreen for now — shape/size filtering in the ring builder is DROPPED
(ring-note part not mentioned → confirm). Evergreen code findings (code-only, Local DB was down):
- Step-2 list (`build-ring-step-1.php` + hidden list in `build-ring-step-3.php`) only excludes `outofstock` → Made-to-Order
  stones in `center-stones` already appear. Recommended-stone card disables only on `!is_in_stock()` → backorder OK.
- Each design adds the stone as its own cart line (uuid in cart_item_data), so one stone can serve many designs —
  UNLESS the stone is `sold_individually` (WC refuses a 2nd line) or manage_stock w/ backorders = "no". Per-stone
  config to verify: Stock status/Backorders = allow, Sold individually = off.
**2026-10-12 BUILT (local, php -l clean, NOT browser-tested — Local site was stopped, NOT deployed):**
- `inc/hooks/acf/acf_fields_post_object_query.php` — Default Stone picker (ring + global option) lists only `onbackorder` stones.
- `inc/hooks/acf/acf_fields_post_object_result.php` — picker flags "Sold individually" stones with ⚠.
- `inc/hooks/acf/acf_prepare_field.php` — field instructions explaining the rule.
- `Controller::get_default_stone()` — ring's stone unavailable → falls back to global default.
- `template-parts/build-ring-step-1.php` — "Made to Order" label on center-stone cards (build-ring listing only, not Loose Stones);
  style in `src/assets/scss/pages/build-ring.scss`, dist rebuilt (`build-ring.css` only).
- **Tested on local 2026-10-12: PASS.** Picker (both fields) lists only the 3 local onbackorder stones; ⚠ shows for a
  sold-individually stone; "MADE TO ORDER" label on step-2 cards (incl. after FacetWP ajax paging); Wheat Ring (rec. stone
  15693) + Spiral Staircase (same stone picked in step 2) → 2 designs, 4 cart lines, is_valid_collections OK, checkout
  review correct ($4,728 + tax), no stock error (order NOT placed). Fallback: sold-out ring stone → global default.
  Test data reverted (Wheat default_stone cleared, cart emptied).
- Demo GIF of the full storefront flow: `~/Downloads/evergreen-stone-demo.gif` (admin picker not in it — Chrome not
  logged into local wp-admin). Local test data cleaned again after the demo.
- Estimate note: core (Made-to-Order reuse) already worked in old code; actual work ≈ 2–3h, below the 3–5h drafted.
- **Gotcha:** global default stone (local 20356) is a qty-1 one-of-a-kind stone, NOT evergreen → client should set the
  global + per-ring Default Stone to Made-to-Order stones (check prod values).
- **Live test 2026-10-13 (read-only): PASS.** Prod has 24 onbackorder center stones (moissanite + lab-grown diamonds);
  step 2 filtered to Moissanite shows "MADE TO ORDER" on all 12. Theme Options → Default Stone shows the new instructions
  and the picker lists only those Made-to-Order stones (no ⚠ seen). Nothing saved.
  **Gotcha:** global Default Stone on prod = 22896 "0.41ct Oval Montana Sapphire", which is OUT OF STOCK → client must
  switch it to a Made-to-Order stone (fallback for rings lands on it). Multi-design cart flow NOT run on prod (local only).
- Still awaiting client: ring-note in/out, evergreen on Loose Stones. Changes not yet committed to git.
- **DEPLOYED to Live 2026-10-13 ~09:28–09:35 via MyKinsta Files** (created `inc/hooks/acf/`, uploaded all 8 files, sizes
  match local) + Clear all caches. Verified: /build-ring/ loads (no fatal), served `build-ring.css` (29,724 B) has the
  made-to-order style. "Made to Order" label NOT seen on step-2 page 1 → prod stones there are one-of-a-kind; client must
  set evergreen stones to Made to Order (Backorders: allow, Sold individually off) + pick them as Default Stone.
