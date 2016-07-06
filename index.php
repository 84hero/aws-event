<?php
require_once './vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$Event = new AwsEvent('aws_sns_notify_token');
$Event->response();

