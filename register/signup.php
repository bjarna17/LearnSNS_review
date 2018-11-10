<?php
    //session:各サーバーに用意された一時的にデータを保存することができる機能(簡易データベースのような存在) 中身の構造は連想配列
    //sessiomの使用条件：ファイルの最初にsession_start()を宣言する必要がある
    session_start();
    //「エラーだった場合に何エラーかを保存するための$errors配列を定義」
    $errors = array();
    //はじめ$_POSTされてない時のために空の値を定義
    $name = '';
    $email = '';
    $password = '';
    $file_name = '';

    //POST送信されていたら、その中身を変数定義する
    if (!empty($_POST)) {
      //はじめ$_POSTされてない時のために空の値を定義
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
      //strlen:文字列の長さを取得する(日本語はmb_strlen)
      $count = strlen($password);
      if ($password = '') {
        $errors['password'] = 'blank';
      }elseif ($count < 4 || 16 < $count) {
        $errors['password'] = 'length';
      }
    //$_FILES['キー']['name'] 画像名を取得
    //$_FILES['キー']['tmp_name'] 送信された画像データそのものを取得
    //画像名を取得し、空だった場合は$errorsに値を代入する
      $file_name = $_FILES['input_img_name']['name'];
      if (!empty($file_name)) {
        //拡張子のチェックの処理
        //substr()関数は指定した文字列を取得する
        //strlower()関数は大文字を小文字に変換する
        $file_type = substr($file_name,-3);//画像名の後ろから３文字を取得
        $file_type = strtolower($file_type);//大文字が含まれていた場合全て小文字化
        if ($file_type != 'jpg' && $file_type != 'png' && $file_type != 'gif') {
          $errors['img_name'] = 'type';
        }
      }else{
        $errors['img_name'] = 'blank';
      }


    //$errorsが空だった場合はバリデーション成功
    //バリデーション成功時の処理
    //⑴プロフィール画像のアップロード
    //⑵セッションへ送信データを保存する
    //DBは基本的に文字や数字データを管理します。そのため、ユーザーが送信した画像データを指定したフォルダへアップロードし、DBにはアップロードされた画像の名前を文字データで保存しする必要がある。保存した画像を取得して表示したい場合は、画像名をDBから取得しimgタグのパスへセットする形でフォルダへアップロードされた画像を表示する。
        if (empty($errors)) {
            //成功時の処理を記述する
            //一意のファイル名を生成 date()関数を使用
            date_default_timezone_set('Asia/Manila'); //フィリピン時間に設定
            $date_str = date('YmdHis');//現在の時刻を年月日時間分秒のフォーマットで表示
            $submit_file_name = $date_str . $file_name;
            //move_uploaded_file()関数:画像をアップロード
            //move_uploaded_file(テンポラリーファイル,アップロード先パス)
            //テンポラリーファイル：$_FILES['キー']['tmp_name']で取得できる
            //../user_profile_img'.$submit_file_nameと文字連結することで保存先を指定する
            move_uploaded_file($_FILES['input_img_name']['tmp_name'],'../user_profile_img' . $submit_file_name);
            //簡易データベースsessionに値を保存する
            //sessionはサーバー内全てのファイルで共通しているため、キーをもうけ多次元配列化し他のシステムとの重複を防いだ上で保存する必要がある。
            //sessionというタンスは１つしかないが、タンスを増やせばたくさんの内容を保存できる
            $_SESSION['register']['name'] = $_POST['input_name'];
            $_SESSION['register']['email'] = $_POST['input_email'];
            $_SESSION['register']['password'] = $_POST['input_password'];
            $_SESSION['register']['img_name'] = $submit_file_name;

            //header()関数:リダイレクト処理
            //“Location:”とURLを指定で、指定したURLのブラウザを表示できる。
            //exit()スクリプトの終了
            //header()関数の使用だとPOST送信はリセットされる
            // header('Location: check.php');
            // exit();
         }
   }


    ?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>LearnSNS</title>
  <!-- linkタグ：リンクする外部リソースを指定する -->
  <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.css">
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
            <?php if (isset($errors['password'])&&$errors['password'] == 'length') { ?>
              <p class="text-danger">パスワードは4~16文字で入力してください</p>
            <?php } ?>
          </div>
          <!-- ファイルは$_POSTで受け取れない -->
          <!-- $_FILES:ファイルアップロード専用 ⑴POST送信されている ⑵multipart/form-data-->
           <div class="form-group">
            <label for="img_name">プロフィール画像</label>
            <input type="file" name="input_img_name" id="img_name" accept="image/*">
            <!-- accerpt属性:accept="image/*: 画像ファイルのみ選択可とする -->
          </div>
          <?php if (isset($errors['img_name'])&&$errors['img_name'] == 'blank') { ?>
              <p class="text-danger">画像を選択してください</p>
            <?php } ?>
            <?php if (isset($errors['img_name'])&&$errors['img_name'] == 'type') { ?>
              <p class="text-danger">拡張子が「jpg」「png」「gif」の画像を洗濯してください</p>
            <?php } ?>
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