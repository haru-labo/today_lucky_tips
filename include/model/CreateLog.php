<?php

    $log_file = 'log/log.txt';
    //ログへの書き込み
    if (($fp = fopen($log_file, 'a')) !== FALSE) {
        if (fwrite($fp, $name) === FALSE || fwrite($fp, $content) === FALSE) {
            print '書き込みに失敗しました' . $log_file;
        }
        fclose($fp);
    }