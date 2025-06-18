<?php
// データベース情報
$host = 'mysql323.phy.lolipop.lan';
$dbname = 'LAA1553913-notion';
$username = 'LAA1553913';
$password = 'passnotion';

try{
    // PDO接続
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // エラーハンドリングの設定
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // エラーメッセージを表示
    echo '<script>alert("データベースへの接続に失敗しました：\r\n'. $e->getMessage() .'");</script>';
    // 処理の停止
    exit;
}
?>