<?php
/**
 * User: matteo
 * Date: 16/08/13
 * Time: 11.54
 * Just for fun...
 */

require_once __DIR__.'/../vendor/autoload.php';

$app = new \CypressLab\GitElephantRestApi\Application();

// providers
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

// routes
$app->get('/', 'CypressLab\GitElephantRestApi\Controller\Main::homepage')->bind('homepage');
$app->get('/log', 'CypressLab\GitElephantRestApi\Controller\Git::log')->bind('log');

return $app;