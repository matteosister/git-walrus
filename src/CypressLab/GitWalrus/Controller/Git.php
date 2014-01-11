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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param Application                               $app
     * @param string                                    $ref
     * @param int                                       $num
     *
     * @return \CypressLab\GitWalrus\HttpFoundation\JsonRawResponse
     */
    public function log(Request $request, Application $app, $ref, $num = 8)
    {
        if (null !== $context = $request->get('context', null)) {
            $app['serializer.list_context.names'] = array_merge(
                $app['serializer.list_context.names'],
                [$context]
            );
        }
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

    /**
     * @param Application                               $app
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \CypressLab\GitWalrus\HttpFoundation\JsonRawResponse
     */
    public function index(Application $app, Request $request)
    {
        if ('POST' == $request->getMethod()) {
            $file = json_decode($request->getContent());
            $app->getRepository()->stage($file->name);
            return new Response();
        }
        $status = $app->getRepository()->getIndexStatus();
        return $app->rawJson($app->serialize($status, 'json', $app['serializer.list_context']));
    }

    /**
     * @param Application                               $app
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \CypressLab\GitWalrus\HttpFoundation\JsonRawResponse
     */
    public function workingTree(Application $app, Request $request)
    {
        if ('POST' == $request->getMethod()) {
            $file = json_decode($request->getContent());
            $app->getRepository()->unstage($file->name);
            return new Response();
        }
        $status = $app->getRepository()->getWorkingTreeStatus();
        return $app->rawJson($app->serialize($status, 'json', $app['serializer.list_context']));
    }
}
