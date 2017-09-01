<?php
/*
 * валидация и обработка формы с возвратом значений без перезагрузки страницы
 *
 */
session_start();

if (isset($_SESSION['id'])) {
    $user_reg = $_SESSION['id'];
} else {
    $user_reg = null;
    $_SESSION['id'] = null;
}


$data = [];
$data['error'] = false;

/*
 * validation all POST data from though AJAX and register user
 *
 * order users burger and insert info to DB
 *
 */

function incorrect_value(&$data, $error_msg)
{
    $data['error'] = $error_msg;
    echo http_response_code(422);
}

$options_name = [
    'options' => [
        'regexp' => "|[A-Za-zА-Яа-я\s]+|u"
    ]
];

/*$option_phone = [
    'options' => [
        'regexp' => "|([0-9]){10}|u"
    ]
];*/

$options_home_and_other_int = [
    'options' => [
        'min_range' => 1,
        'max_range' => 100,
        'default' => 1
    ]
];

//проверка обязательных полей формы
if (isset($_POST['email'])) {
    $data['email'] =
        filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
} elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    incorrect_value($data, "Некорректный email, повторите ввод.");
}

if (isset($_POST['name'])) {
    $data['name'] = filter_var($_POST['name'], FILTER_VALIDATE_REGEXP, $options_name);
} else {
    incorrect_value($data, "Некорректное имя повторите ввод.");
}

if (isset($_POST['phone'])) {
    $data['phone'] = trim(preg_replace('~\s+~s', '', $_POST['phone']));
} elseif (!filter_var($_POST['phone'], FILTER_DEFAULT)) {
    incorrect_value($data, "Недопустимые символы в телефоне. Введите заного.");
}

if (isset($_POST['street'])) {
    $data['street'] = htmlspecialchars(strip_tags($_POST['street']));
} elseif (!filter_var($_POST['street'], FILTER_DEFAULT)) {
    incorrect_value($data, "Улица содержит недопустимые символы. Повторите ввод.");
}

//необязательные поля валидация и склейка в street
if (isset($_POST['home'])) {
    $data['home'] = intval($_POST['home']);
} elseif (!filter_var($data['home'], FILTER_VALIDATE_INT, $options_home_and_other_int)) {
    incorrect_value($data, "Недопустимое значение дома");
}
if (isset($_POST['part'])) {
    $data['part'] = intval($_POST['part']);
} elseif (!filter_var($data['part'], FILTER_VALIDATE_INT, $options_home_and_other_int)) {
    incorrect_value($data, "Повторите ввод корпуса");
}

if (isset($_POST['appt'])) {
    $data['appt'] = intval($_POST['appt']);
} elseif (!filter_var($data['part'], FILTER_VALIDATE_INT, $options_home_and_other_int)) {
    incorrect_value($data, "Повторите ввод квартиры");
}

if (isset($_POST['floor'])) {
    $data['floor'] = intval($_POST['floor']);
} elseif (!filter_var($data['floor'], FILTER_VALIDATE_INT, $options_home_and_other_int)) {
    incorrect_value($data, "Повторите ввод этажа");
}
//клеим в street
$array_for_street = [$data['street'], $data['home'], $data['part'], $data['appt'], $data['floor']];
$data['street'] = implode(', ', $array_for_street);

//валидация коммента, радио кнопок и чекбокса не перезванивать
if (isset($_POST['comment'])) {
    $data['comment'] = htmlspecialchars(strip_tags($_POST['comment']));
} elseif (!filter_var($data['comment'], FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
    incorrect_value($data, "Недопустимые символы в поле комментарий");
}
//check radio
if (isset($_POST['payment'])) {
    switch ($_POST['payment']) {
        case "need_cashback":
            $data['payment'] = "need_cashback";
            break;
        case "pay_with_card":
            $data['payment'] = "pay_with_card";
            break;
        default:
            $data['payment'] = "need_cashback";
            break;
    }
} else {
    $data['payment'] = "need_cashback";
}

//check checkbox with unstate status too
$data['callback'] = isset($_POST['callback']) ? 1 : 0;

//main functional
$params = require(__DIR__. "/config/parameters_db.php");
require_once __DIR__ . '/../backend/db_functions.php';
//connection to DB
$dbh = getConnection($params);
//set session vars if valid $data['email'] for authorization or register new user , and insert order
$data['user_id'] = checkUserEmail($dbh, $data['email']);
if ($data['user_id']) {
    $data['order_id'] = registerNewOrder($dbh, $data);
    $data['count'] = getCountOrders($dbh, $data['user_id']);
    $_SESSION['id'] = $data['user_id'];
    $_SESSION['email'] = $data['email'];
    $_SESSION['count'] = $data['count'];
} else {
    $data['user_id'] = registerUser($dbh, [$data['email'], $data['name'], $data['phone']]);
    $data['order_id'] = registerNewOrder($dbh, $data);
    $data['count'] = getCountOrders($dbh, $data['user_id']);
    $_SESSION['id'] = $data['user_id'];
    $_SESSION['email'] = $data['email'];
    $_SESSION['count'] = $data['count'];
}
closeConnection($dbh);

$data = json_encode($data);
//response data and send and set message of buy burgers
echo $data;
