<?php
/**
 * User: matteo
 * Date: 16/08/13
 * Time: 11.54
 * Just for fun...
 */

require_once __DIR__.'/../vendor/autoload.php';

$app = new \CypressLab\GitElephantRestApi\Application();

// git elephant
$app['repository'] = \GitElephant\Repository::open(__DIR__.'/../');
$app['serializer'] = \JMS\Serializer\SerializerBuilder::create()
    ->setDebug(true)
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