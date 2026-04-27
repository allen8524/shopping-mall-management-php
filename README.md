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
- 주문 저장 구조: `jumun`(주문 마스터) + `jumuns`(주문 상세)로 분리 저장
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
- `order_ok.php`에서 주문번호를 생성한 뒤, 장바구니 항목을 `jumuns`에 반복 저장합니다.
- 이후 주문자/수령자/결제/총액 정보는 `jumun`에 1건으로 저장합니다.
- 이 구조로 주문 공통 정보와 상품별 상세 정보를 분리해 한 주문 다상품 데이터를 관리합니다.
- 주문 상세에는 옵션 ID(`opts_id1~3`)도 함께 저장해 옵션 조합을 추적할 수 있습니다.

### 3. 관리자 화면 기반 상품/주문 관리
- 상품: `admin/product.php`, `admin/product_new.php`, `admin/product_edit.php`에서 등록/조회/수정/삭제 및 이미지 업로드를 처리합니다.
- 주문: `admin/jumun.php`, `admin/jumun_info.php`, `admin/jumun_update.php`에서 주문 검색, 상세 확인, 상태 변경을 처리합니다.
- 옵션/FAQ: `admin/opt.php`, `admin/opts.php`, `admin/faq.php`에서 운영 데이터 관리가 가능합니다.

## 실행 방법
1. 저장소를 클론하거나 프로젝트 파일을 로컬 웹 서버 루트에 배치합니다.
2. MySQL/MariaDB에서 DB를 생성합니다. (예: `shop62`)
3. `db/shop62.sql` 파일을 phpMyAdmin 또는 CLI로 import합니다.
4. `common.php`의 DB 접속 정보를 현재 환경에 맞게 수정합니다.
   - 예: `mysqli_connect("localhost", "root", "", "shop62")`
5. Apache+PHP 환경에서 프로젝트를 실행합니다.
6. 브라우저에서 `index.html` 또는 `main.php`로 접속합니다.
7. 관리자 페이지는 `admin/login.php`로 접속합니다.

환경별 경로/계정 정보가 다를 수 있으므로, 로컬 환경에 맞게 DB 계정/웹 루트는 수정해서 사용하면 됩니다.

## 트러블슈팅 또는 학습 포인트
- DB 접속 정보가 맞지 않으면 `common.php` 단계에서 연결 에러가 발생할 수 있습니다.
- 장바구니가 쿠키(JSON) 기반이므로 쿠키 차단/만료 시 장바구니 데이터가 유지되지 않을 수 있습니다.
- 주문 저장은 장바구니 쿠키를 기준으로 처리되므로, 주문 단계 진입 전 쿠키 데이터 확인이 중요합니다.
- 상품 이미지 파일명과 DB의 `image1~3` 값이 불일치하면 이미지가 정상 출력되지 않습니다.
- include 구조(`main_top.php`, `common.php`)를 사용하므로 파일 경로가 바뀌면 include 경로 점검이 필요합니다.

## 향후 개선점
- 로그인/권한 처리 강화(관리자/사용자 권한 검증 로직 정리)
- 입력값 검증 및 서버 측 방어 로직 보강
- SQL 문자열 결합 구간을 Prepared Statement 방식으로 확장 적용
- 주문 상태 변경 이력(누가/언제 변경했는지) 기록 기능 추가
- 상품 검색/필터 UI 및 조건 확장
- 관리자 화면에 주문/매출 통계 요약 추가
- 쿠키 기반 장바구니를 회원 기준 DB 장바구니 구조로 확장 검토
