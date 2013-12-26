<?php
/**
 * User: matteo
 * Date: 16/08/13
 * Time: 12.11
 * Just for fun...
 */

namespace CypressLab\GitWalrus\Controller;

use CypressLab\GitWalrus\Application;
use GitElephant\Objects\Tree;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Git
 *
 * git controller
 */
class Git
{
    /**
     * @param Application $app
     * @param string      $ref
     * @param int         $num
     *
     * @return \CypressLab\GitWalrus\HttpFoundation\JsonRawResponse
     */
    public function log(Application $app, $ref, $num = 5)
    {
        $log = $app->getRepository()->getLog($ref, null, $num);
        return $app->rawJson($app->serialize($log, 'json', $app['serializer.list_context']));
    }

    /**
     * @param Application $app
     * @param int         $sha
     *
     * @return \CypressLab\GitWalrus\HttpFoundation\JsonRawResponse
     */
    public function commit(Application $app, $sha)
    {
        return $app->rawJson($app->serialize($app->getRepository()->getCommit($sha), 'json'));
    }

    /**
     * @param Application $app
     *
     * @return \CypressLab\GitWalrus\HttpFoundation\JsonRawResponse
     */
    public function branches(Application $app)
    {
        $branches = $app->getRepository()->getBranches();
        return $app->rawJson($app->serialize($branches, 'json', $app['serializer.list_context']));
    }

    /**
     * @param Application $app
     * @param string      $name
     *
     * @return \CypressLab\GitWalrus\HttpFoundation\JsonRawResponse
     */
    public function branch(Application $app, $name)
    {
        $branch = $app->getRepository()->getBranch($name);
        return $app->rawJson($app->serialize($branch, 'json'));
    }

    /**
     * @param Application $app
     * @param string      $ref
     *
     * @return \CypressLab\GitWalrus\HttpFoundation\JsonRawResponse
     */
    public function tree(Application $app, $ref)
    {
        $tree = $app->getRepository()->getTree($ref);
        return $app->rawJson($app->serialize($tree, 'json', $app['serializer.list_context']));
    }

    /**
     * @param Application $app
     * @param string      $ref
     * @param             $path
     *
     * @return \CypressLab\GitWalrus\HttpFoundation\JsonRawResponse
     */
    public function treeObject(Application $app, $ref, $path)
    {
        $tree = $app->getRepository()->getTree($ref, $path);
        return $app->rawJson($app->serialize($tree, 'json', $app['serializer.list_context']));
    }
}
