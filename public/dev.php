<?php
/**
 * User: matteo
 * Date: 16/08/13
 * Time: 11.53
 * Just for fun...
 */


$app = require_once __DIR__.'/../app/app.php';


$app['debug'] = true;
$app->run();