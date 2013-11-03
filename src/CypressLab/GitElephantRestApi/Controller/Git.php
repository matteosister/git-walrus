<?php
/**
 * User: matteo
 * Date: 16/08/13
 * Time: 12.11
 * Just for fun...
 */

namespace CypressLab\GitElephantRestApi\Controller;

use CypressLab\GitElephantRestApi\Application;
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
     *
     * @return \CypressLab\GitElephantRestApi\HttpFoundation\JsonRawResponse
     */
    public function log(Application $app)
    {
        $log = $app->getRepository()->getLog();
        $commits = iterator_to_array($log);
        $results['items'] = $commits;
        return $app->rawJson($app->serialize($results, 'json'));
    }

    /**
     * @param Application $app
     *
     * @return \CypressLab\GitElephantRestApi\HttpFoundation\JsonRawResponse
     */
    public function branches(Application $app)
    {
        $branches = $app->getRepository()->getBranches();
        $results['items'] = $branches;
        return $app->rawJson($app->serialize($results, 'json'));
    }

    /**
     * @param Application $app
     * @param string      $ref
     *
     * @return \CypressLab\GitElephantRestApi\HttpFoundation\JsonRawResponse
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
     * @return \CypressLab\GitElephantRestApi\HttpFoundation\JsonRawResponse
     */
    public function treeObject(Application $app, $ref, $path)
    {
        $tree = $app->getRepository()->getTree($ref, $path);
        return $app->rawJson($app->serialize($tree, 'json'));
    }
}
