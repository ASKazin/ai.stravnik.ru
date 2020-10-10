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
        $id_org = htmlspecialchars($rows[0]['id_org']);
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

        $sql_c = 'SELECT * FROM `users` WHERE `u_id`=:id';
        $stmt_c = $db->prepare($sql_c);
        $stmt_c->bindValue(':id', $id_org, PDO::PARAM_STR);
        $stmt_c->execute();
        $rows_c = $stmt_c->fetchAll(PDO::FETCH_ASSOC);
        $u_name = htmlspecialchars($rows_c[0]['u_name']);

        $sql_c = 'SELECT * FROM `comment` WHERE `id`=:id';
        $stmt_c = $db->prepare($sql_c);
        $stmt_c->bindValue(':id', (int)$_GET['id'], PDO::PARAM_STR);
        $stmt_c->execute();
        $rows_c = $stmt_c->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows_c as $val) {
            $comments = nl2br(htmlspecialchars($val['text']));

            $sql_c = 'SELECT * FROM `users` WHERE `u_id`=:id';
            $stmt_c = $db->prepare($sql_c);
            $stmt_c->bindValue(':id', $val['u_id'], PDO::PARAM_STR);
            $stmt_c->execute();
            $rows_c = $stmt_c->fetchAll(PDO::FETCH_ASSOC);
            $u_name = htmlspecialchars($rows_c[0]['u_name']);

            $comments_full .= '<strong>' . $u_name . '</strong> комментирует:<br>' . $comments . '<hr>';
        }

        $sql = 'UPDATE `requests` SET `views` = :views WHERE `id`=:id';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':views', (int)$views + 1, PDO::PARAM_STR);
        $stmt->bindValue(':id', (int)$_GET['id'], PDO::PARAM_STR);
        $stmt->execute();

        if (isset($_POST['yes'])) {
            $sql = 'UPDATE `requests` SET `members` = :members WHERE `id`=:id';
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':members', (int)$members + 1, PDO::PARAM_STR);
            $stmt->bindValue(':id', (int)$_GET['id'], PDO::PARAM_STR);
            $stmt->execute();
            header('Location:' . BEZ_HOST . '?mode=event&id=' . (int)$_GET['id']);
        }

        if (isset($_POST['comment'])) {
            $sql = 'INSERT INTO `comment` SET `text` = :text, `id` = :id, `u_id` = :u_id';
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':text', $_POST['comments'], PDO::PARAM_STR);
            $stmt->bindValue(':id', (int)$_GET['id'], PDO::PARAM_STR);
            $stmt->bindValue(':u_id', (int)$userId, PDO::PARAM_STR);
            $stmt->execute();
            header('Location:' . BEZ_HOST . '?mode=event&id=' . (int)$_GET['id']);
        }

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
