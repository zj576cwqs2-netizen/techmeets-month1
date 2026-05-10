<?php
require_once 'db.php';
session_start();
 
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: posts_index.php');
    exit;
}
 
// POST時に削除実行
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        http_response_code(403);
        exit('Invalid CSRF token');
    }
 
    $conn = getDBConnection();
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $conn->close();
 
    header('Location: posts_index.php');
    exit;
}
 
// GET時は確認画面を表示
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
    <title>削除確認</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>削除確認</h1>
 
    <p>
        <strong><?php echo htmlspecialchars($post['title']); ?></strong> を削除します。<br>
        この操作は取り消せません。
    </p>
 
    <form method="POST" action="posts_delete.php?id=<?php echo (int)$id; ?>">
        <input type="hidden" name="csrf_token"
               value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <button type="submit">削除する</button>
        <a href="posts_show.php?id=<?php echo (int)$id; ?>">キャンセル</a>
    </form>
</body>
</html>