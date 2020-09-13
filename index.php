<?php

/**
 *
 */

session_start();

//Устанавливаем кодировку и вывод всех ошибок
header('Content-Type: text/html; charset=UTF8');

//Включаем буферизацию содержимого
ob_start();

//Версия системы
$version = '0.8.4 от 19.09.20';

//Определяем переменную для переключателя
$mode = isset($_GET['mode']) ? $_GET['mode'] : false;
$user = isset($_SESSION['user']) ? $_SESSION['user'] : false;
$userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : false;
$userName = isset($_SESSION['userName']) ? $_SESSION['userName'] : false;
$userLogin = isset($_SESSION['userLogin']) ? $_SESSION['userLogin'] : false;
$userPosition = isset($_SESSION['userPosition']) ? $_SESSION['userPosition'] : false;
$userStatus = isset($_SESSION['userStatus']) ? $_SESSION['userStatus'] : false;
$testStatus = isset($_SESSION['testStatus']) ? $_SESSION['testStatus'] : false;
$userNotify = isset($_SESSION['notify']) ? $_SESSION['notify'] : false;
$err = array();

define('ROOT_DIR', dirname(__FILE__));

//Устанавливаем ключ защиты
define('BEZ_KEY', true);

//Подключаем конфигурационный файл
include 'config.php';

//Подключаем скрипт с функциями
include 'func/func.php';

//подключаем MySQL
include 'bd/bd.php';

switch ($mode) {
    // Подключаем обработчик с формой регистрации
    case 'reg':
        include 'scripts/reg/reg.php';
        //include 'scripts/reg/reg_form.html';
        break;

    // Подключаем обработчик с формой авторизации
    case 'auth':
        include 'scripts/auth/auth.php';
        //include 'scripts/auth/auth_form.html';
        break;

    // TODO: Добавить обработку ошибок
    case 'error':
        break;

    // Подключаем обработчик карточки мероприятия
    case 'event':
        include 'scripts/event/event.php';
        include 'scripts/event/event.html';
        break;

    // Подключаем обработчик главной страницы
    case 'index':
    case '':
        include 'scripts/index/index.php';
        include 'scripts/index/index.html';
        break;
}

//Получаем данные с буфера
$content = ob_get_contents();
ob_end_clean();

//Подключаем наш шаблон
include 'html/index.html';
