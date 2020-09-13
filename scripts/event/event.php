<?php

/**
 *
 */

$pageTitle = 'Мероприятие (id: ' . $_GET['id'] . ')';
$h1Title = 'Мероприятие. Дата проведения 31.12.20 в 23:59:59';
$pageDesc = 'test';

?>

<div class="col-md-8 col-md-offset-2">
    <!-- TODO: Сделать установку точки по координатам из БД -->
    <script type="text/javascript" charset="utf-8" async
            src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3A7f88e7e8c181fff38802f19ee678fd22a85926f5b938752ea3d1b8c075d1d248&amp;width=100%25&amp;height=400&amp;lang=ru_RU&amp;scroll=true"></script>
</div>
<div class="col-md-8 col-md-offset-2">
    <h3>Описание</h3>
    Описание мероприятия.<br>
    Подробное или не очень.

    <h3>Организаторы</h3>
    МАО "Рога и копыта"

    <h3>Контакты</h3>
    Иванов Иван Иванович<br>
    <a href="tel:+70000000000">+7 (000) 000-00-00</a>

    <h3>Статус</h3>
    <span class="label label-success">Согласовано</span>
    <span class="label label-warning">В процессе согасования</span>
    <span class="label label-danger">Не согласовано</span>
</div>
<div class="col-md-8 col-md-offset-2">
<hr>
</div>
<div class="col-md-8 col-md-offset-2">
    <form class="form-inline">
        <div class="form-group">
            <span class="help-block">Собираетесь посетить?</span>
        </div>
        <br>
        <div class="form-group">
            <div class="">
                <button type="submit" class="btn btn-info">Я пойду!</button>
            </div>
        </div>
    </form>
</div>
<div class="col-md-8 col-md-offset-2">
    <hr>
</div>
