<?php
require_once 'db.php';
$id = $_GET['id'];
$conn = getDBConnection();
$stmt = $conn->prepare("SELECT * FROM posts ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
$posts  = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>投稿一覧</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>投稿一覧</h1>
    <a href="create.php">新規投稿</a>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>タイトル</th>
                <th>本文</th>
                <th>作成日時</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $post): ?>
            <tr>
                <td><?php echo $post['id']; ?></td>
                <td><?php echo htmlspecialchars($post['title']); ?></td>
                <td><?php echo htmlspecialchars($post['content']); ?></td>
                <td><?php echo $post['created_at']; ?></td>
                <td>
                    <a href="edit.php?id=<?php echo $post['id']; ?>">編集</a>
                    <a href="delete.php?id=<?php echo $post['id']; ?>" onclick="return confirm('本当に削除しますか？')">削除</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>