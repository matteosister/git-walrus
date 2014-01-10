<?php
/**
 * User: matteo
 * Date: 16/08/13
 * Time: 11.54
 * Just for fun...
 */

$debug = $debug ?: false;
$repositoryRoot = $repositoryRoot ?: __DIR__.'/../';
$serializerDir = $serializerDir ?: __DIR__.'/serializer';
$twigViewsDir = $twigViewsDir ?: __DIR__.'/views';

$app = new \CypressLab\GitWalrus\Application();
$app['debug'] = $debug;

// git elephant
$app['repository'] = \GitElephant\Repository::open($repositoryRoot);
$app['serializer'] = \JMS\Serializer\SerializerBuilder::create()
    ->setDebug($debug)
    ->addMetadataDir($serializerDir)
    ->configureListeners(function (\JMS\Serializer\EventDispatcher\EventDispatcher $dispatcher) use ($app) {
        $dispatcher->addSubscriber(new \CypressLab\GitWalrus\Event\SerializerSubscriber($app));
    })
    ->build();
$app['serializer.list_context.names'] = ['list'];
$app['serializer.list_context'] = function () use ($app) {
    return \JMS\Serializer\SerializationContext::create()
        ->setGroups($app['serializer.list_context.names'])
        ->setSerializeNull(true);
};

// providers
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => $twigViewsDir,
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
$api->get('/', 'CypressLab\GitWalrus\Controller\Main::api')
    ->bind('api');
$api->get('/tree/{ref}', 'CypressLab\GitWalrus\Controller\Git::tree')
    ->bind('tree')
    ->value('ref', 'master');
$api->get('/tree/{ref}/{path}', 'CypressLab\GitWalrus\Controller\Git::treeObject')
    ->bind('tree_object')
    ->assert('path', '\S+');
$api->get('/branches', 'CypressLab\GitWalrus\Controller\Git::branches')
    ->bind('branches');
$api->get('/branch/{name}', 'CypressLab\GitWalrus\Controller\Git::branch')
    ->bind('branch');
$api->get('/log/{ref}', 'CypressLab\GitWalrus\Controller\Git::log')
    ->bind('log')
    ->value('ref', 'master');
$api->get('/commit/{sha}', 'CypressLab\GitWalrus\Controller\Git::commit')
    ->bind('commit')
    ->value('ref', 'master');
$api->get('/status/index', 'CypressLab\GitWalrus\Controller\Git::indexStatus')
    ->bind('status_index');
$api->get('/status/working-tree', 'CypressLab\GitWalrus\Controller\Git::workingTreeStatus')
    ->bind('status_working_tree');
$app->mount('api', $api);
$app->get('/', 'CypressLab\GitWalrus\Controller\Main::homepage')->bind('homepage');
$app->get('/git-walrus.css', 'CypressLab\GitWalrus\Controller\Assets::css')->bind('css');
$app->get('/git-walrus.js', 'CypressLab\GitWalrus\Controller\Assets::js')->bind('js');
$app->get('/partial/{name}', 'CypressLab\GitWalrus\Controller\Assets::partial')->bind('partial');
$app->match('{url}', 'CypressLab\GitWalrus\Controller\Main::homepage')->assert('url', '.+');
return $app;
