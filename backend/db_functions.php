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
        die("Ошибка при подключении к БД или SQL синтаксиса");
        file_put_contents('PDOErrors.txt', $e > getMessage(), FILE_APPEND);
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

function checkUserEmail(PDO &$dbh, $email)
{
    $sth = $dbh->query("select :email, id from users where :email = email");
    $sth->setFetchMode(PDO::FETCH_ASSOC);
    bindParam(':email', $email, PDO::PARAM_STR);
    $data = $sth->fetchAll();
    if (isset($data['email'])) {
        return $data['id'];
        //todo Session get user_id and set parameter ?
    } else {
        return false;
    }
//    return false;
}

function registerUser(PDO &$dbh, array $data)
{
    $sql = "insert into burgers (email, name,  phone) values :email, :name, :phone";

    $sth = $dbh->prepare($sql);
    $sth->execute(array(':email' => $data['email'], ':name' => $data['name'],
        ':phone' => $data['phone']));
    if ($user_id = checkUserEmail($dbh, $data['email'])) {
        return $user_id;
    }

}