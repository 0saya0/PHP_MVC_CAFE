<?php
require_once('../database.php');
class Db
{
    protected $dbh;

    public function __construct($dbh = null)
    {
        if (!$dbh) { // 接続情報が存在しない場合
            $dsn = 'mysql:host=localhost;dbname=casteria;charset=utf8';
            $user = 'root';
            $pass = 'root';
            try {
                $this->dbh = new PDO($dsn, $user, $pass);
                // 接続成功
            } catch (PDOException $e) {
                echo "接続失敗: " . $e->getMessage() . "\n";
                exit();
            }
        } else { // 接続情報が存在する場合
            $this->dbh = $dbh;
        }
    }

    public function getDbHandler()
    {
        return $this->dbh;
    }

    public function beginTransaction()
    {
        $this->dbh->beginTransaction();
    }

    public function commit()
    {
        $this->dbh->commit();
    }

    public function rollback()
    {
        $this->dbh->rollback();
    }
}
