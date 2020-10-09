<?php

/**
 * Обработчик формы авторизации
 */

$pageTitle = 'Вход на сайт';
$h1Title = 'Вход в личный кабинет';
$pageDesc = '';

// Выход из авторизации
if (isset($_GET['exit']) == true) {
    // Уничтожаем сессию
    session_unset();
    if (session_destroy()) {
        // Делаем редирект
        header('Location:' . BEZ_HOST . '?mode=auth');
        exit;
    } else {
        echo "Ошибка удаления сессии.";
    }
}
if ($user === FALSE) {
    include 'scripts/auth/auth_form.html';
} else {
    $err[] = 'Вы уже авторизованы.';
    if (count($err) > 0) {
        echo showErrorMessage($err);
    }
}
// Если нажата кнопка то обрабатываем данные
if (isset($_POST['submit'])) {
    // Проверяем на пустоту
    if (empty($_POST['email'])) {
        $err[] = 'Не введен Логин.';
    }
    if (empty($_POST['password'])) {
        $err[] = 'Не введен Пароль.';
    }
    // Проверяем email
    if (emailValid($_POST['email']) === false) {
        $err[] = 'Не корректный E-mail.';
    }
    // Проверяем наличие ошибок и выводим пользователю
    if (count($err) > 0)
        echo showErrorMessage($err);
    else {
        /* Создаем запрос на выборку из базы
          данных для проверки подлинности пользователя */
        $sql = 'SELECT * FROM `users` WHERE `u_login` = :email';
        // Подготавливаем PDO выражение для SQL запроса
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
        $stmt->execute();
        // Получаем данные SQL запроса
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Если логин совпадает, проверяем пароль
        if (count($rows) > 0) {
            // Получаем данные из таблицы
            if (hash('sha256', $_POST['password'] . $rows[0]['salt']) == $rows[0]['u_password']) {
                $_SESSION['user'] = true;
                $_SESSION['userId'] = $rows[0]['u_id'];
                $_SESSION['userLogin'] = $rows[0]['u_login'];
                $_SESSION['userPosition'] = $rows[0]['u_position'];
                $_SESSION['userStatus'] = $rows[0]['u_status'];
                $_SESSION['testStatus'] = $rows[0]['u_test'];
                $_SESSION['notify'] = $rows[0]['u_notify'];

                if ($_SESSION['userPosition'] != '1') {
                    // Уничтожаем сессию
                    session_unset();
                    if (session_destroy()) {
                        // Делаем редирект
                        header('Location:' . BEZ_HOST . '?mode=index');
                        exit;
                    } else {
                        echo "exit error.";
                    }
                }

                // Сбрасываем параметры
                header('Location:' . BEZ_HOST . '?mode=index');
                exit;
            } else {
                echo showErrorMessage('Неверный пароль!');
            }
        } else {
            echo showErrorMessage('Логин <b>' . $_POST['email'] . '</b> не найден!');
        }
    }
}
?>
