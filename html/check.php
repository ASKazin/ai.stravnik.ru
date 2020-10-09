<?php

/**
 *
 */

?>

<?php
if ($user === false) { ?>
    <li><a href="<?php echo BEZ_HOST; ?>?mode=index">Главная</a></li>
    <li><a href="<?php echo BEZ_HOST; ?>?mode=auth">Войти</a></li>
    <li><a href="<?php echo BEZ_HOST; ?>?mode=reg">Регистрация</a></li>
    <?php
}

if ($user === true) { ?>
    <li><a href="<?php echo BEZ_HOST; ?>?mode=index">Главная</a></li>
    <li><a href="<?php echo BEZ_HOST; ?>?mode=profile">Профиль</a></li>
    <li><a href="<?php //echo BEZ_HOST; ?>generator.html" target="_blank">Подать уведомление</a></li>
    <li><a href="<?php BEZ_HOST; ?>?mode=auth&exit=true"><span class="glyphicon gmenu glyphicon-off"></span> Выход
            (<?php echo $userLogin; ?>)</a></li>
    <?php
}
?>