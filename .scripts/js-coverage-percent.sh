#!/usr/bin/env sh
set -eu

RAW_FILE="${1:-coverage-js.txt}"

if [ ! -f "$RAW_FILE" ]; then
  echo "ERROR: coverage output file not found: $RAW_FILE" >&2
  exit 1
fi

VALUE="$(
  sed 's/\x1B\[[0-9;]*[A-Za-z]//g' "$RAW_FILE" \
    | awk '
      /^[[:space:]]*Statements[[:space:]]*:/ {
        for (i=1; i<=NF; i++) if ($i ~ /^[0-9]+(\.[0-9]+)?%$/) { gsub(/%/, "", $i); statements=$i; break }
      }
      /^[[:space:]]*Branches[[:space:]]*:/ {
        for (i=1; i<=NF; i++) if ($i ~ /^[0-9]+(\.[0-9]+)?%$/) { gsub(/%/, "", $i); branches=$i; break }
      }
      /^[[:space:]]*Functions[[:space:]]*:/ {
        for (i=1; i<=NF; i++) if ($i ~ /^[0-9]+(\.[0-9]+)?%$/) { gsub(/%/, "", $i); functions=$i; break }
      }
      /^[[:space:]]*Lines[[:space:]]*:/ {
        for (i=1; i<=NF; i++) if ($i ~ /^[0-9]+(\.[0-9]+)?%$/) { gsub(/%/, "", $i); lines=$i; break }
      }
      END {
        if (statements=="" || branches=="" || functions=="" || lines=="") exit 1
        min=statements+0
        b=branches+0
        f=functions+0
        l=lines+0
        if (b < min) min=b
        if (f < min) min=f
        if (l < min) min=l
        printf "%.2f", min
      }
    '
)"

if [ -z "${VALUE:-}" ]; then
  echo "ERROR: Could not extract JS coverage summary from ${RAW_FILE}" >&2
  exit 1
fi

echo "Global JS coverage (min of Statements/Branches/Functions/Lines): ${VALUE}%"
