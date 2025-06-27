<?php
require_once './config/connectionDB.php';
session_start();

// セッション情報の取得
$user_id = $_SESSION['USERID'];

if (!$user_id) {
    header("Location: https://aso2301199.pinoko.jp/ToDo/login.php");
    exit;
}

// ユーザー情報取得
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// 優先度表示
function convertPriority($value) {
    switch ($value) {
        case 0: return '低';
        case 1: return '中';
        case 2: return '高';
        default: return '不明';
    }
}

// タスク削除処理
if (isset($_POST["delete_id"])) {
    $delete = $pdo->prepare("DELETE FROM todos WHERE id = :id AND user_id = :user_id");
    $delete->bindValue(':id', $_POST["delete_id"], PDO::PARAM_INT);
    $delete->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $delete->execute();
    echo '<script>alert("タスクを削除しました");</script>';
}

// タスク追加処理
if (isset($_POST["todo_add"])) {
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
} else if (isset($_POST["todo_Search"])) {
    // 検索処理が後から追加される予定




    
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>ToDoリスト</title>
</head>
<body>
<h1>ToDoリスト</h1>
<!-- ログイン中のアカウント名を表示 -->
<p><?=htmlspecialchars($user['username'])?> さん： <a href="./login.php">ログアウト</a></p>

<form action="" method="POST">
    <!-- タスク追加クラス -->
    <div class="Todo_add">
        <h2>タスク管理</h2>
        <input type="text" name="task" placeholder="タスク内容">
        <input type="date" name="day">
        <select name="priority" id="priority">
            <option value="" disabled selected>優先度を選択</option>
            <option value="0">低</option>
            <option value="1">中</option>
            <option value="2">高</option>
        </select>
        <button type="submit" name="todo_add" value="todo_add">追加</button>
    </div>

    <!-- タスク検索クラス -->
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
<hr>

<!-- タスク一覧表示 -->
<?php
try {
    $stmt = $pdo->prepare("
        SELECT id, status, task, due_date, priority 
        FROM todos 
        WHERE user_id = :user_id
    ");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $todos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($todos) > 0) {
        foreach ($todos as $todo) {
            $task_id = htmlspecialchars($todo['id']);
            $status = htmlspecialchars($todo['status']);
            $checked = ($status === 'done') ? 'checked' : '';
            echo "<div>";
            echo "<b><label>状態：<input type='checkbox' disabled $checked></label></b><br />";
            echo "<b>タスク:</b> " . htmlspecialchars($todo['task']) . "<br />";
            echo "<b>期限:</b> " . htmlspecialchars($todo['due_date']) . "<br />";
            echo "<b>優先度:</b> " . convertPriority($todo['priority']) . "<br />";

            //編集（更新）
            echo "<b>操作：</b><a href='Todo_task_edit.php?task_id=$task_id'>編集</a> ";

            //削除
            echo "<form method='POST' style='display:inline;' onsubmit='return confirm(\"本当に削除しますか？\");'>";
            echo "<input type='hidden' name='delete_id' value='{$task_id}'>";
            echo "<button type='submit'>削除</button>";
            echo "</form>";

            echo "</div><hr />";
        }
    } else {
        echo "<p>タスクが登録されていません。</p>";
    }
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
}
?>
</body>
</html>
