# Changelog

All notable changes to this theme's tooling/docs are tracked here.
Format loosely follows [Keep a Changelog](https://keepachangelog.com/).

## [Unreleased] — 2026-08-16

### Added
- `deploy/pull.js` — pulls theme code from WPEngine live down to local (SFTP mirror,
  supports `--path=` and `--dry-run`).
- `deploy/env.js` + `.env.production` / `.env.staging` / `.env.example` — SFTP
  credentials moved out of source code, selected per-environment via `--env=`.
- `docs/LOGIC.md` — business-logic documentation for `inc/` (build-ring wizard,
  WooCommerce customizations, FacetWP, utils, known tech debt).
- `docs/PROGRESS.md` — running progress/decisions log.
- `deploy:staging`, `check:staging`, `test-connect:staging`, `pull:staging`,
  `pull:check:staging` npm scripts.

### Changed
- Moved `deploy.js`, `pull.js`, `setup-deploy.sh` out of `src/` into a new top-level
  `deploy/` folder, with its own `package.json` (`ssh2-sftp-client`, `dotenv`) separate
  from the Laravel Mix frontend build in `src/`.
- `deploy/setup-deploy.sh` — pre-push git hook now points at `deploy/deploy.js`
  (was `src/deploy.js`) and passes `--env=production` explicitly.
- `deploy.js` / `pull.js` — SFTP config now loaded from `.env.*` via `deploy/env.js`
  instead of being hardcoded.
- Both scripts exclude the `deploy/` folder itself from upload/download, so the
  tooling never overwrites itself via live.

### Docs moved
- `LOGIC.md` → `docs/LOGIC.md`.

### Changed (build-a-ring eligibility)
- Ring/setting eligibility in the build-a-ring wizard switched from the `rings` product
  category to an opt-in **`build-a-ring`** product tag — `template-parts/build-ring-step-1.php`
  (listing query) and `Controller::is_building_ring()` in
  `inc/classes/build-ring.class.php` (single-product build-ring UI gate) both updated to
  match. Stone eligibility is unchanged (still the `center-stones` category). See
  `docs/LOGIC.md` § Product eligibility for details.
- **Deployed to production** (`inc/classes/build-ring.class.php`,
  `template-parts/build-ring-step-1.php` only, via `deploy/deploy.js --files=`).
  Rings must have the `build-a-ring` tag applied in WooCommerce to appear in the wizard.

### Added
- `deploy/deploy.js` — new `--files=a,b,c` flag to deploy an explicit file list,
  bypassing the git-diff range logic (used for the eligibility fix above so unrelated
  uncommitted changes, e.g. `checkout.js`, weren't pushed along with it).

---

Older history predates this changelog — see `git log` for prior commits.
