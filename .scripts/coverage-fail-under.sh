#!/usr/bin/env sh
set -eu

RAW_FILE="${1:?coverage output file}"
MIN_PCT="${2:-99}"

if [ ! -f "$RAW_FILE" ]; then
  echo "ERROR: coverage output file not found: $RAW_FILE" >&2
  exit 1
fi

STRIPPED="$(sed 's/\x1B\[[0-9;]*[A-Za-z]//g' "$RAW_FILE")"
VALUE="$(printf '%s\n' "$STRIPPED" | grep -m1 -E '^[[:space:]]+Lines:[[:space:]]+[0-9.]+%' | sed -n 's/.*Lines:[[:space:]]*\([0-9.]*\)%.*/\1/p')"

if [ -z "${VALUE:-}" ]; then
  echo "ERROR: Could not extract summary Lines percentage from ${RAW_FILE}" >&2
  exit 1
fi

if awk -v v="$VALUE" -v m="$MIN_PCT" 'BEGIN { exit !(v + 0 < m + 0) }'; then
  echo "ERROR: Line coverage ${VALUE}% is below required minimum ${MIN_PCT}%." >&2
  exit 1
fi

echo "OK: Line coverage ${VALUE}% (minimum ${MIN_PCT}%)."
