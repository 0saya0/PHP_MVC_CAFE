<?php
require_once('../Models/Db.php');
session_start();
class Contact extends Db
{
    protected $dbh;
    // private $dbh;
    public function __construct($dbh = null)
    {
        parent::__construct($dbh);
    }

    // DBのデータ取得メソッド
    public function findAll()
    {
        try {
            // トランザクション開始
            $this->dbh-> beginTransaction();
            $sql = "SELECT * FROM contacts";
            $stmt = $this->dbh-> query($sql);
            // 【【配列のキー】カラム名のみを取得する
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // コミット
            $this->dbh->commit();
        } catch (PDOException $e) {
            // ロールバック
            $this->dbh->rollBack();
            echo '接続失敗'.$e->getMessage();
            exit();
        };

        return $result;
    }

    // DBのデータ作成メソッド
    public function create()
    {
        $name = htmlspecialchars($_SESSION['name'], ENT_QUOTES, 'UTF-8');
        $kana = htmlspecialchars($_SESSION['kana'], ENT_QUOTES, 'UTF-8');
        $tel = htmlspecialchars($_SESSION['tel'], ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($_SESSION['email'], ENT_QUOTES, 'UTF-8');
        $body = htmlspecialchars($_SESSION['body'], ENT_QUOTES, 'UTF-8');
        // データの追加の処理
        try {
            // トランザクション開始
            $this -> dbh -> beginTransaction();
            $stmt = $this -> dbh -> prepare('INSERT INTO contacts(name,kana,tel,email,body) 
            VALUES("'.$name.'","'.$kana.'","'.$tel.'","'.$email.'","'.$body.'")');
            $stmt -> execute();
            // コミット
            $this-> dbh -> commit();
        } catch (PDOException $e) {
            // ロールバック
            $this -> dbh -> rollBack();
            echo '接続失敗'.$e -> getMessage();
            exit();
        };
    }

    // DBのデータ更新メソッド
    public function update()
    {
        // edit.phpより値を取得
        $_SESSION["id"] = htmlspecialchars($_POST['id'], ENT_QUOTES, "UTF-8");
        $_SESSION["name"] = htmlspecialchars($_POST['name'], ENT_QUOTES, "UTF-8");
        $_SESSION["kana"] = htmlspecialchars($_POST['kana'], ENT_QUOTES, "UTF-8");
        $_SESSION["tel"] = htmlspecialchars($_POST['tel'], ENT_QUOTES, "UTF-8");
        $_SESSION["email"] = htmlspecialchars($_POST['email'], ENT_QUOTES, "UTF-8");
        $_SESSION["body"] = htmlspecialchars($_POST['body'], ENT_QUOTES, "UTF-8");
        // データの更新の処理
        try {
            // トランザクション開始
            $this -> dbh -> beginTransaction();
            $stmt = $this -> dbh -> prepare('UPDATE contacts SET name = :name, kana = :kana,
            tel = :tel, email = :email, body = :body WHERE id = :id');
            $stmt->bindParam(':id', $_SESSION["id"], PDO::PARAM_INT);
            $stmt->bindParam(':name', $_SESSION['name'], PDO::PARAM_STR);
            $stmt->bindParam(':kana', $_SESSION['kana'], PDO::PARAM_STR);
            $stmt->bindParam(':tel', $_SESSION['tel'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR);
            $stmt->bindParam(':body', $_SESSION['body'], PDO::PARAM_STR);
            // クエリを実行
            $stmt->execute();
            // コミット
            $this -> dbh -> commit();
        } catch (PDOException $e) {
            // ロールバック
            $this -> dbh -> rollBack();
            echo '接続失敗'.$e->getMessage();
            exit();
        };
    }

    // DBのデータ削除メソッド
    public function delete()
    {
        // contact.phpより削除に必要な主キー(id)のみ受け取る
        $id = htmlspecialchars($_GET['id'], ENT_QUOTES, 'UTF-8');
        // データの削除の処理
        try {
            // トランザクション開始
            $this -> dbh -> beginTransaction();
            $stmt = $this -> dbh -> prepare('DELETE FROM contacts WHERE id = :id');
            $stmt -> bindParam(':id', $id);
            // クエリを実行
            $stmt -> execute();
            // コミット
            $this -> dbh -> commit();
            // 削除後、入力画面へリダイレクト
            header('Location: contact.php');
        } catch (PDOException $e) {
            // ロールバック
            $this -> dbh -> rollBack();
            echo '削除失敗'.$e -> getMessage();
            exit();
        };
    }


}