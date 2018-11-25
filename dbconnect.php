<?php
//データベース接続作業
//PDO (PHP Data Object)
// オブジェクト指向というものを使っている
//XAMPP環境ではユーザー名は「root」、パスワードは空になる
$dsn = 'mysql:dbname=LearnSNS_review;host=localhost';
$user = 'root';
$password = '';
$dbh = new PDO($dsn,$user,$password);
//SQL文にエラーがあった際、画面にエラーを出力する設定
$dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
$dbh->query('SET NAME utf8')

//sql文実行の基本構文
//$sql = 'ここにSQL文を書く';
//$stmt = $dbh->prepare($sql);
//$stmt->execute();

?>