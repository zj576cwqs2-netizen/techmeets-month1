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
    <title>記事一覧</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>記事一覧</h1>
    <a href="create.php">＋新規投稿</a>
    
    <?php if (empty($posts)): ?>
    <p>記事がありません</p>
    <?php else: ?>
    <table>
        <thead>
            　　<tr>タイトル<th>              
                <th>ID</th>
                <th>投稿者</th>
                <th>投稿日時</th>
                <th></th>
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
    <?php endif; ?>
</body>
</html>