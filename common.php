<?php
$db = mysqli_connect("localhost", "root", "", "shop62");

if (!$db) {
    exit("DB연결에러: " . mysqli_connect_error());
}

mysqli_set_charset($db, "utf8mb4");

$admin_id = "admin";
$admin_pw = "1234";
$page_line = 5;
$page_block = 5;


	
		// 상품 상태 선택 옵션
	$a_status = array("상품상태", "판매중", "판매중지", "품절");
	$n_status = count($a_status);

	// 아이콘 선택 옵션
	$a_icon = array("아이콘 선택", "New", "Hit", "Sale");
	$n_icon = count($a_icon);

	// 메뉴 선택 옵션
	$a_menu = array("분류선택", "반소매", "후드", "니트", "자켓", "맨투맨", "가디건", "집업", "부츠", "슬리퍼", "반바지", "스니커즈", "데님", "롱팬츠", "가방");
	$n_menu = count($a_menu);

	// 검색 항목 선택 옵션
	$a_text1 = array("", "제품이름", "제품번호");  // index 0은 사용 안 함
	$n_text1 = count($a_text1);

	// 카드종류 선택 옵션 ← 추가
// common.php — 카드종류 선택 옵션
$a_card_kind = array(
    1  => "KB국민카드",
    2  => "신한카드",
    3  => "하나카드",
    4  => "BC카드",
    5  => "삼성카드",
    6  => "현대카드",
    7  => "롯데카드",
    8  => "NH농협카드",
    9  => "IBK기업은행카드",
    10 => "한국씨티은행카드",
    11 => "SC제일은행카드",
    12 => "수협은행카드",
    13 => "신협카드",
    14 => "iM뱅크카드",
    15 => "BNK부산은행카드",
    16 => "BNK경남은행카드",
    17 => "광주은행카드",
    18 => "전북은행카드",
    19 => "제주은행카드",
    20 => "케이뱅크카드",
    21 => "카카오뱅크카드",
    22 => "토스뱅크카드",
    23 => "우체국체크카드",
    24 => "새마을금고체크카드",
    25 => "저축은행중앙회체크카드",
    26 => "산은캐피탈카드",
    27 => "KDB캐피탈카드",
    28 => "현대백화점카드",
    29 => "갤러리아백화점카드"
);

$n_card_kind = count($a_card_kind);


// common.php — 무통장 입금 정보 (bank_kind 값에 따라)
$bank_info = array(
    1  => '국민은행 111-00000-0000',
    2  => '신한은행 222-00000-0000',
    3  => '우리은행 333-00000-0000',
    4  => '하나은행 444-00000-0000',
    5  => 'IBK기업은행 555-00000-0000',
    6  => '한국씨티은행 666-00000-0000',
    7  => 'SC제일은행 777-00000-0000',
    8  => '수협은행 888-00000-0000',
    9  => '신협 999-00000-0000',
    10 => '케이뱅크 1644-1234',
    11 => '카카오뱅크 1599-4900',
    12 => '토스뱅크 1599-4901',
    13 => 'BNK부산은행 051-1234-5678',
    14 => 'BNK경남은행 055-1234-5678',
    15 => '광주은행 062-1234-5678',
    16 => '전북은행 063-1234-5678',
    17 => '제주은행 064-1234-5678',
    18 => '우체국 1588-1300',
    19 => '새마을금고 1588-9900',
    20 => '저축은행중앙회 1588-5500',
    21 => '산은캐피탈 054-1212-1212',
    22 => 'KDB캐피탈 1588-0000',
    23 => '현대백화점카드 1522-4188',
    24 => '갤러리아백화점카드 1522-1778'
);

	
		
	function mypagination($query, $args, &$count, &$pagebar)
	{
		global $db, $page_line, $page_block;			// 서버DB 정보

		$page = isset($_REQUEST["page"]) ? max(1, (int)$_REQUEST["page"]) : 1; // page초기화
		
		$url=basename($_SERVER['PHP_SELF']) . "?" . $args;    // 문서이름?전송할 변수들
		
		// 전체 레코드개수
		$sql = strtolower( $query );
		$sql ="select count(*) " . substr($sql, strpos($sql,"from"));
		$result=mysqli_query($db, $sql);
		if (!$result) exit("데이터 조회 중 오류가 발생했습니다.");
		$row=mysqli_fetch_array($result);
		$count = $row[0];

		// 페이지내 자료
		$first = ($page-1) * $page_line;
		
		$sql = str_replace(";", "", $query);
		$sql .= " limit $first, $page_line";
		$result=mysqli_query($db, $sql);
		if (!$result) exit("데이터 조회 중 오류가 발생했습니다.");

		// pagebar html
		$pages = ceil($count/$page_line);				// 페이지수
		$blocks = ceil($pages/$page_block);			// 블록수 
		$block = ceil($page/$page_block);			// 블록 위치
		$page_s = $page_block * ($block-1);		// 블록의 시작페이지
		$page_e = $page_block * $block;				// 블록의 마지막페이지
		if ($blocks <= $block) $page_e = $pages;

		$pagebar ="<nav>
			<ul class='pagination pagination-sm justify-content-center py-1'>";

		if ($block > 1)				// 이전 블록으로
			$pagebar .="<li class='page-item mypage123'>
					<a class='page-link' href='$url&page=$page_s'>◀</a>
				</li>";

		for($i=$page_s+1; $i<=$page_e; $i++)
		{
			if ($page == $i)			// 선택한 page
				$pagebar .="<li class='page-item active'>
						<span class='page-link mycolor1 mypage123'>$i</span>
					</li>";
			else
				$pagebar .="<li class='page-item'>
						<a class='page-link mypage1234' href='$url&page=$i'>$i</a>
					</li>";
		}

		if ($block < $blocks)		// 다음 블록으로
			$pagebar .="<li class='page-item'>
					<a class='page-link' href='$url&page=" . $page_e+1 . "'>▶</a>
				</li>";
				
		$pagebar .="</ul>
			</nav>";
			
		return $result;
	}
?>
	