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
    ->configureListeners(function (\JMS\Serializer\EventDispatcher\EventDispatcher $dispatcher) use ($app) {
        $dispatcher->addSubscriber(new \CypressLab\GitElephantRestApi\Event\SerializerSubscriber($app));
    })
    ->build();

// providers
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

// routes
$app->get('/', 'CypressLab\GitElephantRestApi\Controller\Main::homepage')->bind('homepage');
$app->get('/tree/{ref}', 'CypressLab\GitElephantRestApi\Controller\Git::tree')->bind('tree');
$app->get('/tree/{ref}/{path}', 'CypressLab\GitElephantRestApi\Controller\Git::treeObject')
    ->bind('tree_object')
    ->assert('path', '.+');
$app->get('/branches', 'CypressLab\GitElephantRestApi\Controller\Git::branches')->bind('branches');
$app->get('/log', 'CypressLab\GitElephantRestApi\Controller\Git::log')->bind('log');

return $app;
