# Release process

1. Update [CHANGELOG.md](CHANGELOG.md): move entries from `[Unreleased]` to a new `[X.Y.Z] - YYYY-MM-DD` section. (This project does not store version in `composer.json`; Packagist uses the git tag.)
2. Update [UPGRADING.md](UPGRADING.md) if the release has upgrade notes.
3. Run pre-release checks: `make release-check` (cs-fix, cs-check, rector-dry, phpstan, test-coverage, and optionally demo healthchecks).
4. Commit all changes, create an annotated tag (e.g. `v1.2.10`), and push branch and tag. The release workflow will create the GitHub Release with the changelog.
5. Publish the package to Packagist if applicable (usually automatic when the tag is pushed).

## Example for v2.0.2

```bash
git add docs/CHANGELOG.md docs/UPGRADING.md docs/RELEASE.md README.md
git status   # review
git commit -m "Release 2.0.2: GitHub Spec Kit baseline and dev dependency updates"
git tag -a v2.0.2 -m "Release 2.0.2: GitHub Spec Kit baseline and dev dependency updates"
git push origin main
git push origin v2.0.2
```
