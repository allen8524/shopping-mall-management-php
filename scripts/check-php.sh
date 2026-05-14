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
  admin/product.php
  admin/member_delete.php
  admin/product_delete.php
  admin/product_insert.php
  admin/product_update.php
  admin/opt_delete.php
  admin/opts_delete.php
  admin/jumun_update.php
  admin/jumun_delete.php
  admin/faq_delete.php
  member_insert.php
  member_update.php
  qa_insert.php
  qa_update.php
  qa_delete.php
  juso/juso_insert.php
  juso/juso_update.php
  juso/juso_delete.php
  sj/sj_insert.php
  sj/sj_update.php
  sj/sj_delete.php
  order_ok.php
  cart_edit.php
)

for file in "${php_files[@]}"; do
  if [ -f "$file" ]; then
    run php -l "$file"
  fi
done

run git diff --check

if command -v rg >/dev/null 2>&1; then
  echo "+ rg short_open_tag scan"
  rg --pcre2 -n "<\?(?!php|=)" --glob "*.php" --glob "!db/shop62.sql" . && status=1 || true

  echo "+ rg cookie_admin scan"
  rg -n "cookie_admin" --glob "*.php" --glob "!db/shop62.sql" . || true

  echo "+ rg SQL exposure scan"
  rg -n 'exit\("에러:.*\$sql|exit\("에러:.*\$query|die\(.*\$sql|die\(.*\$query|exit\("에러 : " \. mysqli_error|DB 에러.*mysqli_error|DB 업데이트 에러|exit\("에러 : \$sql' --glob "*.php" --glob "!db/shop62.sql" . && status=1 || true

  echo '+ rg \$_REQUEST usage scan (informational)'
  rg -n '\$_REQUEST' --glob "*.php" --glob "!db/shop62.sql" . || true
else
  echo "ripgrep(rg)가 없어 패턴 검색을 건너뜁니다."
fi

exit "$status"
