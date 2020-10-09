<?php

/**
 * Файл с пользовательскими функциями
 */

/** Отпровляем сообщение на почту
 * @param string $to
 * @param string $from
 * @param string $title
 * @param string $message
 */
function sendMessageMail($to, $from, $title, $message)
{
    //Формируем заголовок письма
    $subject = $title;
    $subject = '=?utf-8?b?' . base64_encode($subject) . '?=';

    //Формируем заголовки для почтового сервера
    $headers = "Content-type: text/html; charset=\"utf-8\"\r\n";
    $headers .= "From: lk@ai.stravnik.ru\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Date: " . date('D, d M Y h:i:s O') . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    //Отправляем данные на ящик админа сайта
    if (!mail($to, $subject, $message, $headers))
        return 'Ошибка отправки письма!';
    else
        return true;
}

/** Функция вывода ошибок
 * @param array $data
 */
function showErrorMessage($data)
{
    if (is_array($data)) {
        foreach ($data as $val)
            @$err .= '<div class="clearfix"></div><div class="alert alert-danger" role="alert">' . $val . '</div><div class="clearfix"></div>';
    } else
        @$err .= '<div class="clearfix"></div><div class="alert alert-danger" role="alert">' . $data . '</div><div class="clearfix"></div>';
    return $err;
}

/** Проверка валидации email
 * @param string $email
 * return boolian
 */
function emailValid($email)
{
    if (function_exists('filter_var')) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    } else {
        if (!preg_match("/^[a-z0-9_.-]+@([a-z0-9]+\.)+[a-z]{2,6}$/i", $email)) {
            return false;
        } else {
            return true;
        }
    }
}

function logTelegram($type, $message){
    $botApiToken = ''; // токен бота
    $id = isset($_SESSION['userId']) ? ' id - ' . $_SESSION['userId'] . ', ' : '';
    $data = [
        'chat_id' => '', // название канала
        'text' => 'Ошибка при формировании уведомления'
    ];
    $resp = file_get_contents("https://api.telegram.org/bot{$botApiToken}/sendMessage?" . http_build_query($data) );
}