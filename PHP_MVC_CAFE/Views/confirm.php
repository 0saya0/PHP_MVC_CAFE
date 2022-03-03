<?php
require_once(ROOT_PATH .'./Controllers/contactController.php');
$index = new contactController();
$data =  $index->create();

// confirm.phpへ直接リダイレクトした場合、contact.phpへ画面遷移
if (empty($_SERVER["HTTP_REFERER"])) {
    $host = $_SERVER['HTTP_HOST'];
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    header("Location: //$host$uri/contact.php");
}

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
</head>
<body>
<?php require("header.php"); ?>
<div class="wrapper">
    <h1>確認画面</h1>
    <form action="complete.php" method="POST">
        <dl class="form-content">
            <dt class="confirm-dt">名前</dt>
            <dd><?php echo htmlspecialchars($_SESSION['name'], ENT_QUOTES, "UTF-8"); ?></dd>
            <dt class="confirm-dt">フリガナ</dt>
            <dd><?php echo htmlspecialchars($_SESSION['kana'], ENT_QUOTES, "UTF-8"); ?></dd>
            <dt class="confirm-dt">電話番号</dt>
            <dd><?php echo htmlspecialchars($_SESSION['tel'], ENT_QUOTES, "UTF-8"); ?></dd>
            <dt class="confirm-dt">メールアドレス</dt>
            <dd><?php echo htmlspecialchars($_SESSION['email'], ENT_QUOTES, "UTF-8"); ?></dd>
            <dt class="confirm-dt">お問い合わせ内容</dt>
            <dd class="new-line"><?php echo htmlspecialchars($_SESSION['body'], ENT_QUOTES, "UTF-8"); ?></dd>
            <dd>上記内容でよろしいですか？</dd>
            <div class="button">
                <dd><button type="button" onclick = "history.back()">キャンセル</button>
                <dd><button type="submit">送信</button>
            </div>
        </dl>
    </form>
</div>
    <?php require("footer.php"); ?>
</body>
</html>