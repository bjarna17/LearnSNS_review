<?php
    require('dbconnect.php');
    //タイムラインでidを保持させ表示するため
    session_start();
    //バリデーション初期化
    $errors = [];

    if (!empty($_POST)) {
        $email = $_POST['input_email'];
        $password = $_POST['input_password'];

        if ($email != '' && $password != '') {
            //データベースとの照合処理
            $sql = 'SELECT * FROM `users` WHERE `email`=?';
            $data = [$email];
            //$stmtはobject型というデータ
            $stmt = $dbh->prepare($sql);
            $stmt->execute($data);
            //fetchという処理をする←解決策
            //object型 → array型に変換してくれる
            //1fetch 1recordというルール
            //このSQL文の結果、データが一致すれば$recordにはアカウント情報が連想配列形式で代入される。
            $record = $stmt->fetch(PDO::FETCH_ASSOC);

            //メールアドレスでの本人確認
            //逆に入力されたメールアドレスが一致しなかった場合はfalseが代入されるの
            //の場合は入力値にミスがあったことをエラーメッセージで表示する
            if ($record == false) {
                $errors['signin'] = 'failed';
            }
            //$record連想配列からパスワードカラムのデータを取得し、ハッシュ化されたパスワードと、入力されたパスワードをpassword_verify()を使って比較する。
            //password_verify 認証処理
            //第一引数には認証する文字列、第二引数では暗号化済みの文字列を指定
            if(password_verify($password,$record['password'])){
              //認証成功
              //サインインアカウントのidを$_SESSION['id']に保存する
              $_SESSION['id'] = $record['id'];
              //header()関数を使ってタイムライン画面へ遷移する
              header("Location: timeline.php");
              exit();
            }else{
              //認証失敗
              $errors['signin'] = 'failed';
            }
        }else{
          $errors['signin'] = 'blank';
        }
    }


?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>Learn SNS</title>
  <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="assets/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body style="margin-top: 60px;">
  <div class="container">
    <div class="row">
      <div class="col-xs-8 col-xs-offset-2 thumbnail">
        <h2 class="text-center content_header">サインイン</h2>
        <form method="POST" action="" enctype="multipart/form-data">
          <div claa="form-group">
            <label for="email">メールアドレス</label>
            <input type="email" name="input_email" class="form-control" id="email" placeholder="example@gmail.com">
            <?php if (isset($errors['signin']) && $errors['signin']=='blank') : ?>
              <p class="text-danger">メールアドレスとパスワードを正しく入力してください</p>
            <?php endif; ?>
            <?php if (isset($errors['signin']) && $errors['signin']=='failed') : ?>
              <p class="text-danger">サインインに失敗しました
              </p>
            <?php endif; ?>
          </div>
          <div claa="form-group">
            <label for="password">パスワード</label>
            <input type="password" name="input_password" class="form-control" id="password" placeholder="4~16文字のパスワード">
          </div>
          <input type="submit" class="btn btn-info" value="サインイン">
        </form>
      </div>
    </div>
  </div>
  <script src="assets/js/jquery-3.1.1.js"></script>
  <script src="assets/js/jquery-migrate-1.4.1.js"></script>
  <script src="assets/js/bootstrap.js"></script>
</body>
</html>