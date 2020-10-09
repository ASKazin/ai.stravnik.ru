<?php

$pageTitle = 'Моя анкета (id пользователя: ' . $_SESSION['id_reg'] . ')';
$h1Title = 'Мой профиль';
$pageDesc = '';

if ($user === false) {
    header('Location:' . BEZ_HOST . '?mode=error&errorNum=8');
    exit;
}
if ($user === true) {

    $sql = 'SELECT * FROM `' . BEZ_DBPREFIX . 'users` WHERE `u_login`=:email';

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':email', $userLogin, PDO::PARAM_STR);
    $stmt->execute();

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $reg_login = htmlspecialchars($rows[0]['u_login']);
    $reg_user_name = htmlspecialchars($rows[0]['u_name']);
    // $reg_user_tel = htmlspecialchars($rows[0]['u_tel']);

    $sql = 'SELECT * FROM `' . BEZ_DBPREFIX . 'companys` WHERE `id_company`=:id_company';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':id_company', $company_id, PDO::PARAM_STR);
    $stmt->execute();

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($rows) > 0) {
        $id_company = $rows[0]['id_company'];
        $c_section = $rows[0]['c_section'];
        $c_name = htmlspecialchars($rows[0]['c_name']);
        //$short_desc = htmlspecialchars($rows[0]['short_desc']);
        //$full_desc = htmlspecialchars($rows[0]['full_desc']);
        //$country = $rows[0]['country'];
        //$city = $rows[0]['city'];
        //$address = $rows[0]['address'];
        //$c_tel = $rows[0]['c_tel'];
        //$email = $rows[0]['email'];
        //$site = $rows[0]['site'];
        //$contact = $rows[0]['contact'];
        $service = $rows[0]['service'];
        $inn = $rows[0]['c_inn'];

        $cmp = "<p>";
        $cmp .= "<strong>ID компании:</strong> " . $id_company . "<br>";
        $cmp .= "<strong>Позиционирование:</strong> " . $c_section . "<br>";
        $cmp .= "<strong>Название:</strong> " . $c_name . "<br>";
        $cmp .= "<strong>Услуги:</strong> " . $service . "<br>";
        $cmp .= "<strong>ИНН:</strong> " . $inn;
        $cmp .= "</p>";

    } else {
        $cmp = "";
    }
}
?>
