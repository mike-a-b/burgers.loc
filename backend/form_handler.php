<?php
/*
 * валидация и обработка формы с возвратом значений без перезагрузки страницы
 *
 */
//session_start();
//$user_reg = null;
//if ($_SESSION['id']) {
//    $user_reg = $_SESSION['id'];
//}
//$params = require_once __DIR__. "/config/parameters_db.php";
//require_once __DIR__. "/db_functions.php";

//$data = [];
//$error = false;

/*
 * validation all POST data from form though AJAX and register user
 *
 * order users burger and insert info to DB
 */


//connection to DB
//$dbh = getConnection($params);
//validation email for check authorization
/*if (isset($_POST['email']) || filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) {
    $data['email'] = $_POST['email'];
} elseif ($user_reg = checkUserEmail($dbh, $data['email'])) {
    $_SESSION['id'] = $user_reg;
    $_SESSION['email'] = $data['email'];
} else {
    $user_reg = null;
    $_SESSION['id'] = null;
    $user_reg = registerUser($dbg, $data['email']); //todo function and validate data;
}*/
$data['email'] = $_POST['email'];
$data['name'] = $_POST['name'];
$data['street'] =$_POST['street'];
$data['phone'] =$_POST['phone'];
$data['home'] =$_POST['home'];
//$data = list($email, $name, $streat, $home, $error);
$data = json_encode($data);

//response data and send and set message of buy burgers
return $data;
