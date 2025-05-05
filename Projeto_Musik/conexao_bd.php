<?php
    $host = "127.0.0.1";
    $user = "root";
    $port = "3306";
    $password ="ceub123456";
    $dbname = "sitemusica";

    $conexao = new PDO(
        'mysql:host='. $host . ';
        port=' . $port . ';
        dbname=' . $dbname ,
        $user,
        $password);
?>
