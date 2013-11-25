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
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));
$lexer = new Twig_Lexer($app['twig'], array(
    'tag_comment'  => array('<%#', '%>'),
    'tag_block'    => array('<%', '%>'),
    'tag_variable' => array('<%=', '%>'),
));
$app['twig']->setLexer($lexer);

// routes
/** @var \Silex\ControllerCollection $api */
$api = $app['controllers_factory'];
$api->get('/', 'CypressLab\GitElephantRestApi\Controller\Main::api')->bind('api');
$api->get('/tree/{ref}', 'CypressLab\GitElephantRestApi\Controller\Git::tree')
    ->bind('tree')
    ->value('ref', 'master');
$api->get('/tree/{ref}/{path}', 'CypressLab\GitElephantRestApi\Controller\Git::treeObject')
    ->bind('tree_object')
    ->assert('path', '.+');
$api->get('/branches', 'CypressLab\GitElephantRestApi\Controller\Git::branches')->bind('branches');
$api->get('/branch/{name}', 'CypressLab\GitElephantRestApi\Controller\Git::branch')->bind('branch');
$api->get('/log/{ref}', 'CypressLab\GitElephantRestApi\Controller\Git::log')
    ->bind('log')
    ->value('ref', 'master');
$app->mount('api', $api);
$app->get('/', 'CypressLab\GitElephantRestApi\Controller\Main::homepage')->bind('homepage');
$app->match('{url}', 'CypressLab\GitElephantRestApi\Controller\Main::homepage')->assert('url', '.+');
return $app;
