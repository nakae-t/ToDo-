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

    <form action="" method="POST">

        <!-- todoの追加クラス -->
        <div class="Todo_add">
            <h2>タスク管理</h2>
            <input type="text" name="task" placeholder="タスク内容">
            <input type="date" name="day">
            <select name="priority" id="priority">
                <option value="" disabled selected>優先度を選択</option>
                <option value= 0>低</option>
                <option value= 1>中</option>
                <option value= 2>高</option>
            </select>
            <button type="submit" name="todo_add" value="todo_add">追加</button>
        </div>


        <!-- todoの検索クラス -->
        <div class="Todo_search">
            <h2>フィルタ/検索</h2>
            <input type="text" name="Search" placeholder="キーワード">
            
            <!-- 検索したいタスクの状態を選択 -->
            <select name="todo" id="todo">
                <option value="3" disabled selected>タスク状況を選択</option>
                <option value="todo_all">すべて</option>
                <option value="done">完了</option>
                <option value="todo">未完了</option>
                
            </select>

            <!-- 検索したいタスクの優先度を選択 -->
            <select name="Search_priority" id="Search_priority">
                <option value="3" disabled selected>優先度を選択</option>
                <option value="3">優先度すべて</option>
                <option value="0">低</option>
                <option value="1">中</option>
                <option value="2">高</option>
            </select>
            <button type="submit" name="todo_Search" value="todo_Search">検索</button>
        </div>
    </form>
   
    <!-- ユーザのリストを表示 -->
    <?php
    try {
        $stmt = $pdo->prepare("SELECT status, task, due_date, priority FROM todos WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $todos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 優先度の表示変更の処理
        function convertPriority($value) {
            switch ($value) {
                case 0: return '低';
                case 1: return '中';
                case 2: return '高';
                default: return '不明';
            }
        }


        if (count($todos) > 0) {
            foreach ($todos as $todo) {
                echo "<p>状態: " . htmlspecialchars($todo['status']) . "</p>";
                echo "<p>タスク: " . htmlspecialchars($todo['task']) . "</p>";
                echo "<p>期限: " . htmlspecialchars($todo['due_date']) . "</p>";
                echo "<p>優先度: " . convertPriority($todo['priority']) . "</p>";
                echo "<hr>";
            }
        } else {
            echo "<p>タスクが登録されていません。</p>";
        }
    } catch (PDOException $e) {
        echo "エラー: " . $e->getMessage();
    }
    ?>


        <?php

        // Todoリストの追加機能
        if(isset($_POST["todo_add"])) {
           $add = $pdo->prepare("
                INSERT INTO todos (user_id, task, due_date, priority) 
                VALUES (:user_id, :task, :day, :priority)
            ");

            $add->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $add->bindValue(':task', $_POST["task"], PDO::PARAM_STR);
            $add->bindValue(':day', $_POST["day"], PDO::PARAM_STR);
            $add->bindValue(':priority', $_POST["priority"], PDO::PARAM_INT);

            $add->execute();

            echo '<script>alert("タスクを追加しました");</script>';

        }else if(isset($_POST["todo_Search"])){

        }
        
    ?>
            
    
</body>
</html>