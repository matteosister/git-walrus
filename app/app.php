<?php
/**
 * User: matteo
 * Date: 16/08/13
 * Time: 11.54
 * Just for fun...
 */

require_once __DIR__.'/../vendor/autoload.php';

$debug = defined('DEBUG') ? constant('DEBUG') : false;

$app = new \CypressLab\GitElephantRestApi\Application();
$app['debug'] = $debug;

// git elephant
$rootDir = isset($repositoryRoot) ? $repositoryRoot : __DIR__.'/../';
$app['repository'] = \GitElephant\Repository::open($rootDir);
$app['serializer'] = \JMS\Serializer\SerializerBuilder::create()
    ->setDebug($debug)
    ->addMetadataDir(__DIR__.'/serializer')
    ->build();

// providers
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

// routes
$app->get('/', 'CypressLab\GitElephantRestApi\Controller\Main::homepage')->bind('homepage');
$app->get('/tree/{ref}', 'CypressLab\GitElephantRestApi\Controller\Git::tree')->bind('tree');
$app->get('/branches', 'CypressLab\GitElephantRestApi\Controller\Git::branches')->bind('branches');
$app->get('/log', 'CypressLab\GitElephantRestApi\Controller\Git::log')->bind('log');

return $app;
