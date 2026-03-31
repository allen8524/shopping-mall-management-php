<?php
include "main_top.php";
?>
<!doctype html>
<html lang="kr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link  href="css/bootstrap.min.css" rel="stylesheet">
    <link  href="css/my.css" rel="stylesheet">
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container">
<!-- 시작 : 아이디/암호 찾기 페이지 -->

<script>
    function SearchID() {
        var uname = form2.name.value.trim();
        var email = form2.email.value.trim();
        if (uname === "") {
            alert("이름을 입력해 주십시오.");
            form2.name.focus();
            return;
        }
        if (email === "") {
            alert("E-Mail을 입력해 주십시오.");
            form2.email.focus();
            return;
        }
        var url = "member_searchid.php?name=" + encodeURIComponent(uname)
                + "&email=" + encodeURIComponent(email);
        window.open(url, "searchid", "width=300,height=210,top=100,left=100,scrollbars=no,status=no");
        form2.reset();
    }

    function SearchPW() {
        var userid = form3.userid.value.trim();
        var uname  = form3.name.value.trim();
        if (userid === "") {
            alert("아이디를 입력해 주십시오.");
            form3.userid.focus();
            return;
        }
        if (uname === "") {
            alert("이름을 입력해 주십시오.");
            form3.name.focus();
            return;
        }
        var url = "member_searchpw.php?userid=" + encodeURIComponent(userid)
                + "&name="   + encodeURIComponent(uname);
        window.open(url, "searchpw", "width=300,height=210,top=100,left=100,scrollbars=no,status=no");
        form3.reset();
    }
</script>

<div class="row m-1 mb-0">
    <div class="col" align="center">
        <h4 class="m-3 mt-5">아이디/암호 찾기</h4>
        <hr size="4px" class="m-0">
        <br><br><br>
        <table width="340" height="300" style="border:4px solid #eeeeee" bgcolor="#fcfcfc">
            <tr>
                <td align="center">
                    <!-- form2: 아이디 찾기 -->
                    <form name="form2" method="post" action="">
                        <table>
                            <tr height="30">
                                <td colspan="2" class="ps-5" width="70%">
                                    <font size="3" color="#B90319"><b>아이디 찾기</b></font>
                                </td>
                                <td width="25%"></td>
                            </tr>
                            <tr height="45">
                                <td width="20%">이름</td>
                                <td width="50%">
                                    <input type="text" name="name" size="20" class="form-control form-control-sm" tabindex="1">
                                </td>
                                <td rowspan="2" width="30%">
                                    <a href="javascript:SearchID()" class="btn btn-sm btn-dark text-white mx-2 pt-4"
                                       style="height:75px;width:75px;" tabindex="3">확인</a>
                                </td>
                            </tr>
                            <tr height="45">
                                <td>E-Mail</td>
                                <td>
                                    <input type="text" name="email" size="20" class="form-control form-control-sm" tabindex="2">
                                </td>
                            </tr>
                        </table>
                    </form>
                    <!-- form2 끝 -->
                </td>
            </tr>
            <tr><td><hr class="m-0"></td></tr>
            <tr>
                <td align="center">
                    <!-- form3: 암호 찾기 -->
                    <form name="form3" method="post" action="">
                        <table>
                            <tr height="30">
                                <td colspan="2" class="ps-5" width="70%">
                                    <font size="3" color="#B90319"><b>암호 찾기</b></font>
                                </td>
                                <td width="25%"></td>
                            </tr>
                            <tr height="45">
                                <td width="20%">ID</td>
                                <td width="50%">
                                    <input type="text" name="userid" size="20" class="form-control form-control-sm" tabindex="4">
                                </td>
                                <td rowspan="2" width="30%">
                                    <a href="javascript:SearchPW()" class="btn btn-sm btn-secondary text-white mx-2 pt-4"
                                       style="height:75px;width:75px;" tabindex="6">확인</a>
                                </td>
                            </tr>
                            <tr height="45">
                                <td>이름</td>
                                <td>
                                    <input type="text" name="name" size="20" class="form-control form-control-sm" tabindex="5">
                                </td>
                            </tr>
                        </table>
                    </form>
                    <!-- form3 끝 -->
                </td>
            </tr>
        </table>
    </div>
</div>

<br><br><br><br><br><br><br><br>

<!-- 끝 : 아이디/암호 찾기 페이지 -->
<?php
include "main_bottom.php";
?>
</div>
</body>
</html>
