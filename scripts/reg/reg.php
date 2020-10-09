<?php

/**
 * Обработчик формы регистрации
 */

$pageTitle = 'Регистрация на сайте';
$h1Title = 'Регистрация';
$pageDesc = '';

// Выводим сообщение об удачной регистрации
if (isset($_GET['status']) and $_GET['status'] == 'ok')
    echo '<div class="alert alert-success" role="alert"><b>Вы успешно зарегистрировались! Пожалуйста активируйте свой аккаунт!</b></div>';

// Выводим сообщение об удачной активации
if (isset($_GET['active']) and $_GET['active'] == 'ok')
    echo '<div class="alert alert-success" role="alert"><b>Ваш аккаунт успешно активирован!</b></div>';

// Производим активацию аккаунта
if (isset($_GET['key'])) {
    // Проверяем ключ
    $sql = 'SELECT *
			FROM `users`
			WHERE `u_login` = :key';          // внесено изменение
    // Подготавливаем PDO выражение для SQL запроса
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':key', $_GET['key'], PDO::PARAM_STR);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($rows) == 0)
        $err[] = '<div class="alert alert-danger" role="alert">Ключ активации не верен!</div>';

    // Проверяем наличие ошибок и выводим пользователю
    if (count($err) > 0)
        echo showErrorMessage($err);
    else {
        // Получаем адрес пользователя
        $email = $rows[0]['u_login'];

        // Активируем аккаунт пользователя
        $sql = 'UPDATE `users`
				SET `u_status` = 1
				WHERE `u_login` = :email';
        // Подготавливаем PDO выражение для SQL запроса
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        //TODO: Добавить проверку уже активированного аккаунта

        // Отправляем письмо для активации
        $title = 'Ваш аккаунт успешно активирован';
        $message = 'Поздравляю Вас, Ваш аккаунт успешно активирован';

        sendMessageMail($email, BEZ_MAIL_AUTOR, $title, $message);

        /* Перенаправляем пользователя на
          нужную нам страницу */
        header('Location:' . BEZ_HOST . '?mode=reg&active=ok');
        exit;
    }
}

if ($user === FALSE) {
    include 'scripts/reg/reg_form.html';
} else {
    $err[] = 'Вы уже залогированы.';
    if (count($err) > 0) {
        echo showErrorMessage($err);
    }
}

/* Если нажата кнопка на регистрацию,
  начинаем проверку */
if (isset($_POST['submit'])) {
    // Утюжим пришедшие данные
    if (empty($_POST['email']))
        $err[] = '<div class="alert alert-danger" role="alert">Поле Email не может быть пустым!</div>';
    else {
        if (emailValid($_POST['email']) === false)
            $err[] = '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><span class="sr-only">Error:</span>Не правильно введен E-mail</div>' . "\n";
    }

    if (empty($_POST['pass']))
        $err[] = '<div class="alert alert-danger" role="alert">Поле Пароль не может быть пустым</div>';

    if (empty($_POST['pass2']))
        $err[] = '<div class="alert alert-danger" role="alert">Поле Подтверждения пароля не может быть пустым</div>';

    // Проверяем наличие ошибок и выводим пользователю
    if (count($err) > 0)
        echo showErrorMessage($err);
    else {
        /* Продолжаем проверять введенные данные
          Проверяем на совпадение пароли */
        if ($_POST['pass'] != $_POST['pass2'])
            $err[] = '<div class="alert alert-danger" role="alert">Пароли не совпадают</div>';

        // Проверяем наличие ошибок и выводим пользователю
        if (count($err) > 0)
            echo showErrorMessage($err);
        else {
            /* Проверяем существует ли у нас
              такой пользователь в БД */

            $sql = 'SELECT `u_login`
					FROM `' . BEZ_DBPREFIX . 'users`
					WHERE `u_login` = :login';
            // Подготавливаем PDO выражение для SQL запроса
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':login', $_POST['email'], PDO::PARAM_STR);

            $stmt->execute();

            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($rows) > 0)
                $err[] = '<div class="alert alert-danger" role="alert">К сожалению Логин: <b>' . $_POST['email'] . '</b> занят!</div>';

            // Проверяем наличие ошибок и выводим пользователю
            if (count($err) > 0)
                echo showErrorMessage($err);
            else {

                // Получаем ХЕШ соли
                $salt = substr(hash('sha256',random_bytes(64)), 2, 60)

                // Солим пароль
                $pass = hash('sha256',$_POST['pass'].$salt);

                /* Если все хорошо, пишем данные в базу */
                $sql = 'INSERT INTO `users` SET `salt` = :salt,`u_login` = :email,`u_password` = :pass,`u_position` = 0,`u_status` = 0'; // внесены изменения
                // Подготавливаем PDO выражение для SQL запроса
                $stmt = $db->prepare($sql);
                $stmt->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
                $stmt->bindValue(':pass', $pass, PDO::PARAM_STR);
		$stmt->bindValue(':salt', $salt, PDO::PARAM_STR);
                $stmt->execute();

                $_SESSION['userId'] = $db->lastInsertId();

                // Отправляем письмо для активации
                $url = BEZ_HOST . '?mode=reg&key=' . $_POST['email'];
                $title = 'Регистрация';
                $message = 'Для активации Вашего акаунта пройдите по ссылке
				<a href="' . $url . '">' . $url . '</a>';

                sendMessageMail($_POST['email'], BEZ_MAIL_AUTOR, $title, $message);

                $_SESSION['user'] = true;
                $_SESSION['userLogin'] = $_POST['email'];
                $_SESSION['userPosition'] = 0;
                $_SESSION['userStatus'] = 0;

                // Сбрасываем параметры
                header('Location:' . BEZ_HOST . '?mode=profile_edit');
                exit;
            }
        }
    }
}
?>
