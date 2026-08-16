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
