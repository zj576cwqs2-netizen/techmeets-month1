<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>お問い合わせフォーム</title>
<style>
  body { font-family: sans-serif; padding: 24px; max-width: 560px; }
  h1 { margin-bottom: 24px; }
  label { display: block; margin-bottom: 4px; font-weight: bold; }
  input, textarea {
    width: 100%; padding: 8px; margin-bottom: 4px;
    border: 1px solid #ddd; border-radius: 4px;
    font-size: 14px; box-sizing: border-box;
  }
  .error { color: #c00; font-size: 13px; margin-bottom: 12px; }
  .btn { padding: 10px 24px; font-size: 14px; cursor: pointer; border: none; border-radius: 4px; }
  .btn-primary { background: #3b82f6; color: #fff; }
  .btn-primary:hover { background: #2563eb; }
  .btn-secondary { background: #6b7280; color: #fff; }
  .btn-secondary:hover { background: #4b5563; }

  /* 確認画面 */
  .confirm-box { background: #f0f4ff; padding: 24px; border-radius: 8px; margin-bottom: 20px; }
  .confirm-box table { width: 100%; border-collapse: collapse; }
  .confirm-box th, .confirm-box td { padding: 10px 12px; border-bottom: 1px solid #d1d5db; text-align: left; vertical-align: top; }
  .confirm-box th { width: 30%; color: #374151; }
  .btn-row { display: flex; gap: 12px; }

  /* 完了画面 */
  .complete-box { background: #ecfdf5; border: 1px solid #6ee7b7; padding: 24px; border-radius: 8px; }
  .complete-box h2 { color: #065f46; margin-top: 0; }
</style>
</head>
<body>

<?php
$step      = 'form';   // form | confirm | complete
$errors    = [];
$mail_sent = null; // メール送信結果（true=成功 / false=失敗 / null=未送信）
$name = $email = $subject = $message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $posted_step = $_POST['step'] ?? 'form';
    $name    = trim($_POST['name']    ?? '');
    $email   = trim($_POST['email']   ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($posted_step === 'form') {
        // ── ステップ1→2: 入力検証 ──
        if ($name    === '') $errors['name']    = '名前を入力してください。';
        if ($email   === '') $errors['email']   = 'メールアドレスを入力してください。';
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
                             $errors['email']   = 'メールアドレスの形式が正しくありません。';
        if ($subject === '') $errors['subject'] = '件名を入力してください。';
        if ($message === '') $errors['message'] = 'メッセージを入力してください。';

        if (empty($errors)) $step = 'confirm';

    } elseif ($posted_step === 'confirm') {
        // ── ステップ2→3: メール送信して完了 ──

        // mb_send_mail() で日本語のメール件名・本文を正しく扱うための設定
        mb_language('Japanese');       // 件名を ISO-2022-JP で自動エンコードする
        mb_internal_encoding('UTF-8'); // 入力文字列が UTF-8 であることを宣言

        // implode() で各行を "\n" で結合してメール本文を組み立てる
        $mail_body = implode("\n", [
            "{$name} 様",
            "",
            "以下の内容でお問い合わせを受け付けました。",
            "",
            "■ 名前          : {$name}",
            "■ メールアドレス: {$email}",
            "■ 件名          : {$subject}",
            "■ メッセージ",
            $message,
            "",
            "このメールはお問い合わせフォームから自動送信されています。",
        ]);

        // メールヘッダーを設定する（複数ある場合は "\r\n" で区切る）
        // From: 送信元アドレス（実際のサーバーに合わせて変更）
        // Content-Type: 本文の文字コードを UTF-8 と明示
        $mail_headers = implode("\r\n", [
            'From: no-reply@example.com',
            'Content-Type: text/plain; charset=UTF-8',
        ]);

        // mb_send_mail(宛先, 件名, 本文, ヘッダー) でメールを送信する
        // 戻り値は送信成功なら true、失敗なら false
        $mail_sent = mb_send_mail(
            $email,                          // 宛先: フォームに入力されたメールアドレス
            '【お問い合わせ受付】' . $subject, // 件名
            $mail_body,                      // 本文
            $mail_headers                    // ヘッダー
        );

        $step = 'complete';
    }
}

// 表示用エスケープ
$e = [
    'name'    => htmlspecialchars($name,    ENT_QUOTES, 'UTF-8'),
    'email'   => htmlspecialchars($email,   ENT_QUOTES, 'UTF-8'),
    'subject' => htmlspecialchars($subject, ENT_QUOTES, 'UTF-8'),
    'message' => htmlspecialchars($message, ENT_QUOTES, 'UTF-8'),
];

// ────────────── ステップ1: 入力フォーム ──────────────
if ($step === 'form'):
?>
<h1>お問い合わせ</h1>
<form method="POST" novalidate>
  <input type="hidden" name="step" value="form">

  <label for="name">名前 <span style="color:#c00">*</span></label>
  <input type="text" id="name" name="name" value="<?= $e['name'] ?>" placeholder="山田 太郎">
  <?php if (isset($errors['name'])): ?>
    <p class="error"><?= htmlspecialchars($errors['name'], ENT_QUOTES, 'UTF-8') ?></p>
  <?php endif; ?>

  <label for="email">メールアドレス <span style="color:#c00">*</span></label>
  <input type="email" id="email" name="email" value="<?= $e['email'] ?>" placeholder="example@mail.com">
  <?php if (isset($errors['email'])): ?>
    <p class="error"><?= htmlspecialchars($errors['email'], ENT_QUOTES, 'UTF-8') ?></p>
  <?php endif; ?>

  <label for="subject">件名 <span style="color:#c00">*</span></label>
  <input type="text" id="subject" name="subject" value="<?= $e['subject'] ?>" placeholder="ご質問について">
  <?php if (isset($errors['subject'])): ?>
    <p class="error"><?= htmlspecialchars($errors['subject'], ENT_QUOTES, 'UTF-8') ?></p>
  <?php endif; ?>

  <label for="message">メッセージ <span style="color:#c00">*</span></label>
  <textarea id="message" name="message" rows="6" placeholder="お問い合わせ内容をご記入ください"><?= $e['message'] ?></textarea>
  <?php if (isset($errors['message'])): ?>
    <p class="error"><?= htmlspecialchars($errors['message'], ENT_QUOTES, 'UTF-8') ?></p>
  <?php endif; ?>

  <button type="submit" class="btn btn-primary">確認画面へ →</button>
</form>

<?php
// ────────────── ステップ2: 確認画面 ──────────────
elseif ($step === 'confirm'):
?>
<h1>入力内容の確認</h1>
<div class="confirm-box">
  <table>
    <tr><th>名前</th><td><?= $e['name'] ?></td></tr>
    <tr><th>メールアドレス</th><td><?= $e['email'] ?></td></tr>
    <tr><th>件名</th><td><?= $e['subject'] ?></td></tr>
    <tr><th>メッセージ</th><td style="white-space:pre-wrap"><?= $e['message'] ?></td></tr>
  </table>
</div>

<div class="btn-row">
  <!-- 戻るボタン: 入力値をhiddenで持ち越す -->
  <form method="POST">
    <input type="hidden" name="step"    value="form">
    <input type="hidden" name="name"    value="<?= $e['name'] ?>">
    <input type="hidden" name="email"   value="<?= $e['email'] ?>">
    <input type="hidden" name="subject" value="<?= $e['subject'] ?>">
    <input type="hidden" name="message" value="<?= $e['message'] ?>">
    <button type="submit" class="btn btn-secondary">← 入力に戻る</button>
  </form>

  <!-- 送信ボタン -->
  <form method="POST">
    <input type="hidden" name="step"    value="confirm">
    <input type="hidden" name="name"    value="<?= $e['name'] ?>">
    <input type="hidden" name="email"   value="<?= $e['email'] ?>">
    <input type="hidden" name="subject" value="<?= $e['subject'] ?>">
    <input type="hidden" name="message" value="<?= $e['message'] ?>">
    <button type="submit" class="btn btn-primary">送信する</button>
  </form>
</div>

<?php
// ────────────── ステップ3: 完了画面 ──────────────
elseif ($step === 'complete'):
?>
<div class="complete-box">
  <h2>送信が完了しました</h2>
  <?php
  // $mail_sent が true なら送信成功メッセージ、false なら失敗の警告を表示する
  if ($mail_sent): ?>
    <p>以下の内容でお問い合わせを受け付けました。<br>
       確認メールを <strong><?= $e['email'] ?></strong> に送信しました。</p>
  <?php else: ?>
    <p>以下の内容でお問い合わせを受け付けました。<br>
       <span style="color:#92400e;">※ 確認メールの送信に失敗しました。メールサーバーの設定をご確認ください。</span></p>
  <?php endif; ?>
  <table style="width:100%; border-collapse:collapse; margin-top:12px;">
    <tr><th style="padding:8px 12px; border-bottom:1px solid #6ee7b7; width:30%; text-align:left;">名前</th><td style="padding:8px 12px; border-bottom:1px solid #6ee7b7;"><?= $e['name'] ?></td></tr>
    <tr><th style="padding:8px 12px; border-bottom:1px solid #6ee7b7; text-align:left;">メールアドレス</th><td style="padding:8px 12px; border-bottom:1px solid #6ee7b7;"><?= $e['email'] ?></td></tr>
    <tr><th style="padding:8px 12px; border-bottom:1px solid #6ee7b7; text-align:left;">件名</th><td style="padding:8px 12px; border-bottom:1px solid #6ee7b7;"><?= $e['subject'] ?></td></tr>
    <tr><th style="padding:8px 12px; text-align:left;">メッセージ</th><td style="padding:8px 12px; white-space:pre-wrap;"><?= $e['message'] ?></td></tr>
  </table>
</div>
<p><a href="contact.php">新しいお問い合わせ</a></p>

<?php endif; ?>

</body>
</html>
