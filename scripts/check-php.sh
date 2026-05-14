#!/usr/bin/env bash
set -u

status=0
run() {
  echo "+ $*"
  "$@" || status=$?
}

php_files=(
  common.php
  admin/login_check.php
  admin/login_main_check.php
  admin/member_delete.php
  admin/product_delete.php
  admin/product_insert.php
  admin/product_update.php
  admin/opt_delete.php
  admin/opts_delete.php
  admin/jumun_update.php
  order_ok.php
  cart_edit.php
)

for file in "${php_files[@]}"; do
  run php -l "$file"
done

run git diff --check

if command -v rg >/dev/null 2>&1; then
  echo "+ rg short_open_tag scan"
  rg --pcre2 -n "<\?(?!php|=)" --glob "*.php" --glob "!db/shop62.sql" . && status=1 || true

  echo "+ rg cookie_admin scan"
  rg -n "cookie_admin" --glob "*.php" --glob "!db/shop62.sql" . || true

  echo "+ rg SQL exposure scan"
  rg -n 'exit\("에러:.*\$sql' --glob "*.php" --glob "!db/shop62.sql" . && status=1 || true
else
  echo "ripgrep(rg)가 없어 패턴 검색을 건너뜁니다."
fi

exit "$status"
