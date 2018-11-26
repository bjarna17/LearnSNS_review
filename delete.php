<?php
//once:読み込むのを一度のみにする
//ループ文があるとその回数分読み込むので、onceがある
    require_once('dbconnect.php');
    $feed_id = $_GET['feed_id'];
    $sql = "DELETE FROM `feeds` WHERE `feeds`.`id`=?";
    $data = array($feed_id);
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    header("Location: timeline.php");
    exit();
//require:処理を読み込み、利用したい場合に使う。別ファイルにある関数を呼び出したい場合など。外部のファイルで定義されたものを使いたい時。
//include:そのページを任意の場所に差し込みたいときに使う。コメントリストなどを表示させる処理を別ファイルにしている場合など


?>