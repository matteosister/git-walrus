<?php
/**
 * User: matteo
 * Date: 08/11/13
 * Time: 23.01
 * Just for fun...
 */

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use CypressLab\GitElephantRestApi\Command\ServerRunCommand;

$app = new Application();
$app->add(new ServerRunCommand());
$app->run();
