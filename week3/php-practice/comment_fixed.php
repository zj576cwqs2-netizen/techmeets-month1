<!DOCTYPE html>
<!-- HTML文書の種類を宣言する（HTML5） -->
<html lang="ja">
<!-- ページ全体の言語を日本語に設定 -->
<head>
  <!-- ブラウザに表示されない設定情報をここに書く -->
  <meta charset="UTF-8">
  <!-- 文字コードをUTF-8に設定（日本語が文字化けしないようにする） -->
  <title>コメント投稿</title>
  <!-- ブラウザのタブに表示されるページタイトル -->
</head>
<body>
<!-- ブラウザに表示される内容をここに書く -->

<h1>コメント投稿フォーム</h1>
<!-- ページの見出し -->

<!-- フォーム: method="POST" でデータを送信する（URLに表示されない） -->
<form method="POST">
  <label>名前:</label>
  <!-- 名前入力欄: name属性でPHPからデータを受け取る際のキーになる -->
  <input type="text" name="name"><br>

  <label>コメント:</label>
  <!-- 複数行入力できるテキストエリア: required は空のまま送信できないようにする -->
  <textarea name="comment" required></textarea><br>

  <!-- 送信ボタン: クリックするとフォームのデータがPOSTで送られる -->
  <button type="submit">投稿する</button>
</form>

<?php
// フォームがPOSTで送信されたときだけ以下の処理を実行する
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // フォームから送られてきた名前とコメントを変数に代入する
    $name    = $_POST["name"];
    $comment = $_POST["comment"];

    // 名前が空欄だったらエラーメッセージを表示する
    if ($name === "") {
        echo "名前を入力してください。";
    } else {
        // htmlspecialchars() でXSS対策（HTMLタグをそのまま表示させないようにする）
        $safeName    = htmlspecialchars($name,    ENT_QUOTES, 'UTF-8');
        $safeComment = htmlspecialchars($comment, ENT_QUOTES, 'UTF-8');

        // 安全な値を画面に出力する
        echo "<p>" . $safeName . "さんのコメント:</p>";
        echo "<p>" . $safeComment . "</p>";
    }
}
?>

</body>
</html>
