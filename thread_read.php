<?php

// 処理の流れ
// 1,表示ファイル（todo_read.php）へアクセス時，DB 接続する．
// 2,データ参照用 SQL 作成 → 実行．
// 3,取得したデータを HTML に埋め込んで画面を表示．
// ※必要に応じて，並び替えやフィルタリングを実施してみよう

// DB接続
$dbn = 'mysql:dbname=gsacf_l06_07;charset=utf8mb4;port=3306;host=localhost'; //データベースに接続
$user = 'root';
$pwd = '';

// var_dump($dbn);
// exit;

try {
  $pdo = new PDO($dbn, $user, $pwd);
} catch (PDOException $e) {
  echo json_encode(["db error" => "{$e->getMessage()}"]);
  exit();
}

// SQL作成&実行
$sql = 'SELECT * FROM thread_table ORDER BY updated_at DESC';
$stmt = $pdo->prepare($sql);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

// SQL実行の処理
$result = $stmt->fetchAll(PDO::FETCH_ASSOC); //fetchAllでデータを取れる
// echo '<pre>';
// var_dump($result);
// exit;
// echo '</pre>';

$output = "";
foreach ($result as $record) {
  $output .= "
    <li>
      <a href=./thread_read.php?id={$record['id']}>{$record["thread"]}</a>
      <div class='list_created_at'>更新日時：{$record["updated_at"]}</div>
    </li>
  ";
  // <div class='list_created_at'>{$record_comment["created_at"]}</div>
}

////////////////////////////////////////
$dbn = 'mysql:dbname=gsacf_l06_07;charset=utf8mb4;port=3306;host=localhost'; //データベースに接続
$user = 'root';
$pwd = '';

// var_dump($dbn);
// exit;

try {
  $pdo = new PDO($dbn, $user, $pwd);
} catch (PDOException $e) {
  echo json_encode(["db error" => "{$e->getMessage()}"]);
  exit();
}
// comment
$sql = 'SELECT * FROM comment_table WHERE thread_id = :thread_id ORDER BY created_at DESC';
$stmt = $pdo->prepare($sql);

$stmt->bindValue(':thread_id', $_GET['id'], PDO::PARAM_STR);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

// SQL実行の処理
$result_comment = $stmt->fetchAll(PDO::FETCH_ASSOC);
$output_comment = "";
foreach ($result_comment as $record_comment) {
  $output_comment .= "
  <li>
      <div class='list_comment_name'>{$record_comment["comment_name"]}</div>
      <div class='list_comment'>{$record_comment["comment"]}</div>
      <div class='list_created_at'>投稿日時：{$record_comment["created_at"]}</div>
      <hr>  
  ";
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>23cahnnel</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <header>
    <a href="https://5ch.net/">
      <img src="img/rogo01.png" alt="">
    </a>
  </header>
  <!-- <a href="todo_input.php">入力画面</a> -->
  <div class="body_main">
    <div class="thread_main">
      <form action="thread_create.php" method="POST">
        <fieldset>
          <legend>スレ建て</legend>
          <!-- <a href="todo_read.php">一覧画面</a> -->
          <div>
            名前:（任意）
            <div>
              <input type="text" name="thread_name" placeholder="名前">
            </div>
          </div>
          <div>
            スレッド:（※必須）
            <div>
              <input type="text" name="thread" placeholder="タイトル">
            </div>
          </div>
          <div>
            <button>スレをたてる</button>
          </div>
        </fieldset>
      </form>
      <div class="thread_container">
        <ul>
          <?= $output ?>
        </ul>
      </div>
      <div class="ad_img">
        <a href="https://ad.games.dmm.com/magicami_pc_001/index.html?utm_content=210052&utm_source=Adroute&utm_medium=display&utm_campaign=rtg">
          <img src="img/ad_creative.jpg" alt="">
        </a>
      </div>
    </div>
    <!-- comment -->
    <div class="comment_main">
      <form action="comment_create.php" method="POST">
        <fieldset>
          <legend>コメント</legend>
          <!-- <a href="thread_read.php">スレ画面</a> -->
          <div>
            <div class="comment_titles">
              名前:（任意）
            </div>
            <input type="text" name="comment_name" placeholder="名前">
          </div>
          <div>
            <div class="comment_titles">
              コメント:（※必須）
            </div>
            <!-- <input type="text" name="comment" placeholder="Comment"> -->
            <textarea name="comment" id="" cols="80" rows="5" placeholder="コメント"></textarea>
          </div>
          <div>
            <button>書き込む</button>
          </div>
          <input type="hidden" name="thread_id" value="<?= $_GET['id'] ?>">
        </fieldset>
      </form>
      <div class="comment_container">
        <ul>
          <?= $output_comment ?>
        </ul>
      </div>
    </div>
    <div class="left_main">
      <a href="https://ad.games.dmm.co.jp/pluslinksr_pc_001/index.html?utm_content=210110&utm_source=Adroute&utm_medium=display&utm_campaign=nml">
        <img src="img/32081_16369793885685.gif" alt="">
      </a>
      <!-- <img src="img/32081_16369793885685.gif" alt=""> -->
    </div>
  </div>
  <footer>
    <img src="img/footer.png" alt="">
  </footer>
</body>
<script>
  // PHPのデータをJSに渡す
  const resultArray = <?= json_encode($result) ?>;
  console.log(resultArray);
  // 配列からタグ生成し，#outputに表示する
</script>

</html>