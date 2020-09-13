<?php

header('Content-Type: text/html; charset=utf-8');

function createSalt()
{
    # Генератор соли для хэша
    $text = md5(uniqid(rand(), TRUE));
    return substr($text, 0, 3);
}

$hash = hash('sha256', $_POST['date'] . $_POST['tel']); # sha256(дата митинга+телефон)
$salt = createSalt();                                 # соль
$hash_with_salt = hash('sha256', $salt . $hash);      # sha256(sha256(дата+тел)+соль)

$command_path_a_copy = "mkdir -p uploads/" . $hash_with_salt . "/xml; cp -r example_docx_unzip uploads/" . $hash_with_salt . "/xml/";
exec($command_path_a_copy);

$filename = "uploads/" . $hash_with_salt . "/xml/example_docx_unzip/word/document.xml";

/* Открываем xmlку с содержимым docx файла
   example_docx_unzip - папка, куда разархивирован 'Пример уведомления.docx' */
$file = file($filename);
// TODO: Сделать рефакторинг имен переменных
$file[1] = str_ireplace('Fff', htmlspecialchars($_POST['Fff'],ENT_QUOTES), $file[1]); # меняем ФИО на введённые фио
$file[1] = str_ireplace('tel', htmlspecialchars($_POST['tel'],ENT_QUOTES), $file[1]); # Меняем tel на введённый телефон
$file[1] = str_ireplace('purpose', htmlspecialchars($_POST['purpose'],ENT_QUOTES), $file[1]); # ... и так далее
$file[1] = str_ireplace('Brrr', htmlspecialchars($_POST['form'],ENT_QUOTES), $file[1]);
$file[1] = str_ireplace('place', htmlspecialchars($_POST['place'],ENT_QUOTES), $file[1]);
$file[1] = str_ireplace('date', htmlspecialchars($_POST['date'],ENT_QUOTES), $file[1]);
$file[1] = str_ireplace('num', htmlspecialchars($_POST['num'],ENT_QUOTES), $file[1]);
$file[1] = str_ireplace('Prrr', date("m.d.Y"), $file[1]);

if ($_POST['sound'] == '1') {
    $file[1] = str_ireplace('sound', 'с использованием', $file[1]);
} else {
    $file[1] = str_ireplace('sound', 'без использования', $file[1]);
}

if (strtolower($_POST['form']) == 'митинг' or $_POST['form']=='Митинг') {
    $file[1] = str_ireplace('Frm2', 'митинга', $file[1]);
} else {
    if (strtolower($_POST['form']) == 'шествие' or $_POST['form']=='Шествие') {
        $file[1] = str_ireplace('Frm2', 'шествия', $file[1]);
    } else {
        if (strtolower($_POST['form']) == 'Пикетирование' or strtolower($_POST['form']) == 'Пикет') {
            $file[1] = str_ireplace('Frm2', 'пикетирования', $file[1]);
        } else {
            if (strtolower($_POST['form']) == 'собрание' or $_POST['form']=='Собрание') {
                $file[1] = str_ireplace('Frm2', 'собрания', $file[1]);
            } else {
                if (strtolower($_POST['form']) == 'демонстрация' or $_POST['form']=='Демонстрация') {
                    $file[1] = str_ireplace('Frm2', 'демонстрации', $file[1]);
                } else {
                    $file[1] = str_ireplace('Frm2', htmlspecialchars(strtolower($_POST['form']),ENT_QUOTES), $file[1]);
                }
            }
        }
    }
}

file_put_contents($filename, $file); # сохраняем изменения в xml'ке

$command_done = ('cd uploads/' . $hash_with_salt . '/xml/example_docx_unzip; zip ../1.docx -r *'); # собираем docx обратно в папку uploads с названием $hash_and_salt

if (exec($command_done)) {

    // Подключаем конфигурационный файл
    include 'config.php';

    // Подключаем скрипт с функциями
    include 'func/func.php';

    // Подключаем MySQL
    include 'bd/bd.php';

    /* Если все хорошо, пишем данные в базу */
    $sql = 'INSERT INTO `requests` SET 
                           `fio_org` = :fio_org, 
                           `tel_org` = :tel_org, 
                           `format` = :format, 
                           `place` = :place, 
                           `date` = :date, 
                           `count` = :count, 
                           `message` = :message, 
                           `sound` = :sound';
    // Подготавливаем PDO выражение для SQL запроса
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':fio_org', $_POST['Fff'], PDO::PARAM_STR);
    $stmt->bindValue(':tel_org', $_POST['tel'], PDO::PARAM_STR);
    $stmt->bindValue(':format', $_POST['form'], PDO::PARAM_STR);
    $stmt->bindValue(':place', $_POST['place'], PDO::PARAM_STR);
    $stmt->bindValue(':date', $_POST['date'], PDO::PARAM_STR);
    $stmt->bindValue(':count', $_POST['num'], PDO::PARAM_STR);
    $stmt->bindValue(':message', $_POST['purpose'], PDO::PARAM_STR);
    $stmt->bindValue(':sound', $_POST['sound'], PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo("<html><br><br><br><link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css\" integrity=\"sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm\" crossorigin=\"anonymous\"><center><h2>Документ готов для скачивания</h2><center><br><a href=\"uploads/" . $hash_with_salt . "/xml/1.docx\" class=\"btn btn-primary btn-lg active\" role=\"button\" aria-pressed=\"true\">Скачать уведомление</a>"); # Выводим ссылку для скачивания
    } else {
        echo 'Ошибка при запросе к БД<br>';
        echo $sql . '<br>';
        echo '<pre>';
        var_dump($_POST);
        echo '</pre>';
    }
} else {
    echo 'Ошибка при формировании уведомления.';
}

?>
