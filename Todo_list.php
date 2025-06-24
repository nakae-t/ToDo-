<?php
require_once './config/connectionDB.php';
session_start();

// セッション情報の読み込み
$user_id = $_SESSION['USERID'];

if (!$user_id) {
    // セッション情報がない時の処理
    header("Location: https://aso2301199.pinoko.jp/ToDo/login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>ToDoリスト</h1>
    <!-- ログイン中のアカウント名を追加 -->
    <p><?=$user['username']?> さん： <a href="./login.php">ログアウト</a></p>

    <form action="./Todo_list.php" method="POST">

        <!-- todoの追加クラス -->
        <div class="Todo_add">
            <h2>タスク管理</h2>
            <input type="text" name="task" placeholder="タスク内容">
            <input type="date" name="day">
            <select name="priority" id="priority">
                <option value="" disabled selected>優先度を選択</option>
                <option value="priority_low">低</option>
                <option value="priority_medium">中</option>
                <option value="priority_high">高</option>
            </select>
            <button type="submit" name="todo_add">追加</button>
        </div>


        <!-- todoの検索クラス -->
        <div class="Todo_search">
            <h2>フィルタ/検索</h2>
            <input type="text" name="Search" placeholder="キーワード">
            
            <!-- DBから今までの登録日次を取得 -->
            <select name="Search_day" id="day">
                <option value="" disabled selected>登録日を選択</option>
                <option value="day_all">すべて</option>
                <!-- DBから登録日次を取得 -->
                <?php
                $stmt = $pdo->query("SELECT DISTINCT DATE(created_at) AS created_day FROM todos ORDER BY created_day DESC");
                $dates = $stmt->fetchAll(PDO::FETCH_COLUMN);
                foreach ($dates as $date) {
                    echo '<option value="' . htmlentities($date, ENT_QUOTES, 'UTF-8') . '">' . htmlentities($date, ENT_QUOTES, 'UTF-8') . '</option>';
                }
                ?>
            </select>

            <!-- DBから今までの優先順位を取得 -->
            <select name="Search_priority" id="priority">
                <option value="" disabled selected>優先度を選択</option>
                <option value="priority_all">優先度すべて</option>
                <option value="priority_low">低</option>
                <option value="priority_medium">中</option>
                <option value="priority_high">高</option>
            </select>
            <button type="submit" name="todo_add">検索</button>
        </div>

    </form>
    
</body>
</html>