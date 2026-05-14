# 관리자 관리 흐름

관리자 화면은 기존 PHP 파일 단위 구조를 유지하면서 회원, 상품, 옵션, FAQ, 주문을 관리합니다.

## 기본 흐름

1. 관리자는 `admin/login.php`에서 로그인합니다.
2. `admin/login_check.php`가 계정을 확인하고 관리자 session을 생성합니다.
3. 관리자 화면은 `admin/login_main_check.php`로 session 인증을 확인합니다.
4. 데이터 변경 요청은 POST + CSRF 토큰 검증 후 처리됩니다.

## 기능별 파일 요약

| 기능 | 목록/조회 | 등록 화면 | 등록 처리 | 수정 화면 | 수정 처리 | 삭제 처리 |
| --- | --- | --- | --- | --- | --- | --- |
| 회원 관리 | `admin/member.php` | - | - | `admin/member_edit.php` | `admin/member_update.php` | `admin/member_delete.php` |
| 상품 관리 | `admin/product.php` | `admin/product_new.php` | `admin/product_insert.php` | `admin/product_edit.php` | `admin/product_update.php` | `admin/product_delete.php` |
| 옵션 관리 | `admin/opt.php` | `admin/opt_new.php` | `admin/opt_insert.php` | `admin/opt_edit.php` | `admin/opt_update.php` | `admin/opt_delete.php` |
| 소옵션 관리 | `admin/opts.php` | `admin/opts_new.php` | `admin/opts_insert.php` | `admin/opts_edit.php` | `admin/opts_update.php` | `admin/opts_delete.php` |
| FAQ 관리 | `admin/faq.php` | `admin/faq_new.php` | `admin/faq_insert.php` | `admin/faq_edit.php` | `admin/faq_update.php` | `admin/faq_delete.php` |
| 주문 관리 | `admin/jumun.php` | - | - | `admin/jumun_info.php` | `admin/jumun_update.php` | `admin/jumun_delete.php` |

## 기능별 설명

### 관리자 로그인

- `admin/login.php`에서 관리자 아이디/비밀번호를 입력합니다.
- 성공 시 session을 재발급하고 관리자 세션 값을 저장합니다.
- 실패 시 로그인 화면으로 되돌아갑니다.

### 회원 관리

- `admin/member.php`에서 회원 검색과 목록 조회를 수행합니다.
- `admin/member_edit.php`에서 회원 정보를 수정하고 `admin/member_update.php`가 저장합니다.
- 삭제는 목록의 POST form을 통해 `admin/member_delete.php`로 요청됩니다.

### 상품 등록/수정/삭제

- `admin/product_new.php`와 `admin/product_edit.php`에서 상품 정보와 이미지를 입력합니다.
- 등록/수정 처리 파일은 업로드 이미지 확장자와 파일명을 점검하고 Prepared Statement로 DB를 갱신합니다.
- 수정 시 기존 이미지명은 DB 조회 결과를 기준으로 판단합니다.

### 옵션/소옵션 관리

- `admin/opt.php`는 옵션 목록, `admin/opts.php`는 특정 옵션의 소옵션 목록을 보여줍니다.
- 등록/수정/삭제 요청은 POST + CSRF 검증 후 처리됩니다.

### FAQ 관리

- `admin/faq.php`에서 FAQ 목록을 확인하고 등록/수정/삭제할 수 있습니다.
- 질문/답변 저장은 Prepared Statement로 처리합니다.

### 주문 조회/상태 변경

- `admin/jumun.php`에서 기간, 상태, 검색어 기준으로 주문 목록을 조회합니다.
- 주문 상태 변경과 주문 삭제는 같은 목록 화면의 form을 POST로 전송해 처리합니다.
- `admin/jumun_info.php`는 주문 상세 확인에 사용됩니다.
