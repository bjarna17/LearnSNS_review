<?php
    session_start();

    //SESSION変数の破棄
    //ブラウザに情報が残るため
    $_SESSION = [];

    //サーバー内の$_SESSION変数のクリア
    session_destroy();

    //signin.phpへの移動
    header('Location: signin.php');
    exit();

?>