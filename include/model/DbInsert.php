<?php
require_once dirname(__FILE__).'/../config/DbManager.php';

function insert_content(string $name, string $content) {
    try {
        //DBへ接続
        $db = getDb();
        //INSERT作成
        $create_sql = $db->prepare('INSERT INTO contents(NAME, CONTENT) VALUES(?, ?)');
        $create_sql->bindValue(1, $name);
        $create_sql->bindValue(2, $content);
        //INSERT実行
        $create_sql->execute();
    } catch(PDOException $e) {
        print "エラーメッセージ:{$e->getMessage()}";
    }
}