<?php
session_start();
include "../common.php";
// 이미 관리자 로그인 상태면 member.php로 리다이렉트
if (!empty($_SESSION["admin_id"]) && !empty($_SESSION["admin_login"])) {
    header("Location: member.php");
    exit;
}
// 로그인 실패 플래그
$login_error = isset($_GET['error']) && $_GET['error'] === '1';
?>

<!doctype html>
<html lang="kr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>4910</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/my.css" rel="stylesheet">
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/my.js"></script>

    <script>
      function clearErrors() {
        document.querySelectorAll('.error-msg').forEach(el => el.remove());
        ['adminid','adminpw'].forEach(name => {
          document.forms.form1[name].classList.remove('shake');
        });
      }

function check_id() {
  clearErrors();
  const f = document.forms.form1;

  const idInput = f.adminid;
  const pwInput = f.adminpw;

  if (!idInput.value.trim()) {
    const msg = document.createElement('div');
    msg.className = 'error-msg text-danger small mt-1';
    msg.textContent = '아이디를 입력해주세요!!!';
    idInput.parentElement.insertAdjacentElement('afterend', msg); // input 감싸는 div 바로 아래에 삽입
    idInput.classList.add('shake');
    idInput.focus();
    return false;
  }

  if (!pwInput.value.trim()) {
    const msg = document.createElement('div');
    msg.className = 'error-msg text-danger small mt-1';
    msg.textContent = '암호를 입력해주세요!!!';
    pwInput.parentElement.insertAdjacentElement('afterend', msg);
    pwInput.classList.add('shake');
    pwInput.focus();
    return false;
  }

  return true;
}

    </script>
</head>
<body onload="document.forms.form1.adminid.focus();">

<div class="container">
    <br><br><br><br><br><br>
    <div class="row m-1 justify-content-center <?php if($login_error) echo 'shake'; ?>">
        <div class="col-auto text-center">
            <?php if($login_error): ?>
              <div class="alert alert-danger">아이디 또는 비밀번호가 잘못되었습니다.</div>
            <?php endif; ?>
            <form name="form1" method="post" action="login_check.php" onsubmit="return check_id();">
                <table width="350" height="200" style="border:4px solid #eeeeee">
                    <tr>
                        <td align="center">
                            <table width="100%" height="200">
                                <tr height="60" class="bg-light">
								<td colspan="3" class="pt-2">
								  <h3 style="text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);">Admin Login</h3>
								</td>

                                </tr>
                                <tr height="18"><td colspan="3"></td></tr>
                                <tr height="50">
                                    <td width="90">아이디</td>
                                    <td width="170" align="left">
                                        <div class="d-inline-flex">
                                            <input type="text" name="adminid" size="20" class="form-control form-control-sm" tabindex="1" style="width:150px;">
                                        </div>
                                    </td>
                                </tr>
                                <tr height="50">
                                    <td>암 호</td>
                                    <td align="left">
                                        <div class="d-inline-flex">
                                            <input type="password" name="adminpw" size="20" class="form-control form-control-sm" tabindex="2" style="width:150px;">
                                        </div>
                                    </td>
                                </tr>
<td colspan="3" class="text-center" style="padding-top:15px;">
  <div class="btn-group" role="group" aria-label="로그인 그룹">
    <button
      type="submit"
      class="btn btn-sm btn-secondary"
      style="height:30px; width:70px;"
      tabindex="3">
      로그인
    </button>
    &nbsp;
    <a
      href="../index.html"
      class="btn btn-sm btn-outline-secondary"
      style="height:30px; width:70px;">
      ➽ 메인
    </a>
  </div>
</td>

                            </table>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>

</body>
</html>
