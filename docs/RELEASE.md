# Release process

1. Update [CHANGELOG.md](CHANGELOG.md): move entries from `[Unreleased]` to a new `[X.Y.Z] - YYYY-MM-DD` section and add the version link at the bottom. (This project does not store version in `composer.json`; Packagist uses the git tag.)
2. Run pre-release checks: `make release-check` (cs-fix, cs-check, rector-dry, phpstan, test-coverage, and optionally demo healthchecks).
3. Commit, tag (e.g. `v1.2.0`), and push. The release workflow will create the GitHub Release with the changelog.
4. Publish the package to Packagist if applicable.
