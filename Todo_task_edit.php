<?php
if (isset($_POST['save'])) {
    // データベース接続
    require_once './config/connectionDB.php';
    // SQL文の生成(本の新着Top50をbooksテーブルから取得)
    $SQL = "UPDATE todos SET task = :task, status = :status, due_date = :due_date, priority = :priority WHERE id = :id";
    try {
        // プリペアードステートメントの作成
        $stmt = $pdo->prepare($SQL);
        $stmt->bindValue(':task', $_POST['task'], PDO::PARAM_STR);
        $stmt->bindValue(':status', $_POST['status'], PDO::PARAM_STR);
        $stmt->bindValue(':due_date', $_POST['due_date']);
        $stmt->bindValue(':priority', $_POST['priority'], PDO::PARAM_INT);
        $stmt->bindValue(':id', $_POST['task_id'], PDO::PARAM_INT);
        // ステートメントの実行
        $stmt->execute();
    } catch (PDOException $e) {
        echo '接続に問題が発生しました:' . $e->getMessage();
        // 現在のページへリダイレクト
        header("Location: https://aso2301199.pinoko.jp/ToDo/Todo_task_edit.php");
        // DB接続を切断
        $pdo = null;
        // スクリプト実行を切断
        exit;
    } finally {
        // 一覧ページへリダイレクト
        header("Location: https://aso2301199.pinoko.jp/ToDo/Todo_list.php");
        // DB接続を切断
        $pdo = null;
    }
} else {
    // 安全な出力（XSS対策）
    $TASK_ID = htmlspecialchars($_GET['task_id'], ENT_QUOTES, 'UTF-8');

    // データベース接続
    require_once './config/connectionDB.php';
    // SQL文の生成(本の新着Top50をbooksテーブルから取得)
    $SQL = "SELECT * FROM todos WHERE id = :task_id";
    try {
        // プリペアードステートメントの作成
        $stmt = $pdo->prepare($SQL);
        $stmt->bindValue(':task_id', $TASK_ID, PDO::PARAM_INT);
        // ステートメントの実行
        $stmt->execute();
        // 結果の取得
        $task = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo '接続に問題が発生しました:' . $e->getMessage();
        // ログインページへリダイレクト
        header("Location: https://aso2301199.pinoko.jp/ToDo/Todo_task_edit.php");
        // DB接続を切断
        $pdo = null;
        // スクリプト実行を切断
        exit;
    } finally {
        // DB接続を切断
        $pdo = null;
    }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>タスク編集</title>
</head>

<body>
    <h1>タスク編集</h1>
    <form action="" method="post">
        <input type="hidden" name="task_id" value="<?= $TASK_ID ?>">
        <p>
            <label for="task">内容：</label>
            <input type="text" name="task" value="<?= $task['task'] ?>" required>
        </p>
        <p>
            <label for="due_date">期限：</label>
            <input type="date" name="due_date" value="<?= $task['due_date'] ?>" required>
        </p>
        <p>
            <label for="priority">優先度：</label>
            <select name="priority" id="priority">
                <option value="0" <?= ($task["priority"] == '0') ? 'selected' : ''; ?>>低</option>
                <option value="1" <?= ($task["priority"] == '1') ? 'selected' : ''; ?>>中</option>
                <option value="2" <?= ($task["priority"] == '2') ? 'selected' : ''; ?>>高</option>
            </select>
        </p>
        <p>
            <label for="status">状態：</label>
            <select name="status" id="status">
                <option value="todo" <?= ($task["status"] == 'todo') ? 'selected' : ''; ?>>完了</option>
                <option value="done" <?= ($task["status"] == 'done') ? 'selected' : ''; ?>>未完了</option>
            </select>
        </p>
        <button type="submit" name="save" value="save">保存</button>
    </form>
    <a href="./Todo_list.php">キャンセル</a>
</body>

</html>