<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>コメント投稿</title>
</head>
<body>

<h1>コメント投稿フォーム</h1>

<form method="POST">
  <label>名前:</label>
  <input type="text" name="name"><br>
  <label>コメント:</label>
  <textarea name="comment" required></textarea><br>
  <button type="submit">投稿する</button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = $_POST["name"];
    $comment = $_POST["comment"];

    if ($name === "") {
        echo "名前を入力してください。";
    } else {
        $safeName    = htmlspecialchars($name,    ENT_QUOTES, 'UTF-8');
        $safeComment = htmlspecialchars($comment, ENT_QUOTES, 'UTF-8');
        echo "<p>" . $safeName . "さんのコメント:</p>";
        echo "<p>" . $safeComment . "</p>";
    }
}
?>

</body>
</html>