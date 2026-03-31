<?php
$newfilename = "new.txt";

if ($_FILES["filename"]["error"] === 0) {
    $fname = $_FILES["filename"]["name"];
    $fsize = $_FILES["filename"]["size"];

    if (!is_dir("product")) {
        mkdir("product", 0777, true);
    }

    if (file_exists("product/$newfilename")) {
        exit("동일한 파일이 있음");
    }

    if (!move_uploaded_file($_FILES["filename"]["tmp_name"], "product/$newfilename")) {
        exit("업로드 실패: move_uploaded_file 실패");
    }

    echo("파일이름 : $newfilename<br>파일크기 : $fsize");
} else {
    echo("업로드 실패 - 에러코드: " . $_FILES["filename"]["error"]);
}
?>