<?php
require_once 'db.php';
 
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: posts_index.php');
    exit;
}
 
$conn = getDBConnection();
$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();
$stmt->close();
$conn->close();
 
if (!$post) {
    header('Location: posts_index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($post['title']); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <a href="posts_index.php">← 一覧に戻る</a>
 
    <h1><?php echo htmlspecialchars($post['title']); ?></h1>
 
    <p>
        投稿者：<?php echo htmlspecialchars($post['author']); ?>&nbsp;
        投稿日時：<?php echo htmlspecialchars($post['created_at']); ?>
        <?php if ($post['updated_at'] !== $post['created_at']): ?>
        （更新：<?php echo htmlspecialchars($post['updated_at']); ?>）
        <?php endif; ?>
    </p>
 
    <div class="post-content">
        <?php echo nl2br(htmlspecialchars($post['content'])); ?>
    </div>
 
    <div class="post-actions">
        <a href="posts_edit.php?id=<?php echo (int)$post['id']; ?>">編集</a>
        <a href="posts_delete.php?id=<?php echo (int)$post['id']; ?>"
           onclick="return confirm('削除しますか？')">削除</a>
    </div>
</body>
</html>