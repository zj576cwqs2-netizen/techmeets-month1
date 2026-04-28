<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>お問い合わせフォーム</title>
<style>
  body { font-family: sans-serif; padding: 24px; max-width: 560px; }
  h1 { margin-bottom: 24px; }
  label { display: block; margin-bottom: 4px; font-weight: bold; }
  input, textarea { width: 100%; padding: 8px; margin-bottom: 4px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box; }
  .error { color: #c00; font-size: 13px; margin-bottom: 12px; }
  button { padding: 10px 24px; font-size: 14px; cursor: pointer; background: #3b82f6; color: #fff; border: none; border-radius: 4px; }
  button:hover { background: #2563eb; }

  /* 確認画面 */
  .confirm { background: #f0f4ff; padding: 24px; border-radius: 8px; }
  .confirm table { width: 100%; border-collapse: collapse; }
  .confirm th, .confirm td { padding: 10px 12px; border-bottom: 1px solid #d1d5db; text-align: left; vertical-align: top; }
  .confirm th { width: 30%; color: #374151; }
  .back-btn { margin-top: 16px; padding: 10px 24px; font-size: 14px; cursor: pointer; background: #6b7280; color: #fff; border: none; border-radius: 4px; }
  .back-btn:hover { background: #4b5563; }
</style>
</head>
<body>

<?php
$errors = [];
$fields = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];
$show_confirm = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 生の入力値を取得（trim のみ）
    $name    = trim($_POST['name']    ?? '');
    $email   = trim($_POST['email']   ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // バリデーション
    if ($name === '')    $errors['name']    = '名前を入力してください。';
    if ($email === '')   $errors['email']   = 'メールアドレスを入力してください。';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'メールアドレスの形式が正しくありません。';
    if ($subject === '') $errors['subject'] = '件名を入力してください。';
    if ($message === '') $errors['message'] = 'メッセージを入力してください。';

    // エラーなし → 確認画面へ
    if (empty($errors)) {
        $show_confirm = true;
        // XSS 対策: 表示直前に htmlspecialchars でエスケープ
        $fields = [
            'name'    => htmlspecialchars($name,    ENT_QUOTES, 'UTF-8'),
            'email'   => htmlspecialchars($email,   ENT_QUOTES, 'UTF-8'),
            'subject' => htmlspecialchars($subject, ENT_QUOTES, 'UTF-8'),
            'message' => htmlspecialchars($message, ENT_QUOTES, 'UTF-8'),
        ];
    } else {
        // フォームに入力値を戻す（エスケープして value に埋め込む）
        $fields = [
            'name'    => htmlspecialchars($name,    ENT_QUOTES, 'UTF-8'),
            'email'   => htmlspecialchars($email,   ENT_QUOTES, 'UTF-8'),
            'subject' => htmlspecialchars($subject, ENT_QUOTES, 'UTF-8'),
            'message' => htmlspecialchars($message, ENT_QUOTES, 'UTF-8'),
        ];
    }
}

if ($show_confirm):
?>

<h1>入力内容の確認</h1>
<div class="confirm">
  <table>
    <tr><th>名前</th><td><?= $fields['name'] ?></td></tr>
    <tr><th>メールアドレス</th><td><?= $fields['email'] ?></td></tr>
    <tr><th>件名</th><td><?= $fields['subject'] ?></td></tr>
    <tr><th>メッセージ</th><td style="white-space: pre-wrap"><?= $fields['message'] ?></td></tr>
  </table>
</div>
<form method="GET">
  <button type="submit" class="back-btn">← フォームに戻る</button>
</form>

<?php else: ?>

<h1>お問い合わせフォーム</h1>
<form method="POST" novalidate>

  <label for="name">名前 <span style="color:#c00">*</span></label>
  <input type="text" id="name" name="name" value="<?= $fields['name'] ?>" placeholder="山田 太郎">
  <?php if (isset($errors['name'])): ?>
    <p class="error"><?= htmlspecialchars($errors['name'], ENT_QUOTES, 'UTF-8') ?></p>
  <?php endif; ?>

  <label for="email">メールアドレス <span style="color:#c00">*</span></label>
  <input type="email" id="email" name="email" value="<?= $fields['email'] ?>" placeholder="example@mail.com">
  <?php if (isset($errors['email'])): ?>
    <p class="error"><?= htmlspecialchars($errors['email'], ENT_QUOTES, 'UTF-8') ?></p>
  <?php endif; ?>

  <label for="subject">件名 <span style="color:#c00">*</span></label>
  <input type="text" id="subject" name="subject" value="<?= $fields['subject'] ?>" placeholder="ご質問について">
  <?php if (isset($errors['subject'])): ?>
    <p class="error"><?= htmlspecialchars($errors['subject'], ENT_QUOTES, 'UTF-8') ?></p>
  <?php endif; ?>

  <label for="message">メッセージ <span style="color:#c00">*</span></label>
  <textarea id="message" name="message" rows="6" placeholder="お問い合わせ内容をご記入ください"><?= $fields['message'] ?></textarea>
  <?php if (isset($errors['message'])): ?>
    <p class="error"><?= htmlspecialchars($errors['message'], ENT_QUOTES, 'UTF-8') ?></p>
  <?php endif; ?>

  <button type="submit">送信する</button>
</form>

<?php endif; ?>

</body>
</html>
