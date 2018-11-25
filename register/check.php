<?php
    session_start();
    //別ファイルを読み込むrequire
    //引数にパスを書くことでrequire()を記述した行に読込ファイル内のプログラムをすべて置き換えます。
    require('../dbconnect.php');

    //check.phpへの直接リクエストに対して強制遷移させる
    //セッションに入力情報がそもそも保存されていないため$_SESSIONを使用している箇所すべてにエラーがでてしまうから。
    //isset();変数が存在するかどうかの確認
    if (!isset($_SESSION['register'])) {
      header('Location: signup.php');
      exit();
    }
    
    //$_SESSIONをわかりやすい名前で変数化する
    $name = $_SESSION['register']['name'];
    $email = $_SESSION['register']['email'];
    $password = $_SESSION['register']['password'];
    $img_name = $_SESSION['register']['img_name'];

    //登録ボタンが押された時に処理する
    if (!empty($_POST)) {
        //INSERT INTO `①テーブル名` SET `②カラム1`=③値1  , `カラム2`=値2 ...
        //値には実際insertしたいデータを入れる
        //NOW()→値は現在のタイムゾーン
        $sql = 'INSERT INTO `users` SET `name`=? ,`email`=?, `password`=?,`img_name`=?, `created`=NOW()';
        //password_hash()関数 ハッシュ化＝暗号化
        //データベースへの不正アクセス時に被害を最小限にするため
        //第一引数:ハッシュ化したい文字列 第二引数:にPASSWORD_DEFAULTを指定する
        $data = array($name,$email,password_hash($password,PASSWORD_DEFAULT),$img_name);
        //prepare：テンプレートとなるSQL文をprepareに準備する
        $stmt = $dbh->prepare($sql);
        //execute：executeに値をセットしてSQLを実行する
        $stmt->execute($data);
        //$_SESSIONに保存した内容は不要になったら削除するのが鉄則
        //unset:変数の存在をdestroy $_SESSION = array();と同等 データが切り捨てられる
        unset($_SESSION['register']);
        header('Location: thanks.php');
        exit();
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
<!-- margin:ボックス(border)外側の余白 -->
<body style="margin-top: 60px">
  <div class="container">
    <div class="row">
      <!-- thumbnail:お試し縮小表示画像のこと。 -->
      <!-- col:表の縦列の属性やスタイルを指定する -->
      <!-- xs:画面幅Extra small -->
      <!-- すべてのデザインはBootstrap3に定義されたclassを使用しています。 -->
      <div class="col-xs-8 col-xs-offest-2 thumbnail">
        <h2 class="text-center content_header">アカウント情報確認</h2>
        <div class="row">
          <div class="col-xs-4">
             <img src="../user_profile_img/<?php echo $_SESSION['register']['img_name']; ?>" class="img-responsive img-thumbnail">
          </div>
          <div class="col-xs-8">
            <!-- htmlspecialchars()関数を使ったXSS攻撃対策 -->
            <div>
              <span>ユーザー名</span>
              <p class="lead"><?php echo htmlspecialchars($name); ?></p>
            </div>
            <div>
              <span>メールアドレス</span>
              <p class="lead"><?php echo htmlspecialchars($email); ?></p>
            </div>
            <div>
              <!-- パスワードはセキュリティ上画面に出力しないのがWEBサービス開発での鉄則 -->
              <span>パスワード</span>
              <p class="lead">入力いただいたパスワード</p>
            </div>
            <form method="POST" action="">

              <!-- 戻る:データ送信の必要なし→aタグで作成する -->
              <!-- パラメータにaction=rewriteを指定 -->
              <!-- パラメータがあれば$_POSTに$_SESSIONの情報を代入という条件分岐ができる -->
              <a href="signup.php?action=rewrite" class="btn btn-default">戻る</a>
              <!-- hidden 隠しデータの生成 -->
              <!-- POST送信してその後の処理を記述したいけどデータを打つような欄は必要はない -->
              <!-- formタグにデータが入ってないのはまずいのでhiddenを使う -->
              <!-- データベースへ登録する際も$_SESSIONの情報から生成された変数たちを使用する。 -->
              <input type="hidden" name="action" value="submit">
              <input type="submit" class="btn btn-primary" value="ユーザー登録">
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>