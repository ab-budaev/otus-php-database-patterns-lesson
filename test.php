<?php

use Budaev\RowGateway\User;

$PDO = new PDO('mysql:host=$host;dbname=$dbname', 'user', 'password');

$user = new User($PDO);
$user->setFirstName('test');
$user->update();


$activeRecordUser = new \Budaev\ActiveRecord\User($PDO);
$activeRecordUser->setEmail('test@test.com');

$user = (new \Budaev\DataMapper\UserMapper($PDO))->findById(1);
$user->getFirstName();