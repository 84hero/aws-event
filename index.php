<?php
require_once './vendor/autoload.php';

$Event = new AwsEvent('aws_sns_notify_token');
$Event->response();

