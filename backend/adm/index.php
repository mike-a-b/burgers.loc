<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 17.08.17
 * Time: 13:39
 */

/* админ панель */

//$params = require_once __DIR__. "/../config/parameters_db.php";
require_once __DIR__. "/../db_functions.php";

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="../../css/add.css">
</head>
<body>
<?php
echo "<h2><a href="."{$_SERVER['PHP_SELF']}?table=users".">1. Вывести всех зарегистрированных пользователей</a></h2>";
echo "<h2><a href="."{$_SERVER['PHP_SELF']}?table=orders".">2. Вывести все заказы</a></h2>";
if ($_GET['table'] === 'users')
{
    echo "<div class='admin__table-users'>";

    $users_head = <<<EOU
<div class='admin__table-users'>
<hr>
<ul class='admin__list-users'>
<li>ID</li>
<li>NAME</li>
<li>EMAIL</li>
<li>PHONE</li>
</ul>
<hr>
</div>
EOU;
    echo $users_head;
    $dbh = getConnection($params);
    $regUsers = getAllRegisterUsers($dbh);
    foreach ($regUsers as $regUser) {
        echo "<hr>";
        echo "<ul class='admin__list-users'>";
        echo "<li>{$regUser['id']}</li>";
        echo "<li>{$regUser['name']}</li>";
        echo "<li>{$regUser['email']}</li>";
        echo "<li>{$regUser['phone']}</li>";
        echo "</ul>";
        echo "<hr>";
    }
    echo "</div>";
} elseif ($_GET['table'] === 'orders') {
    $order_head = <<<EOS
<div class='admin__table-orders'>
<hr>
<ul class='admin__list-orders'>
<li>ID</li>
<li>USER_ID</li>
<li>ADDRESS</li>
<li>COMMENTS</li>
<li>PAYMENT</li>
<li>CALLBACK</li>
</ul>
<hr>
EOS;
    echo $order_head;
    $dbh = getConnection($params);
    $orders = getAllOrders($dbh);
    foreach ($orders as $order) {
        echo "<hr>";
        echo "<ul class='admin__list-orders'>";
        echo "<li>{$order['id']}</li>";
        echo "<li>{$order['user_id']}</li>";
        echo "<li>{$order['street']}</li>";
        echo "<li>{$order['comment']}</li>";
        echo "<li>{$order['payment']}</li>";
        echo "<li>{$order['callback']}</li>";
        echo "</ul>";
        echo "<hr>";
    }
    echo "</div>";
}
?>
</body>
</html>
