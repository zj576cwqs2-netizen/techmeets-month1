$result_test = $conn->query("SELECT * FROM users");
if (!$result_test) {
    die("クエリエラー: " . $conn->error);
}
echo "取得件数: " . $result_test->num_rows . "<br>";
