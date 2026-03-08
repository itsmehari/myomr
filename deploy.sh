#!/usr/bin/env bash
set -e
git add .
git commit -m "${1:-deploy update}" || true
git push origin main
