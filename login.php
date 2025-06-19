<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
</head>
<body>
    <h1>ログイン</h1>
    <form action="loginAuthenticate.php" method="POST">
        <p>
            <label for="userName">ユーザー名：</label>
            <input type="text" name="userName" required>
        </p>
        <p>
            <label for="userPassword">パスワード：</label>
            <input type="text" name="userPassword" required>
        </p>
        <button type="submit">ログイン</button>
    </form>
    <p>
        <a href="new_user.php">新規登録</a>
    </p>
</body>
</html>