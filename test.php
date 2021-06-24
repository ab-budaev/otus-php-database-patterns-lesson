<?php

use Budaev\TableGateway\User;

include 'vendor/autoload.php';

$userGateway = new User(new PDO('mysql:host=$host;dbname=$dbname', 'user', 'password'));

$user = $userGateway->getOneById(1);

print_r($user);
