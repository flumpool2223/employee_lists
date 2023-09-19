<?php

// Google APIクライアントライブラリの読み込み
require 'vendor/autoload.php';

// Google Sheets APIクライアントを初期化
$client = new Google_Client();
$client->setApplicationName('Application Name');
$client->setScopes([Google_Service_Sheets::SPREADSHEETS]);
$client->setAuthConfig('path/to/your/credentials.json'); // 認証情報のパス
$service = new Google_Service_Sheets($client);

// Google SheetsのスプレッドシートID
$spreadsheetId = 'spreadsheet-id';

// シート名
$sheetName = 'Sheet1';

// Google Sheetsからデータを取得
$response = $service->spreadsheets_values->get($spreadsheetId, $sheetName);
$values = $response->getValues();

if (empty($values)) {
    die('データが見つかりません');
}

// MySQLデータベースへの接続
$mysqli = new mysqli('localhost', 'db_user', 'db_password', 'db_name');

// データベース接続エラーチェック
if ($mysqli->connect_error) {
    die('DB接続エラー: ' . $mysqli->connect_error);
}

// データをデータベースにインポート
foreach ($values as $row) {
    $name = $row[0]; // 名前
    $email = $row[1]; // メールアドレス

    // データベースに挿入
    $query = "INSERT INTO employees (name, email) VALUES ('$name', '$email')";
    $result = $mysqli->query($query);

    if (!$result) {
        echo 'データがインサートされていません: ' . $mysqli->error;
    }
}

// データベース接続を閉じる
$mysqli->close();

echo 'データのインポートに成功しました!';
?>
