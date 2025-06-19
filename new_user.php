<?php
require_once './config/connectionDB.php';
if(isset($_POST["Registra"])){
        $user_name = $_POST["user_name"] ?? "";
        $user_pass = $_POST["user_pass"] ?? "";

        $stmt = $pdo->prepare("INSERT INTO users(username, password) VALUES (?, ?)");
        $stmt->execute([$user_name,$user_pass]);
        // 登録完了後ログイン画面に遷移
        header("Location:https://aso2301199.pinoko.jp/ToDo/login.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>    
</head>
<body>
    <h1>ユーザー登録</h1>
    <form action="./new_user.php" method="POST">
        <p>ユーザー名：<input type="text" name="user_name"></p>
        <p>パスワード：<input type="password" name="user_pass"></p>
    <button type="submit" name="Registra">登録</button>
    </form> 
    <a href="./login.php">ログインはこちら</a>
</body>
</html>