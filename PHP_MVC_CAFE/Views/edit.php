<?php
require_once(ROOT_PATH .'./Controllers/contactController.php');
$index = new contactController();
$data =  $index->index();

// edit.phpへ直接リダイレクトした場合、contact.phpへ画面遷移
if (empty($_SERVER["HTTP_REFERER"])) {
    $host = $_SERVER['HTTP_HOST'];
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    header("Location: //$host$uri/contact.php");
}

// 接続設定
$dsn = 'mysql:host=localhost;dbname=casteria;charset=utf8';
$user = 'root';
$pass = 'root';

// データベースに接続
try {
    $pdo = new PDO($dsn, $user, $pass, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    echo '接続成功';
} catch (PDOException $e) {
    echo '接続失敗'.$e->getMessage();
    exit();
};

$id = $_GET['id'];

// DBのデータを取得する
try {
    // トランザクション開始
    $pdo -> beginTransaction();
    // SQLを予め用意しておく
    $stmt = $pdo->prepare('SELECT * FROM contacts WHERE id = :id');
    $stmt->bindParam(':id', $id, PDO::PARAM_STR);
    // クエリを実行
    $stmt->execute();
    //カラム名のみを取得する
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // コミット
    $pdo -> commit();
} catch (PDOException $e) {
    // ロールバック
    $pdo -> rollBack();
    echo '更新失敗'.$e->getMessage();
    exit();
};

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Casteria</title>
    <link rel="stylesheet" type="text/css" href="css/base.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/form.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" 
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.7/css/swiper.min.css" />
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <script defer src="js/index.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
</head>
<body>
    <?php require("header.php")?>
    <?php
        // エラーメッセージ(要素0～7まで)を定義
        $errormsg = array('', '', '', '', '', '', '', '');
        // 電話番号の正規表現を定義
        $tel_much = "/^(0{1}\d{9,10})$/";
      
    if (!empty($_POST)) {
          // 入力チェック
        if (empty($_POST['name'])) {
            $errormsg[0] = '氏名は必須入力です。';
        } elseif (mb_strlen($_POST['name']) > 10) {
            $errormsg[1] = '10文字以内で入力してください。';
        }
        if (empty($_POST['kana'])) {
            $errormsg[2] = 'カナは必須入力です。';
        } elseif (mb_strlen($_POST['kana']) > 10) {
            $errormsg[3] = '10文字以内で入力してください。';
        }
        if (!preg_match($tel_much, $_POST['tel'])) {
            $errormsg[4] = '電話番号は0-9の数字のみで入力してください。';
        }
        if (empty($_POST['email'])) {
            $errormsg[5] = 'メールアドレスは必須入力です。';
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errormsg[6] = 'メールアドレスの形式で入力して下さい。';
        }
        if (empty($_POST['body'])) {
            $errormsg[7] = 'お問い合わせ内容は必須入力です。';
        }
        // エラーが1つもない場合、確認画面へ遷移
        if ($errormsg[0] === '' && $errormsg[1] === '' && $errormsg[2] === '' && $errormsg[3] === '' &&
        $errormsg[4] === '' && $errormsg[5] === '' && $errormsg[6] === '' && $errormsg[7] === '') {
            if (!empty($_GET["id"])) {
                $_SESSION["id"] = $_GET["id"];
            }
            // POSTの値をまとめてセッションに代入する
            $_SESSION["name"] = $_POST["name"];
            $_SESSION["kana"] = $_POST["kana"];
            $_SESSION["tel"] = $_POST["tel"];
            $_SESSION["email"] = $_POST["email"];
            $_SESSION["body"] = $_POST["body"];
            header('Location:edit_confirm.php');
            exit();
        }
    }

    foreach ($result as $val) {
    }
    ?>
    <div class="wrapper">
        <h1>編集画面</h1>
        <form id="form" action="edit_confirm.php" method="POST">
            <dl class="form-content">
                <!-- ID -->
                <dd><input type="hidden" name ="id" value ="<?php echo $_GET['id']; ?>"></dd>
                <!-- 名前 -->
                <dt>名前</dt>
                <dd><input type="text" id="name" name="name" 
                value="<?php echo htmlspecialchars($val["name"], ENT_QUOTES, "UTF-8"); ?>"></dd>
                <dd class="error-php">
                    <?php
                    if (!empty($errormsg[0])) {
                        echo htmlspecialchars($errormsg[0], ENT_QUOTES, "UTF-8");
                    } elseif (!empty($errormsg[1])) {
                            echo htmlspecialchars($errormsg[1], ENT_QUOTES, "UTF-8");
                    }
                    ?>
                </dd>
                <!-- フリガナ -->
                <dt>フリガナ</dt>
                <dd><input type="text" id="kana" name="kana" 
                value="<?php echo htmlspecialchars($val["kana"], ENT_QUOTES, "UTF-8"); ?>"></dd>
                <dd class="error-php">
                    <?php
                    if (!empty($errormsg[2])) {
                        echo htmlspecialchars($errormsg[2], ENT_QUOTES, "UTF-8");
                    } elseif (!empty($errormsg[3])) {
                            echo htmlspecialchars($errormsg[3], ENT_QUOTES, "UTF-8");
                    }
                    ?>
                </dd>
                <!-- 電話番号 -->
                <dt>電話番号</dt>
                <dd><input type="tel" id="tel" name="tel" 
                value="<?php echo htmlspecialchars($val["tel"], ENT_QUOTES, "UTF-8"); ?>"></dd>
                <dd class="error-php"><?php echo htmlspecialchars($errormsg[4], ENT_QUOTES, "UTF-8"); ?></dd>
                <!-- メールアドレス -->
                <dt>メールアドレス</dt>
                <dd><input type="text" id="email" name="email" 
                value="<?php echo htmlspecialchars($val["email"], ENT_QUOTES, "UTF-8"); ?>"></dd>
                <dd class="error-php">
                    <?php
                    if (!empty($errormsg[5])) {
                        echo htmlspecialchars($errormsg[5], ENT_QUOTES, "UTF-8");
                    } elseif (!empty($errormsg[6])) {
                            echo htmlspecialchars($errormsg[6], ENT_QUOTES, "UTF-8");
                    }
                    ?>
                </dd>
                <!-- お問い合わせ内容 -->
                <dt>お問い合わせ内容</dt>
                <dd><textarea id="body" name="body" rows="10"
                ><?php echo htmlspecialchars($val["body"], ENT_QUOTES, "UTF-8"); ?></textarea></dd>
                <dd class="error-php"><?php echo htmlspecialchars($errormsg[7], ENT_QUOTES, "UTF-8"); ?></dd>
                <button type="submit">送信</button>
            </dl><!-- form-content -->
        </form>
    </div><!-- wrapper -->
    <?php require("footer.php")?>
</body>
</html>