<?php
require_once(ROOT_PATH .'./Controllers/contactController.php');
$contacts = new ContactController();
$result = $contacts->index();

// //POSTされたデータを変数に格納
// //最初は入力データがないのでこの初期化をしないとエラーとなる
$name = isset($_POST['name']) ? $_POST['name'] : null;
$kana = isset($_POST['kana']) ? $_POST['kana'] : null;
$tel = isset($_POST['tel']) ? $_POST['tel'] : null;
$email = isset($_POST['email']) ? $_POST['email'] : null;
$body = isset($_POST['body']) ? $_POST['body'] : null;

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
            // POSTの値をまとめてセッションに代入する
            $_SESSION["name"] = $_POST["name"];
            $_SESSION["kana"] = $_POST["kana"];
            $_SESSION["tel"] = $_POST["tel"];
            $_SESSION["email"] = $_POST["email"];
            $_SESSION["body"] = $_POST["body"];
            header('Location:confirm.php');
            exit();
        }
    }
    ?>
    <div class="wrapper">
        <h1>お問い合わせ画面</h1>
        <form id="form" action="contact.php" method="POST">
            <dl class="form-content">
                <dt>名前</dt>
                <dd><input type="text" id="name" name="name" value="<?php echo $name; ?>"></dd>
                <dd class="error-php">
                    <?php
                    if (!empty($errormsg[0])) {
                        echo htmlspecialchars($errormsg[0], ENT_QUOTES, "UTF-8");
                    } elseif (!empty($errormsg[1])) {
                            echo htmlspecialchars($errormsg[1], ENT_QUOTES, "UTF-8");
                    }
                    ?>
                </dd>
                <dt>フリガナ</dt>
                <dd><input type="text" id="kana" name="kana" value="<?php echo $kana; ?>"></dd>
                <dd class="error-php">
                    <?php
                    if (!empty($errormsg[2])) {
                        echo htmlspecialchars($errormsg[2], ENT_QUOTES, "UTF-8");
                    } elseif (!empty($errormsg[3])) {
                            echo htmlspecialchars($errormsg[3], ENT_QUOTES, "UTF-8");
                    }
                    ?>
                </dd>
                <dt>電話番号</dt>
                <dd><input type="tel" id="tel" name="tel" value="<?php echo $tel; ?>"></dd>
                <dd class="error-php">
                    <?php
                    if (!empty($errormsg[4])) {
                        echo htmlspecialchars($errormsg[4], ENT_QUOTES, "UTF-8");
                    }
                    ?>
                    </dd>
                <dt>メールアドレス</dt>
                <dd><input type="text" id="email" name="email" value="<?php echo $email; ?>"></dd>
                <dd class="error-php">
                    <?php
                    if (!empty($errormsg[5])) {
                        echo htmlspecialchars($errormsg[5], ENT_QUOTES, "UTF-8");
                    } elseif (!empty($errormsg[6])) {
                            echo htmlspecialchars($errormsg[6], ENT_QUOTES, "UTF-8");
                    }
                    ?>
                </dd>
                <dt>お問い合わせ内容</dt>
                <dd><textarea id="body" name="body" rows="10" value="<?php echo $body; ?>"></textarea></dd>
                <dd class="error-php"><?php echo htmlspecialchars($errormsg[7], ENT_QUOTES, "UTF-8"); ?></dd>
                <button type="submit">送信</button>
            </dl><!-- form-content -->
        </form>
    </div><!-- wrapper -->

    <!-- テーブル参照 -->
    <div class="reference">
        <table>
            <tr class="table-title">
                <th>id</th>
                <th>名前</th>
                <th>カナ</th>
                <th>電話番号</th>
                <th>メールアドレス</th>
                <th>お問い合わせ内容<br></th>
                <th></th>
                <th></th>
            </tr><!-- table-title -->
            <?php foreach ($result as $val) : ?>
            <tr>
                <td><?php echo htmlspecialchars($val['id'], ENT_QUOTES, "UTF-8") ?></td>
                <td><?php echo htmlspecialchars($val['name'], ENT_QUOTES, "UTF-8") ?></td>
                <td><?php echo htmlspecialchars($val['kana'], ENT_QUOTES, "UTF-8") ?></td>
                <td><?php echo htmlspecialchars($val['tel'], ENT_QUOTES, "UTF-8") ?></td>
                <td><?php echo htmlspecialchars($val['email'], ENT_QUOTES, "UTF-8") ?></td>
                <td class="new-line"><?php echo htmlspecialchars($val['body'], ENT_QUOTES, "UTF-8") ?></td>
                <td><a href="edit.php?id=<?php echo $val['id'] ?>">編集</a></td>
                <td><a class="delete" href="delete.php?id=<?php echo $val['id']?>">削除</a>
                </td>
                <form action="edit.php" method="GET">
                <input type="hidden" name="id" value="<?php echo $val['id']; ?>">
                </form>
                <form action="contact.php" method="GET">
                <input type="hidden" name="id" value="<?php echo $val['id']; ?>">
                </form>
                <script>
                // DB　データ削除ポップアップ
                $(function() {
                    // delete要素に1回だけ有効なclickイベントを設定(複数発火しない)
                    $('.delete').off('click');
                    $('.delete').one('click', function (){
                    if(!confirm('データを削除しますか？')){
                        return false;
                    } else {
                        exit();
                    }
                    });
                });
                </script>
            </tr>
            <?php endforeach; ?>
        </table>
    </div><!-- reference -->   
    <?php require("footer.php")?>
</body>
</html>