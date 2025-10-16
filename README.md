# relyonapi

This repository contains legacy PHP code being upgraded to PHP 8.4 in incremental, well-scoped commits to preserve functionality.

Upgrade plan:
- Initial commit with README only (baseline)
- Folder-by-folder compatibility fixes (no functional changes)
- Validation with `php -l` and lightweight smoke tests where possible

Notes:
- Target PHP version: 8.4
- Keep changes minimal and reversible; track each folder in its own commit.
