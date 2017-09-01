<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 11.08.17
 * Time: 18:30
 */
$params = require(__DIR__ . '/../backend/config/parameters_db.php');

function getConnection(array $params)
{
    try {
        $dsn = "mysql:host=" . $params['host'] . ";dbname=" . $params['dbname'];
        $dbh = new PDO($dsn, $params['user'], $params['password']);
//        $dbh = @new PDO($dsn, "root", "root");
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        print "Ошибка!: " . $e->getMessage() . "<br/>";
//        file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND);
        $handle = fopen('PDOErrors.txt', 'a+');
        fwrite($handle, $e->getMessage().PHP_EOL);
        fclose($handle);
        die("Ошибка при подключении к БД или SQL синтаксиса");
    }
    $dbh->exec("set names utf8");
    $dbh->exec("use burgers");
    return $dbh;
}

function closeConnection(PDO &$dbh)
{
    $dbh = null;
}

function getAllRegisterUsers(PDO &$dbh)
{
    $sth = $dbh->query("select * from users");
    $sth->setFetchMode(PDO::FETCH_ASSOC);
    $data = $sth->fetchAll();

    return $data;
}

function getAllOrders(PDO &$dbh)
{
    $sth = $dbh->query("select * from orders");
    $sth->setFetchMode(PDO::FETCH_ASSOC);
    $data = $sth->fetchAll();

    return $data;
}

function getCountOrders(PDO &$dbh, $user_id) {
    $sth = $dbh->prepare("select count(*) from orders where orders.user_id = ?");
    $sth->setFetchMode(PDO::FETCH_ASSOC);
    $sth->bindParam(1, $user_id);
    $res = $sth->execute();
    $count = (int)$sth->fetchColumn();
    return $count;
}

function checkUserEmail(PDO &$dbh, $email)
{
    $sql = "select id, email from users where email = :email";
    $sth = $dbh->prepare($sql);
    $sth->setFetchMode(PDO::FETCH_ASSOC);
    $sth->execute(array(":email" => $email));
    $data = $sth->fetchAll();
    if (isset($data[0]['email'])) {
        return $data[0]['id'];
    } else {
        return false;
    }
//    return false;
}

function registerUser(PDO &$dbh, array $data)
{
    $ar_data = [$data[0], $data[1], $data[2]];
    $sql = "insert into users (email, name,  phone) values (?, ?, ?)";
    $sth = $dbh->prepare($sql);

    $sth->execute($ar_data);/*array(':email' => $data['email'], ':name' => $data['name'],
        ':phone' => $data['phone'])*/
    $user_id = checkUserEmail($dbh, $data[0]);
    return $user_id;
}

function registerNewOrder(PDO &$dbh, &$data)
{
    $ar_data = [$data['user_id'], $data['street'], $data['comment'], $data['payment'], $data['callback']];
    $sql = "insert into orders (user_id, street, comment, payment, callback) values (?, ?, ?, ?, ?)";
    $sth = $dbh->prepare($sql);
    $sth->execute($ar_data);
    return $dbh->lastInsertId();
}
