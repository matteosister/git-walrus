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
        $commits = iterator_to_array($log);
        $results['items'] = $commits;
        return $app->rawJson($app->serialize($results, 'json'));
    }

    /**
     * @param Application $app
     *
     * @return \CypressLab\GitWalrus\HttpFoundation\JsonRawResponse
     */
    public function branches(Application $app)
    {
        $branches = $app->getRepository()->getBranches();
        $results['items'] = $branches;
        return $app->rawJson($app->serialize($results, 'json'));
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
        return $app->rawJson($app->serialize($tree, 'json'));
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
        return $app->rawJson($app->serialize($tree, 'json'));
    }
}
