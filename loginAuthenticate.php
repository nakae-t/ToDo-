<?php
// データベース接続
require_once './config/connectionDB.php';
// SQL文の生成(本の新着Top50をbooksテーブルから取得)
$SQL = "SELECT id FROM users WHERE username = [username], password = [password]";
try {
    // プリペアードステートメントの作成
    $stmt = $pdo->prepare($SQL);
    // ステートメントの実行
    $stmt->execute();
    // 結果の取得(カラム名をキー値とした連想配列で取得)
    $newBooks50 = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo '接続に問題が発生しました:' . $e->getMessage();
    // トップページへリダイレクト
    header("Location: https://aso2301199.pinoko.jp/ReadMe2/src/u1_top.php");
    // DB接続を切断
    $pdo = null;
    // スクリプト実行を切断
    exit;
} finally {
    // DB接続を切断
    $pdo = null;
}
?>