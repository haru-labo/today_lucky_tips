<?php
require_once dirname(__FILE__).'/../config/DbManager.php';

function select_all_contents() {
    try {
        //DBへ接続
        $db = getDb();
        //INSERT作成
        $create_sql = $db->query('SELECT NAME, CONTENT, CREATED_AT FROM contents ORDER BY id DESC');
        //INSERT実行
        return $create_sql->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        print "エラーメッセージ:{$e->getMessage()}";
    }
}