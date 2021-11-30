<?php
// POSTデータ確認
// var_dump($_POST);
// exit;

$random_name = rand(0, 4);

switch($random_name){
    case 0:
    $no_name = '<i>晴れやかな名無しさん(匿名)</i>';
    break;
    case 1:
    $no_name = '<i>悲しげな名無しさん(匿名)</i>';
    break;
    case 2:
    $no_name = '<i>コードを書く名無しさん(匿名)</i>';
    break;
    case 3:
    $no_name = '<i>暇を持て余す名無しさん(匿名)</i>';
    break;
    case 4:
    $no_name = '<i>天下の名無しさん(匿名)</i>';
    break;
}

// $no_name = '<i>晴れやかな名無しさん</i>';

if (
    !isset($_POST['comment_name']) || $_POST['comment_name'] == '' //必須項目（todo と deadline）のデータが送信されていない
) {
    $_POST['comment_name']= $no_name;
    $comment_name = $_POST['comment_name'];
}elseif (
    !isset($_POST['comment']) || $_POST['comment'] == '' //必須項目（todo と deadline）が空で送信されている．
) {
    exit('ParamError'); //<Param> パラメータ
}else{
    $comment_name = $_POST['comment_name'];
}

//データ受け取り

$comment = $_POST['comment'];
$thread_id = $_POST['thread_id'];

// var_dump($comment_name);
// var_dump($comment);
// var_dump($thread_id);
// exit;
// exit($comment_name);

// DB接続
// 各種項目設定
$dbn = 'mysql:dbname=gsacf_l06_07;charset=utf8mb4;port=3306;host=localhost';
$user = 'root'; //ユーザ名
$pwd = ''; //パスワード

// DB接続 //↓共通のコード
try {
    $pdo = new PDO($dbn, $user, $pwd);
} catch (PDOException $e) {
    echo json_encode(["db error" => "{$e->getMessage()}"]);
    exit();
} //↑共通のコード

// echo 'OK';
// exit;
// exit('OK');

// SQL作成&実行
$sql = 'INSERT INTO comment_table (id, thread_id, comment_name, comment, created_at, updated_at) VALUES (NULL, :thread_id, :comment_name, :comment, now(), now())';

$stmt = $pdo->prepare($sql);

// バインド変数を設定//セキュリティ強化・・変なデータがあるか
$stmt->bindValue(':comment_name', $comment_name, PDO::PARAM_STR);
$stmt->bindValue(':comment', $comment, PDO::PARAM_STR);
$stmt->bindValue(':thread_id', $thread_id, PDO::PARAM_STR);

// SQL実行（実行に失敗すると `sql error ...` が出力される）
try {
    $status = $stmt->execute(); //ここでSQLにいく？？？
} catch (PDOException $e) { //失敗時
    echo json_encode(["sql error" => "{$e->getMessage()}"]);
    exit();
}
// exit($status);
//ここで止まっている！！！
// exit('OK');

$sql = 'UPDATE thread_table SET updated_at=now() WHERE id=:thread_id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':thread_id', $thread_id, PDO::PARAM_STR);

try {
    $status = $stmt->execute(); //ここでSQLにいく？？？
} catch (PDOException $e) { //失敗時
    echo json_encode(["sql error" => "{$e->getMessage()}"]);
    exit();
}


//SQL が正常に実行された場合は，データ入力画面に移動することとする
header('Location:thread_read.php?id=' . $thread_id);
exit();
