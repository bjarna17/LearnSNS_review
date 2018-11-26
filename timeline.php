<?php
    session_start();
    require('dbconnect.php');

    $sql = 'SELECT * FROM `users` WHERE `id` = ?';
    $data = array($_SESSION['id']);
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $signin_user = $stmt->fetch(PDO::FETCH_ASSOC);

// var_dump($signin_user);

    $errors = [];
    if(!empty($_POST)){
        $feed = $_POST['feed'];
        if($feed != ''){
          //投稿処理
          $sql = 'INSERT INTO `feeds` SET `feed`=?, `user_id`=?, `created`= NOW()';
          $data = array($feed,$signin_user['id']);
          $stmt = $dbh->prepare($sql);
          $stmt->execute($data);
          header('Location: timeline.php');
          exit();
        }else{
            $errors['feed'] = 'blank';
        }
    }

    //投稿表示機能->POSTに関係なく表示
    //テーブル結合：複数テーブルから一気にデータを取得すること
    //外部キーと主キーを結合条件として複数テーブルから一気にデータを取得
    //内部結合:２つのテーブルから条件が成立するレコードのみが取り出す
    //LEFT OUTER JOIN: 左側のテーブルを軸にして外部結合を行う方法
    //RIGHT OUTER JOIN:右側のテーブルを軸にして外部結合を行う方法
    //DESC 大きい数字から小さい数字に並べる
    //ASC 小さい数字から大きい数字に並べる
    $sql = 'SELECT `f`.*, `u`.`name`, `u`.`img_name` FROM `feeds` AS `f` LEFT JOIN `users` AS `u` ON `f`.`user_id` = `u`.`id` ORDER BY `created` DESC LIMIT 5';
    //テーブル名.カラム名
    //FROM テーブル１ JOIN テーブル２ ON 条件
    //feedsテーブルの全てとusersテーブルの一部(名前と写真)を読み出す
    //ASを使ってテーブルをリネームできる
    //ORDER BY 順番の指定
    //LIMIT句:取得するデータの行数を指定
    $data = array();
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    //投稿データを全て格納する配列
    $feeds = array();
    //繰り返す回数が決まっていないときはwhile文を使用
    while (true) {
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        //fetchはデータがないときにfalseを返す性質がある
        if ($record == false) {
            break;
        }
        $feeds[] = $record;//[]は配列の末尾にデータを追加するという意味
        //if文を下に書くとfalseも配列の中に入ってしまう
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
<body style="margin-top: 60px; background: #E4E6EB;">
  <div class="navbar navbar-default navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse1" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">Learn SNS</a>
      </div>
      <div class="collapse navbar-collapse" id="navbar-collapse1">
        <ul class="nav navbar-nav">
          <li class="active"><a href="#">タイムライン</a></li>
          <li><a href="#">ユーザー一覧</a></li>
        </ul>
        <form method="GET" action="" class="navbar-form navbar-left" role="search">
          <div class="form-group">
            <input type="text" name="search_word" class="form-control" placeholder="投稿を検索">
          </div>
          <button type="submit" class="btn btn-default">検索</button>
        </form>
        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><img src="user_profile_img/<?php echo $signin_user['img_name']; ?>" width="18" class="img-circle"><?php echo $signin_user['name']; ?><span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="#">マイページ</a></li>
              <li><a href="signout.php">サインアウト</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="row">
      <div class="col-xs-3">
        <ul class="nav nav-pills nav-stacked">
          <li class="active"><a href="timeline.php?feed_select=news">新着順</a></li>
          <li><a href="timeline.php?feed_select=likes">いいね！済み</a></li>
          <!-- <li><a href="timeline.php?feed_select=follows">フォロー</a></li> -->
        </ul>
      </div>
      <div class="col-xs-9">
        <div class="feed_form thumbnail">
          <form method="POST" action="">
            <div class="form-group">
              <textarea name="feed" class="form-control" rows="3" placeholder="Happy Hacking!" style="font-size: 24px;"></textarea><br>
              <?php if (isset($errors['feed']) && $errors['feed']=='blank') { ?>
                <p class='alert alert-danger'>投稿データを入力して下さい</p>
              <?php } ?>
            </div>
            <input type="submit" value="投稿する" class="btn btn-primary">
          </form>
        </div>
          <?php foreach($feeds as $feed) { ?>
          <div class="thumbnail">
            <div class="row">
              <div class="col-xs-1">
                <img src="user_profile_img/<?php echo $feed['img_name'] ?>" width="40">
              </div>
              <div class="col-xs-11">
                <?php echo $feed['name']; ?><br>
                <a href="#" style="color: #7F7F7F;"><?php echo $feed['created'] ?></a>
              </div>
            </div>
            <div class="row feed_content">
              <div class="col-xs-12" >
                <span style="font-size: 24px;"><?php echo $feed['feed'] ?></span>
              </div>
            </div>
            <div class="row feed_sub">
              <div class="col-xs-12">
                <form method="POST" action="" style="display: inline;">
                  <input type="hidden" name="feed_id" >
                    <input type="hidden" name="like" value="like">
                    <span hidden><?php $feed['id']; ?></span>
                    <button tclass="btn btn-default btn-xs js-like"><i class="fa fa-thumbs-up" aria-hidden="true"></i><span>いいね！</span></button>
                </form>
                <span >いいね数 : </span>
                <span class="comment_count">コメント数 : 9</span>
                <?php if ($feed['user_id'] == $_SESSION['id']) :?>
                  <a href="edit.php?feed_id=<?php echo $feed['id'] ?>" class="btn btn-success btn-xs">編集</a>
                  <a onclick="return confirm('本当に消すの？');" href="delete.php?feed_id=<?php echo $feed['id'] ?>" class="btn btn-danger btn-xs">削除</a>
                <?php endif; ?>
                  <!-- onclickイベント:HTMLドキュメント内の要素をクリックした際に起こるイベント処理。JavaScript -->
                  <!-- onclickによってクリック後に発動してほしい関数を指定する事が出来る。 -->
                  <!-- confirmメソッド:ウェブページに確認ダイアログを表示させる事が出来る。引数に設定した文字列をダイアログに表示する -->
              </div>
            </div>
          </div>
        <?php } ?>
        <div aria-label="Page navigation">
          <ul class="pager">
            <li class="previous disabled"><a href="#"><span aria-hidden="true">&larr;</span> Newer</a></li>
            <li class="next"><a href="#">Older <span aria-hidden="true">&rarr;</span></a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <script src="assets/js/jquery-3.1.1.js"></script>
  <script src="assets/js/jquery-migrate-1.4.1.js"></script>
  <script src="assets/js/bootstrap.js"></script>
</body>
</html>