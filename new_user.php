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
        <p>パスワード：<input type="text" name="user_pass"></p>
    <button type="submit" name="Registra">登録</button>
    </form> 
    <a href="./login.php">ログインはこちら</a>
    <?php
    if(isset($_POST["Registra"])){
        
    }

    ?>
</body>
</html>