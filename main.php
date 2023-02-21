<?php
header('Content-type: application/json; charset=utf-8');

$arResult = [];
$arResult['success'] = true;

if ($arResult['success']) {
    require_once($_SERVER['DOCUMENT_ROOT'].'/mail.php');

    $email = $_REQUEST['email'];
    $msg = $_REQUEST['message'];

    $to = 'pwplains911@gmail.com';
    $from = 'no-reply@test.com';
    $subject = 'Новое сообщение с формы сайта ...';

    $message = '';

    $params = [
        'E-mail' => $email,
        'Сообщение' => $msg,
    ];

    array_walk($params, function($val, $key) use (&$message) {
        $message.= '<b>'.$key.'</b>: '.$val.'<br>';
    });

    $mail = new Mail($to, $from, $from, $subject, $message);

    $mail->setHTML(true);
    $mail->send();
}

echo json_encode($arResult);
?>
