# 쇼핑몰 판매관리 사이트

## 프로젝트 개요
이 프로젝트는 PHP와 MySQL(MariaDB) 기반으로 쇼핑몰의 기본 판매 흐름을 학습하기 위해 만든 개인 프로젝트입니다.
사용자는 메인/목록/상세 페이지에서 상품과 옵션을 확인한 뒤 장바구니에 담고, 주문 정보를 입력해 주문을 저장할 수 있습니다.
장바구니는 쿠키(JSON)로 관리되고, 주문 시에는 주문 마스터(`jumun`)와 주문 상세(`jumuns`)로 나누어 저장됩니다.
관리자는 별도 관리자 화면에서 상품, 옵션, 주문, FAQ, 회원 데이터를 조회/수정/삭제하며 운영 데이터를 확인할 수 있습니다.
즉, 상품 조회 → 장바구니 → 주문 저장 → 관리자 주문 확인 흐름을 한 프로젝트에서 확인하는 데 목적이 있습니다.

## 개발 목적
- PHP 기반 웹 페이지 흐름 이해
- MariaDB/MySQL 연동을 통한 데이터 처리
- 상품, 옵션, 주문 데이터의 연결 구조 학습
- 관리자 화면 기반 상품/주문 관리 구현
- Bootstrap을 활용한 반응형 화면 구성

## 주요 기능
- 상품 목록 조회: 메인/카테고리/검색 페이지에서 상품 목록을 조회
- 상품 상세 조회: 상품 기본 정보, 아이콘, 옵션(상위/하위 옵션 테이블 기반), 수량별 금액 확인
- 장바구니: 상품 ID/수량/옵션 조합을 쿠키(JSON)로 저장, 수량 수정/개별 삭제/전체 비우기 지원
- 주문 처리: 주문자/수령자 정보를 입력하고 결제 방식(카드/무통장)을 선택해 주문 저장
- 주문 저장 구조: `jumun`(주문 마스터) + `jumuns`(주문 상세)로 분리 저장 및 트랜잭션 처리
- 주문 조회: 회원 주문 조회, 비회원(이름+이메일) 주문 조회, 주문 상태 확인
- 관리자 상품 관리: 상품 목록 검색/필터, 등록, 수정, 삭제, 이미지 업로드
- 관리자 주문 관리: 기간/조건 검색, 주문 상세 조회, 주문 상태 변경, 주문 삭제
- 관리자 옵션/FAQ/회원 관리: 옵션/소옵션, FAQ, 회원 정보 관리 화면 제공

## 기술 스택
- Backend: PHP
- Database: MySQL / MariaDB
- Frontend: HTML, CSS, Bootstrap, JavaScript, jQuery
- Tool: phpMyAdmin
- Server Environment: Apache + PHP + MySQL(MariaDB) 로컬 환경(예: XAMPP/Laragon 등 환경에 맞게 구성)

## 프로젝트 구조
- `common.php`: DB 연결, 공통 옵션 배열, 공통 페이징 함수(`mypagination`)
- `index.html`, `main.php`, `main_top.php`, `main_bottom.php`: 사용자 메인 진입 및 공통 레이아웃
- `menu.php`, `product.php`, `product_search.php`: 상품 목록/상세/검색
- `cart.php`, `cart_edit.php`: 쿠키 기반 장바구니 조회/수정
- `order.php`, `order_pay.php`, `order_ok.php`: 주문/결제 정보 입력 및 주문 저장
- `jumun_login.php`, `jumun.php`, `jumun_info.php`: 회원/비회원 주문 조회
- `admin/`: 관리자 로그인, 회원/상품/주문/옵션/FAQ 관리
- `product/`: 상품 이미지 업로드/저장 폴더
- `images/`: UI 및 공통 이미지
- `db/shop62.sql`: DB 스키마 및 샘플 데이터

## DB 설계 요약
| 테이블 | 주요 역할 | 주요 컬럼 |
| --- | --- | --- |
| member | 회원 정보 저장 | id, uid, pwd, name, tel, email, zip, juso |
| product | 상품 정보 저장 | id, menu, code, name, price, status, discount, image1~3 |
| opt | 상위 옵션 그룹 저장 | id, name |
| opts | 하위 옵션 값 저장 | id, opt_id, name |
| jumun | 주문 마스터 정보 저장 | id, member_id, jumunday, o_name/o_email, r_name/r_email, pay_kind, totalprice, state |
| jumuns | 주문 상품 상세 저장 | id, jumun_id, product, num, price, prices, opts_id1~3 |
| faq | FAQ 저장 | id, ask, answer |
| qa | Q&A 게시판 저장 | id, pos1, pos2, title, name, contents |

관계(코드와 컬럼 기준으로 확인 가능한 범위):
- `jumun.id` : `jumuns.jumun_id` = 1:N (주문 1건에 여러 상품 상세)
- `opt.id` : `opts.opt_id` = 1:N (옵션 그룹별 하위 옵션)
- `product.id` : `jumuns.product` = 1:N (상품과 주문 상세 연결)

## 핵심 구현 포인트
### 1. 상품 조회부터 주문 저장까지의 흐름
- `product.php`에서 상품/옵션/수량을 선택하고 `cart_edit.php`로 전송해 장바구니 쿠키에 저장합니다.
- `cart.php`에서 쿠키 데이터를 기준으로 상품 정보를 DB에서 조회해 주문 금액을 계산합니다.
- `order.php` → `order_pay.php` → `order_ok.php` 순서로 주문/결제 정보를 확인하고 최종 저장합니다.
- 저장된 주문은 `jumun.php`, `jumun_info.php` 및 관리자 `admin/jumun.php`에서 조회할 수 있습니다.

### 2. 주문 마스터/주문 상세 데이터 구조
- 장바구니는 쿠키(JSON)에 `상품ID^수량^옵션1^옵션2^옵션3` 형태로 저장되며, 주문 저장 시 서버에서 다시 검증합니다.
- `order_ok.php`는 쿠키에 담긴 상품 가격을 신뢰하지 않고, 주문 저장 직전에 `product` 테이블에서 상품명/가격/할인율을 다시 조회합니다.
- 주문번호는 기존 형식인 `yymmdd + 4자리 순번`을 유지합니다.
- 주문 공통 정보는 `jumun`(주문 마스터)에 1건으로 저장하고, 각 상품/수량/옵션/금액은 `jumuns`(주문 상세)에 반복 저장합니다.
- 주문 마스터와 주문 상세 저장은 하나의 트랜잭션으로 묶어 일부만 저장되는 상황을 줄였습니다.
- 장바구니 쿠키(`cart`, `n_cart`)는 주문 저장이 성공적으로 commit된 뒤에만 삭제합니다.
- 주문 상세에는 옵션 ID(`opts_id1~3`)도 함께 저장해 옵션 조합을 추적할 수 있습니다.

### 3. 관리자 화면 기반 상품/주문 관리
- 상품: `admin/product.php`, `admin/product_new.php`, `admin/product_edit.php`에서 등록/조회/수정/삭제 및 이미지 업로드를 처리합니다.
- 주문: `admin/jumun.php`, `admin/jumun_info.php`, `admin/jumun_update.php`에서 주문 검색, 상세 확인, 상태 변경을 처리합니다.
- 옵션/FAQ: `admin/opt.php`, `admin/opts.php`, `admin/faq.php`에서 운영 데이터 관리가 가능합니다.


## 보안 관련 참고
이 프로젝트는 로컬 학습 및 포트폴리오 목적의 PHP 쇼핑몰 예제입니다. 실서비스 수준의 결제 승인, 개인정보 암호화, 접근 로그, 비밀번호 해시 정책, CSRF 방어, 파일 업로드 보안 정책이 모두 완성된 상용 시스템은 아닙니다.

현재 보완된 내용은 다음과 같습니다.
- 관리자 인증은 클라이언트가 임의로 만들 수 있는 `cookie_admin=yes` 쿠키 기준에서 PHP session 기준으로 개선했습니다.
- 관리자 로그인 성공 시 `session_regenerate_id(true)`를 호출해 세션 고정 공격 위험을 줄였습니다.
- 관리자 페이지 접근은 `$_SESSION["admin_id"]`, `$_SESSION["admin_login"]` 값을 기준으로 확인합니다.
- 상품 상세 ID, 카테고리/정렬 값, 관리자 검색/주문 상태 변경 등 주요 입력값에 정수 캐스팅, 허용값 검증, 문자열 escape 또는 Prepared Statement를 일부 적용했습니다.
- 주문 저장 시 장바구니 쿠키 값을 그대로 신뢰하지 않고 상품 ID/수량/옵션 ID를 서버에서 검증하며, 상품 가격은 DB에서 재조회합니다.

실서비스 적용 시에는 다음 항목을 추가로 검토해야 합니다.
- 회원/관리자 비밀번호 해시화 및 비밀번호 정책 강화
- CSRF 토큰 적용
- 개인정보 암호화 및 접근 통제 강화
- 결제 PG 연동 검증 및 결제 위변조 방지
- 파일 업로드 확장자/MIME/용량 검증 강화
- 전체 SQL 구간 Prepared Statement 확대 적용
- 운영 환경에서 `display_errors` 비활성화 및 별도 로그 수집

## 개선된 포인트
- 관리자 인증을 쿠키 단독 인증에서 session 기반 인증으로 변경했습니다.
- 주문 저장에 트랜잭션을 적용해 주문 마스터/상세 저장 정합성을 개선했습니다.
- 입력값 검증과 SQL Injection 방어를 주요 위험 구간부터 보강했습니다.
- `main_top.php`의 큰 header 관련 inline CSS 블록을 `css/header.css`로 분리했습니다.
- `db/shop62.sql`은 프로젝트 실행용 스키마/샘플/기존 데이터로 유지합니다. 대용량 파일일 수 있으므로 GitHub 웹 화면에서 직접 열기보다는 로컬 DB import 기준으로 사용하는 것을 권장합니다.

## 실행 방법
1. 저장소를 클론하거나 프로젝트 파일을 로컬 웹 서버 루트에 배치합니다.
2. MySQL/MariaDB에서 DB를 생성합니다. (예: `shop62`)
3. `db/shop62.sql` 파일을 phpMyAdmin 또는 CLI로 로컬 DB에 import합니다. 파일이 클 수 있으므로 GitHub 웹에서 직접 열어 확인하기보다 로컬 환경에서 import하는 방식을 권장합니다.
4. `common.php`의 DB 접속 정보를 현재 환경에 맞게 수정합니다.
   - 예: `mysqli_connect("localhost", "root", "", "shop62")`
5. Apache+PHP 환경에서 프로젝트를 실행합니다.
6. 브라우저에서 `index.html` 또는 `main.php`로 접속합니다.
7. 관리자 페이지는 `admin/login.php`로 접속합니다.

환경별 경로/계정 정보가 다를 수 있으므로, 로컬 환경에 맞게 DB 계정/웹 루트는 수정해서 사용하면 됩니다.

## 트러블슈팅 또는 학습 포인트
- DB 접속 정보가 맞지 않으면 `common.php` 단계에서 연결 에러가 발생할 수 있습니다.
- 장바구니가 쿠키(JSON) 기반이므로 쿠키 차단/만료 시 장바구니 데이터가 유지되지 않을 수 있습니다.
- 주문 저장은 장바구니 쿠키를 기준으로 시작하지만, 최종 저장 시 상품 가격은 DB에서 재조회하고 트랜잭션으로 저장합니다.
- 상품 이미지 파일명과 DB의 `image1~3` 값이 불일치하면 이미지가 정상 출력되지 않습니다.
- include 구조(`main_top.php`, `common.php`)를 사용하므로 파일 경로가 바뀌면 include 경로 점검이 필요합니다.

## 향후 개선점
- 사용자 로그인/권한 검증 로직 추가 정리
- CSRF 토큰 및 서버 측 검증 확대
- 남아 있는 SQL 문자열 결합 구간을 Prepared Statement 방식으로 확장 적용
- 주문 상태 변경 이력(누가/언제 변경했는지) 기록 기능 추가
- 상품 검색/필터 UI 및 조건 확장
- 관리자 화면에 주문/매출 통계 요약 추가
- 쿠키 기반 장바구니를 회원 기준 DB 장바구니 구조로 확장 검토
