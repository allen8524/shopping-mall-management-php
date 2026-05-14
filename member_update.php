<?
    include "common.php";

	$cookie_id = $_COOKIE["cookie_id"];
    $uid = $_REQUEST["uid"];
    $pwd = $_REQUEST["pwd"];
    $pwd1 = $_REQUEST["pwd1"];
    $name = $_REQUEST["name"];
    $tel1 = $_REQUEST["tel1"];
    $tel2 = $_REQUEST["tel2"];
    $tel3 = $_REQUEST["tel3"];
    $zip = $_REQUEST["zip"];
    $juso = $_REQUEST["juso"];
    $email = $_REQUEST["email"];
    $birthday1 = $_REQUEST["birthday1"];
    $birthday2 = $_REQUEST["birthday2"];
    $birthday3 = $_REQUEST["birthday3"];


    $tel = sprintf("%-3s%-4s%-4s", $tel1, $tel2, $tel3);

    $birthday = sprintf("%04d-%02d-%02d", $birthday1, $birthday2, $birthday3);
    
    if($pwd == $pwd1 && $pwd){
        $sql="update member set pwd='$pwd', name='$name', tel='$tel', zip='$zip', juso='$juso', email='$email', birthday='$birthday' where id = $cookie_id";
    }

    else{
    $sql="update member set name='$name', tel='$tel', zip='$zip', juso='$juso', email='$email', birthday='$birthday' where id = $cookie_id";
    }

    $result=mysqli_query($db, $sql);
    if (!$result) exit("에러: $sql");

    echo("<script>location.href='main.php'</script>");
