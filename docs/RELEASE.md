# Release process

1. Update [CHANGELOG.md](CHANGELOG.md): move entries from `[Unreleased]` to a new `[X.Y.Z] - YYYY-MM-DD` section. (This project does not store version in `composer.json`; Packagist uses the git tag.)
2. Update [UPGRADING.md](UPGRADING.md) if the release has upgrade notes.
3. Complete the [Release security checklist (12.4.1)](SECURITY.md#release-security-checklist-1241) (REQ-SEC-002), including a passing [AI security audit](SECURITY.md#ai-security-audit) grade (REQ-SEC-004), and record the confirmation in the release PR or tag notes.
4. Run pre-release checks: `make release-check` (includes `check-no-cursor-coauthor`, cs-fix, cs-check, rector-dry, phpstan, test-coverage, and optionally demo healthchecks).
5. Commit all changes, create an annotated tag (e.g. `v1.2.10`), and push branch and tag. The release workflow will create the GitHub Release with the changelog.
6. Publish the package to Packagist if applicable (usually automatic when the tag is pushed).

After creating the release commit and tag, run `make check-no-cursor-coauthor` again **before** `git push` (REQ-GIT-001). The release commit itself is not covered by an earlier `release-check` run.

## Example for v2.2.1

```bash
git add -A
git status   # review
git commit -m "Release 2.2.1: FrankenPHP sticky Kernel audit (reset_kernel false)"
git tag -a v2.2.1 -m "Release 2.2.1: FrankenPHP sticky Kernel audit (reset_kernel false)"
make check-no-cursor-coauthor
git push origin main
git push origin v2.2.1
```
