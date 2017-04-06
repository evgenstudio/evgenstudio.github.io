<?php

$recepient = "123@mail.ru";
$sitename = "Навесы";

$name = trim($_POST["name"]);
$phone = trim($_POST["phone"]);
$email = trim($_POST["email"]);

$pagetitle = "Новая заявка с сайта  \"$sitename\"";
$message = "Имя: $name \nТелефон: $phone \nE-mail: $email";
mail($recepient, $pagetitle, $message,);