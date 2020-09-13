<?php

/**
 *
 */

$sql = 'SELECT * FROM `requests` WHERE `id`=:id';

$stmt = $db->prepare($sql);
$stmt->bindValue(':id', (int)$_GET['id'], PDO::PARAM_STR);

if ($stmt->execute()) {

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($rows) > 0) {
        $id = htmlspecialchars($rows[0]['id']);
        $fio_org = htmlspecialchars($rows[0]['fio_org']);
        $tel_org = htmlspecialchars($rows[0]['tel_org']);
        $format = htmlspecialchars($rows[0]['format']);
        $place = htmlspecialchars($rows[0]['place']);
        $date = htmlspecialchars($rows[0]['date']);
        $count = htmlspecialchars($rows[0]['count']);
        $message = nl2br(htmlspecialchars($rows[0]['message']));
        if ($sound = htmlspecialchars($rows[0]['sound']) == '1') {
            $sound = 'Не будет использоваться';
        } elseif ($sound = htmlspecialchars($rows[0]['sound']) == '2') {
            $sound = 'Будет использоваться';
        }
        $views = htmlspecialchars($rows[0]['views']);
        $members = htmlspecialchars($rows[0]['members']);
        $creation_date = date("d.m.y H:i", strtotime($rows[0]['creation_date']));

        $pageTitle = 'Тип мероприятия: ' . $format . ' (id: ' . (int)$_GET['id'] . ')';
        $h1Title = 'Тип мероприятия: ' . $format;
        $subTitle = 'Дата проведения: ' . $date;
        $pageDesc = '';
    } else {
        echo 'Мероприятие с таким id не найдено.';
    }
} else {
    echo 'Ошибка выполнения запроса к БД';
}

?>
