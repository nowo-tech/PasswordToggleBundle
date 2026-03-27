#!/usr/bin/env sh
set -eu

# REQ-TEST-008 contract:
# - composer.json must define:
#   - "test": "phpunit ..."
#   - "test-coverage": "phpunit ... --coverage-text ... --coverage-clover coverage.xml ..."
# - Makefile target `test-coverage` must run:
#   composer test-coverage | tee coverage-php.txt
#   ./.scripts/php-coverage-percent.sh coverage-php.txt
# - .gitignore must include /coverage-php.txt

RAW_FILE="${1:-coverage-php.txt}"

if [ ! -f "$RAW_FILE" ]; then
  echo "ERROR: coverage output file not found: $RAW_FILE" >&2
  exit 1
fi

# Strip ANSI color sequences if present before extracting value.
VALUE="$(
  sed 's/\x1B\[[0-9;]*[A-Za-z]//g' "$RAW_FILE" \
    | awk '/^[[:space:]]*Lines:[[:space:]]+/ { gsub(/%/, "", $2); print $2; exit }'
)"

if [ -z "${VALUE:-}" ]; then
  echo "ERROR: Could not extract PHP Lines coverage percentage from ${RAW_FILE}" >&2
  exit 1
fi

if [ -t 1 ]; then
  RED="$(printf '\033[31m')"
  ORANGE="$(printf '\033[38;5;208m')"
  GREEN="$(printf '\033[32m')"
  RESET="$(printf '\033[0m')"
else
  RED=""
  ORANGE=""
  GREEN=""
  RESET=""
fi

COLOR="$GREEN"
if awk "BEGIN { exit !(${VALUE} < 50) }"; then
  COLOR="$RED"
elif awk "BEGIN { exit !(${VALUE} <= 85) }"; then
  COLOR="$ORANGE"
fi

printf 'Global PHP coverage (Lines): %s%s%%%s\n' "$COLOR" "$VALUE" "$RESET"
