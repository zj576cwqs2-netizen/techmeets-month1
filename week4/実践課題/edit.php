<?php
require_once 'db.php';
$id = $_GET['id'];
$conn = getDBConnection();
$stmt = $conn->prepare("SELECT * FROM posts WHERE id =?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']) ?? '';
    $content = trim($_POST['content']) ?? '';
    $author = trim($_POST['author']) ?? '';
    if (empty($title) || empty($content) || empty($author)) {
        $error = '全ての項目を入力して下さい';
} else {
    $conn = getDBConnection();
    $stmt =  $stmt = $conn->prepare("INSERT INTO posts (title, content, author) VALUES (?, ?, ?)");
    $stmt->bind_param("sssi", $title, $content, $author, $id)

try {
    if ($stmt->execute()) {
    $stmt->execute();
    $stmt->close();
    header(Location: posts_index.php'):
    exir;
}
} catch (mysqli_sql_exception $e) {
    $error = '投稿に失敗しました: ' . $e->getMessage();
    $stmt->close();
    $stmt->close();
}
}
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>記事投稿</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>記事投稿</h1>
    <a href="posts_index.php">← 一覧に戻る</a>
 
    <?php if ($error): ?>
    <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
 
    <form method="POST">
        <label>タイトル:
            <input type="text" name="title"
                value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
        </label><br>
        <label>投稿者:
            <input type="text" name="author"
                value="<?php echo htmlspecialchars($_POST['author'] ?? ''); ?>">
        </label><br>
        <label>本文:
            <textarea name="content" rows="10"><?php echo htmlspecialchars($_POST['content'] ?? ''); ?></textarea>
        </label><br>
        <button type="submit">投稿する</button>
    </form>
</body>
</html>


}

    )
    }