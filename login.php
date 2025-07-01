<?php
// セッション宣言
session_start();

// セッション情報が保持されている場合
if(isset($_SESSION['USERID'])){

    // セッション情報を破棄
    session_destroy();

    // 再度このページにリダイレクト
    header("Location: https://aso2301199.pinoko.jp/ToDo/login.php");

    exit;
}

// ユーザー情報が見つからなかった場合
if (isset($_SESSION['errorMessage']) && !empty($_SESSION['errorMessage'])) {
    echo '<script>alert("' . htmlspecialchars($_SESSION['errorMessage'], ENT_QUOTES, 'UTF-8') . '");</script>';
    unset($_SESSION['errorMessage']); // 一度表示したら消すのが一般的
}
?>
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