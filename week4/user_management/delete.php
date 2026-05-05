<?php
require_once 'db.php';

$id = $_GET['id'] ?? '';

if ($id === '') {
    header('Location: index.php');
    exit;
}

// POSTで確認済みの場合は削除実行
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = getDBConnection();
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    header('Location: index.php');
    exit;
}

// GETの場合はユーザー情報を取得して確認画面を表示
$conn = getDBConnection();
$stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($username, $email);
$found = $stmt->fetch();
$stmt->close();
$conn->close();

$user = $found ? ['username' => $username, 'email' => $email] : null;

if (!$user) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>削除確認</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>ユーザー削除確認</h1>
    <p>以下のユーザーを削除しますか？</p>
    <table>
        <tr>
            <th>ユーザー名</th>
            <td><?php echo htmlspecialchars($user['username']); ?></td>
        </tr>
        <tr>
            <th>メール</th>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
        </tr>
    </table>
    <form method="post" action="delete.php?id=<?php echo (int)$id; ?>">
        <button type="submit">削除する</button>
        <a href="index.php">キャンセル</a>
    </form>
</body>
</html>
