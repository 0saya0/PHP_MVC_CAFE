<?php
// 接続設定
$dsn = 'mysql:host=localhost;dbname=casteria;charset=utf8';
$user = 'root';
$pass = 'root';

// DBのエラーチェック構文 (データベースに接続)
try {
    $dbh = new PDO($dsn, $user, $pass, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    // 接続成功
} catch (PDOException $e) {
    echo '接続失敗'.$e->getMessage();
    exit();
};
