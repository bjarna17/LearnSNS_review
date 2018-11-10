<?php
    //「エラーだった場合に何エラーかを保存するための$errors配列を定義」
    $errors = array();
    //POST送信されていたら、その中身を変数定義する
    if (!empty($_POST)) {
      $name = $_POST['input_name'];
      $email = $_POST['input_email'];
      $password = $_POST['input_password'];

      //ユーザー名の空チェック
      if ($name == '') {
        $errors['name'] = 'blank';
      }
      //メールアドレスの空チェック
      if ($email == '') {
        $errors['email'] = 'blank';
      }
      //パスワードの空チェック
      if ($password = '') {
        $errors['password'] = 'blank';
      }
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>LearnSNS</title>
  <!-- linkタグ：リンクする外部リソースを指定する -->
  <link rel="stylesheet" type="text/css" href="href=../assets/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="../assets/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>
<body style="margin-top: 60px">
  <div class="container">
    <div class="row">
      <!-- thumbnail:お試し縮小表示画像のこと。 -->
      <!-- col:表の縦列の属性やスタイルを指定する -->
      <!-- xs:画面幅Extra small -->
      <div class="col-xs-8 col-xs-offest-2 thumbnail">
        <h2 class="text-center content_header">アカウント作成</h2>
        <form method="POST" action="signup.php" enctype="multipart/form-data">
          <div class="form-group">
            <!-- label:フォーム部品と項目名（ラベル）を関連付ける -->
            <label for="name">ユーザー名</label>
            <input type="text" name="input_name" class="form-control" id="name" placeholder="山田太郎">
            <!--「HTMLのユーザー名入力フォーム下にもし$errors配列のnameキーが存在し、blankという値が入っていた場合はエラーメッセージユーザー名を入力してくださいを出力」 -->
            <!-- isset()は引数に指定した変数が定義されているかどうか調べる関数 -->
            <?php if (isset($errors['name'])&&$errors['name'] == 'blank') { ?>
              <p class="text-danger">ユーザー名を入力してください</p>
            <?php } ?>
          </div>
          <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="email" name="input_email" class="form-control" id="email" placeholder="example@gmail.com">
            <?php if (isset($errors['email'])&&$errors['email'] == 'blank') { ?>
              <p class="text-danger">メールアドレスを入力してください</p>
            <?php } ?>
          </div>
          <div class="form-group">
            <label for="password">パスワード</label>
            <input type="password" name="input_password" class="form-control" id="password" placeholder="4~16文字のパスワード">
            <?php if (isset($errors['password'])&&$errors['password'] == 'blank') { ?>
              <p class="text-danger">パスワードを入力してください</p>
            <?php } ?>
          </div>
           <div class="form-group">
            <label for="img_name">パスワード</label>
            <input type="password" name="input_password" class="form-control" id="password" placeholder="4~16文字のパスワード">
          </div>
          <input type="submit" class="btn btn-default" value="確認">
          <a href="../signin.php" style="float: right; padding-top: 6px;" class="text-success">サインイン</a>
        </form>
      </div>
    </div>
  </div>
  <!-- script:文書にJavaScriptなどのスクリプトを組み込む -->
  <script src="../assets/js/jquery-3.1.1.js"></script>
  <script src="../assets/js/jquery-migrate-1.4.1.js"></script>
  <script src="../assets/js/bootstrap.js"></script>
</body>
</html>