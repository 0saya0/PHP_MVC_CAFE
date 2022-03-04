<?php
require_once('../Models/contacts.php');
class ContactController
{
    private $request; //リクエストパラメータ(GET,POST)
    private $Contact; //Contactモデル

    public function __construct()
    {
        // リクエストパラメータの取得
        $this->request['get'] = $_GET;
        $this->request['post'] = $_POST;
        // モデルオブジェクトの生成
        $this->Contact = new Contact();
    }

    public function index()
    {
        $contacts = $this->Contact->findAll();
        return $contacts;
    }
    public function create()
    {
        $this->Contact->create();
    }
    public function update()
    {
        $this->Contact->update();
    }
    public function delete()
    {
        $this->Contact->delete();
    }
}
