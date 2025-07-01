<?php
// セッション宣言
session_start();
// データベース接続
require_once './config/connectionDB.php';
// SQL文の生成(本の新着Top50をbooksテーブルから取得)
$SQL = "SELECT id FROM users WHERE username = :username AND password = :password";
try {
    // プリペアードステートメントの作成
    $stmt = $pdo->prepare($SQL);
    $stmt->bindValue(':username',$_POST['userName']);
    $stmt->bindValue(':password',$_POST['userPassword']);
    // ステートメントの実行
    $stmt->execute();
    // 結果の取得
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo '接続に問題が発生しました:' . $e->getMessage();
    // ログインページへリダイレクト
    header("Location: https://aso2301199.pinoko.jp/ToDo/login.php");
    // DB接続を切断
    $pdo = null;
    // スクリプト実行を切断
    exit;
} finally {
    // DB接続を切断
    $pdo = null;
}

// ユーザー情報が見つかったか
if($result){
    // セッションにユーザーIDを保存
    $_SESSION['USERID'] = $result['id'];
    //メインページへリダイレクト
    header("Location: https://aso2301199.pinoko.jp/ToDo/Todo_list.php");
} else {
    $_SESSION['errorMessage'] = 'ユーザー情報が見つかりませんでした';
    // ログインページへリダイレクト
    header("Location: https://aso2301199.pinoko.jp/ToDo/login.php");
}
?>