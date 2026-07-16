# Release process

1. Update [CHANGELOG.md](CHANGELOG.md): move entries from `[Unreleased]` to a new `[X.Y.Z] - YYYY-MM-DD` section. (This project does not store version in `composer.json`; Packagist uses the git tag.)
2. Update [UPGRADING.md](UPGRADING.md) if the release has upgrade notes.
3. Run pre-release checks: `make release-check` (includes `check-no-cursor-coauthor`, cs-fix, cs-check, rector-dry, phpstan, test-coverage, and optionally demo healthchecks).
4. Commit all changes, create an annotated tag (e.g. `v1.2.10`), and push branch and tag. The release workflow will create the GitHub Release with the changelog.
5. Publish the package to Packagist if applicable (usually automatic when the tag is pushed).

After creating the release commit and tag, run `make check-no-cursor-coauthor` again **before** `git push` (REQ-GIT-001). The release commit itself is not covered by an earlier `release-check` run.

## Example for v2.0.4

```bash
git add docs/CHANGELOG.md docs/UPGRADING.md docs/RELEASE.md docs/CONTRIBUTING.md docs/GITHUB_CI.md README.md CODE_OF_CONDUCT.md Makefile .gitignore .github/workflows/ci.yml .githooks/ .scripts/check-no-cursor-coauthor.sh .scripts/strip-cursor-coauthor-from-history.sh .cursor/rules/01-git-commits.mdc composer.lock demo/symfony7/composer.lock demo/symfony8/composer.lock demo/symfony8-php85/composer.lock
git status   # review
git commit -m "Release 2.0.4: REQ-GIT-001 CI hygiene, Code of Conduct, and docs"
git tag -a v2.0.4 -m "Release 2.0.4: REQ-GIT-001 CI hygiene, Code of Conduct, and docs"
make check-no-cursor-coauthor
# History was rewritten to strip Cursor trailers — use force-with-lease once:
git push --force-with-lease origin main
git push origin v2.0.4
```
