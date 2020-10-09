<?php

/**
 * Обработчик редактирования анкеты пользователя
 */

$pageTitle = 'Моя анкета | редактирование';
$h1Title = 'Редактирование профиля';
$pageDesc = '';

if ($user === false) {
    echo '<div class="alert alert-info" role="alert">Привет Гость, доступ закрыт авторизируйтесь!</div>' . "\n";
    $reg_login = '';
    $reg_user_name = '';
    $reg_user_tel = '';
    $reg_avatar = '';
}

if ($user === true) {
    $sql = 'SELECT * FROM `' . BEZ_DBPREFIX . 'users` WHERE `u_login`=:email';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':email', $userLogin, PDO::PARAM_STR);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $reg_login = htmlspecialchars($rows[0]['u_login']);
    $reg_user_name = htmlspecialchars($rows[0]['u_name']);


    echo "<!-- HOST: " . BEZ_HOST . "<br>";
    $folder_tmp = ROOT_DIR . "/uploads/avatars";
    echo "FULL: " . $folder_tmp . "<br>";
    echo "ROOT_DIR: " . ROOT_DIR . "<br> -->";

    if ($rows[0]['login'] == $_SESSION['login']) {

        if (isset($_POST['submit'])) {

            //echo "1 Нажали кнопку.<br>";

            if (count($err) > 0)
                echo showErrorMessage($err);
            else {

                //echo "2 Подгатавливаем запрос.<br>";

                /* Создаем запрос на запись в базу
                  новой информации от пользователя */
                $sql2 = 'UPDATE `' . BEZ_DBPREFIX . 'users` SET u_name=:user_name, u_position=:user_position WHERE u_login=:login';
                //Подготавливаем PDO выражение для SQL запроса
                $stmt = $db->prepare($sql2);

                $stmt->bindValue(':user_name', $_POST['user_name'], PDO::PARAM_STR);
                $stmt->bindValue(':user_position', '1', PDO::PARAM_STR);
                $stmt->bindValue(':login', $userLogin, PDO::PARAM_STR);

                //echo "3 Выполняем запрос.<br>";

                $stmt->execute();
            }

            header('Location:' . BEZ_HOST . '?mode=profile');
            exit;
        }
    }
} else {
    header('Location:' . BEZ_HOST . '?mode=error&errorNum=2');
    exit;
}


?>