<?php

/**
 *
 */

$pageTitle = 'Информационный ресурс "Свобода собраний"';
$h1Title = 'Расписание публичных мероприятий';
$pageDesc = 'test';

$result = 'SELECT COUNT(id) FROM `requests`';
$stmt = $db->prepare($result);
$stmt->execute();
$requests = $stmt->fetchColumn();
$subTitle = 'Всего поданных уведомлений: ' . $requests;

// Запрос на выборку уведомлений
$sql = 'SELECT * FROM `requests`';
$stmt = $db->prepare($sql);

// Выводим контент
if ($stmt->execute()) {
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $val) {
        $id = htmlspecialchars($val['id']);
        $fio_org = htmlspecialchars($val['fio_org']);
        $tel_org = htmlspecialchars($val['tel_org']);
        $format = htmlspecialchars($val['format']);
        $place = htmlspecialchars($val['place']);
        $date = htmlspecialchars($val['date']);
        $count = htmlspecialchars($val['count']);
        $message = nl2br(htmlspecialchars($val['message']));
        if ($sound = htmlspecialchars($val['sound']) == '1') {
            $sound = 'Не будет использоваться';
        } elseif ($sound = htmlspecialchars($val['sound']) == '2') {
            $sound = 'Будет использоваться';
        }
        $views = htmlspecialchars($val['views']);
        $members = htmlspecialchars($val['members']);
        $creation_date = date("d.m.y H:i", strtotime($val['creation_date']));
        // $update_date=htmlspecialchars($val['update_date']);

        $event .= '<div class="col-md-4">';
        $event .= '<div class="panel panel-info">';
        $event .= '<div class="panel-heading">';
        $event .= '<a href="/?mode=event&id=' . $id . '"><h3 class="panel-title">' . $format . '</h3></a>';
        $event .= '</div>';
        $event .= '<div class="panel-body">' . mb_substr($message, 0, 30) . '...</div>';
        $event .= '<div class="panel-footer"><span class="fl-left">';
        $event .= '<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> ' . $views . ' </span>';
        $event .= '&nbsp;';
        $event .= '<span class="fl-right">';
        $event .= '<span class="glyphicon glyphicon-user" aria-hidden="true"></span> ' . $members . ' </span>';
        $event .= '</div></div></div>';
    }
    echo $event;
}

?>
