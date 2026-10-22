#!/usr/bin/env bash
# REQ-TWIG-005 — fail when bundle views or demo templates submit forms with raw <form> / <input> / <textarea> /
# <select> markup instead of Symfony Form (form_start / form_widget). Form themes (*theme*.html.twig and views/Form/*) are exempt,
# and so is a control that is never submitted when the line above it carries `{# twig-005-ignore: <reason> #}`.
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"

dirs=()
[[ -d src/Resources/views ]] && dirs+=(src/Resources/views)
for d in demo/*/templates; do [[ -d "$d" ]] && dirs+=("$d"); done
if [[ ${#dirs[@]} -eq 0 ]]; then
  echo "check-no-raw-html-form: no Twig views — OK"
  exit 0
fi

matches=""
while IFS= read -r hit; do
  [[ -n "$hit" ]] || continue
  file="${hit%%:*}"; rest="${hit#*:}"; line="${rest%%:*}"
  if [[ "$line" -gt 1 ]] && sed -n "$((line - 1))p" "$file" | grep -q 'twig-005-ignore'; then
    continue
  fi
  matches+="$hit"$'\n'
done < <(grep -RInE '<(form|input|textarea|select)([[:space:]>]|$)' "${dirs[@]}" --include='*.twig' | grep -v -E '(/[^/]*theme[^/]*\.html\.twig|/views/[Ff]orm/[^:]*):' || true)

if [[ -n "$matches" ]]; then
  echo "ERROR: raw form markup in Twig (REQ-TWIG-005):" >&2
  printf '%s' "$matches" >&2
  exit 1
fi

echo "check-no-raw-html-form: OK (${dirs[*]})"
