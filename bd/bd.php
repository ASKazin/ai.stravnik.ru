<?php

/**
 * Подключение к базе данных
 */

//Ключ защиты
if (!defined('BEZ_KEY')) {
    header("HTTP/1.1 404 Not Found");
    //exit(file_get_contents('../../404.html'));
}

//Подключение к базе данных mySQL с помощью PDO
try {
    $db = new PDO('mysql:host=localhost;dbname=' . BEZ_DATABASE, BEZ_DBUSER, BEZ_DBPASSWORD, array(
        PDO::ATTR_PERSISTENT => true
    ));
    //$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Ошибка соединения!: " . $e->getMessage() . "<br/>";
    die();
}
$db->exec("set names utf8");
$db->exec("SET CHARACTER SET 'utf8'");
$db->exec("SET SESSION collation_connection = 'utf8_general_ci'");
