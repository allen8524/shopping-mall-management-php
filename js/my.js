function admin_menu()
{
	var s_menu;

	s_menu = "<nav class='navbar navbar-expand-lg navbar-dark bg-dark'>" + "\n"
		+ "  <div class='container-fluid'>" + "\n"
		+ "    <a class='navbar-brand text-white' href='../admin/index.html'>관리자</a>" + "\n"
		+ "    <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarNav' aria-controls='navbarNav' aria-expanded='false' aria-label='Toggle navigation'>" + "\n"
		+ "      <span class='navbar-toggler-icon'></span>" + "\n"
		+ "    </button>" + "\n"
		+ "    <div class='collapse navbar-collapse' id='navbarNav'>" + "\n"
		+ "      <ul class='navbar-nav me-auto' style='font-size:15px'>" + "\n"
		+ "        <li class='nav-item'><a class='nav-link' href='member.php'>회원관리</a></li>" + "\n"
		+ "        <li class='nav-item'><a class='nav-link' href='product.php'>제품관리</a></li>" + "\n"
		+ "        <li class='nav-item'><a class='nav-link' href='jumun.php'>주문관리</a></li>" + "\n"
		+ "        <li class='nav-item'><a class='nav-link' href='opt.php'>옵션관리</a></li>" + "\n"
		+ "        <li class='nav-item'><a class='nav-link' href='faq.php'>FAQ</a></li>" + "\n"
		+ "      </ul>" + "\n"
		+ "      <a class='btn btn-sm btn-outline-secondary btn-dark' href='../index.html'>샵으로</a>" + "\n"
		+ "      <a class='btn btn-sm btn-outline-secondary btn-dark ms-2' href='admin_logout.php'>로그아웃</a>" + "\n"
		+ "    </div>" + "\n"
		+ "  </div>" + "\n"
		+ "</nav>" + "\n"
		+ "<br><br><br>" + "\n";

	return s_menu;
}


function clearErrors() {
  // 기존 에러 메시지 & shake 클래스 제거
  document.querySelectorAll('.error-msg').forEach(el => el.remove());
  [form1.adminid, form1.adminpw].forEach(el => el.classList.remove('shake'));
}

function check_id() {
  clearErrors();

  if (!form1.adminid.value.trim()) {
    // 에러 메시지 생성
    const msg = document.createElement('div');
    msg.className = 'error-msg';
    msg.textContent = '아이디를 입력해주세요';
    // 입력칸 부모에 붙이기
    form1.adminid.parentNode.appendChild(msg);
    form1.adminid.classList.add('shake');
    form1.adminid.focus();
    return false;
  }

  if (!form1.adminpw.value.trim()) {
    const msg = document.createElement('div');
    msg.className = 'error-msg';
    msg.textContent = '암호를 입력해주세요';
    form1.adminpw.parentNode.appendChild(msg);
    form1.adminpw.classList.add('shake');
    form1.adminpw.focus();
    return false;
  }

  return true;
}

