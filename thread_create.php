<?php
// POSTデータ確認
// var_dump($_POST);
// exit;
$nanashi = '匿名';
if(
    !isset($_POST['thread_name']) || $_POST['thread_name'] == ''//必須項目（todo と deadline）のデータが送信されていない
){
 $_POST['thread_name']= $nanashi;
$thread_name = $_POST['thread_name'];
}elseif (
    !isset($_POST['thread']) || $_POST['thread'] == '' //必須項目（todo と deadline）が空で送信されている．
) {
    exit('ParamError'); //<Param> パラメータ
}else{
    $thread_name = $_POST['thread_name'];
}

//データ受け取り

$thread = $_POST['thread'];

// var_dump($thread_name);
// exit;

// DB接続
// 各種項目設定
$dbn = 'mysql:dbname=gsacf_l06_07;charset=utf8mb4;port=3306;host=localhost';
$user = 'root';//ユーザ名
$pwd = '';//パスワード

// 【参考】エラーメッセージを出力する意味
// どこで失敗したのかをわかるようにする！
// PHP ではエラーを見つけづらい．．．
// データを扱うので，異常なデータなどが作成されるとまずい．
// どこでエラーが出ているのかわからないと詰む．
// エラーにも種類がある！
// どこでうまくいっていないのかを把握できるようにエラーの処理を記述！

// DB接続 //↓共通のコード
try {
    $pdo = new PDO($dbn, $user, $pwd);
} catch (PDOException $e) {
    echo json_encode(["db error" => "{$e->getMessage()}"]);
    exit();
} //↑共通のコード

// echo 'OK';
// exit;

// SQL作成&実行
$sql = 'INSERT INTO thread_table (id, thread_name, thread, created_at, updated_at) VALUES (NULL, :thread_name, :thread, now(), now())';

$stmt = $pdo->prepare($sql);

// バインド変数を設定//セキュリティ強化・・変なデータがあるか
$stmt->bindValue(':thread_name', $thread_name, PDO::PARAM_STR);
$stmt->bindValue(':thread', $thread, PDO::PARAM_STR);

// SQL実行（実行に失敗すると `sql error ...` が出力される）
try {
    $status = $stmt->execute();//ここでSQLにいく？？？
} catch (PDOException $e) {//失敗時
    echo json_encode(["sql error" => "{$e->getMessage()}"]);
    exit();
}
// exit('OK');

//SQL が正常に実行された場合は，データ入力画面に移動することとする
header('Location:thread_read.php');
exit();