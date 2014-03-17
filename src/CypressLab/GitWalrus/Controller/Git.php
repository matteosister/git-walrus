<?php
/**
 * User: matteo
 * Date: 16/08/13
 * Time: 12.11
 * Just for fun...
 */

namespace CypressLab\GitWalrus\Controller;

use Swagger\Annotations as SWG;
use CypressLab\GitWalrus\Application;
use GitElephant\Objects\Tree;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Git controller
 *
 * @SWG\Resource(
 *     description="Git actions",
 *     apiVersion="1.0",
 *     swaggerVersion="1.2",
 *     resourcePath="/git",
 *     basePath="/api/git"
 * )
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
     *
     * @SWG\Api(
     *   path="/log/{ref}",
     *   @SWG\Operation(
     *     method="GET",
     *     summary="log for a given tree",
     *     type="Log",
     *     @SWG\Parameters(
     *       @SWG\Parameter(
     *         name="ref",
     *         description="reference",
     *         paramType="path",
     *         required=true,
     *         type="string",
     *         defaultValue="master"
     *       )
     *     )
     *   )
     * )
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
     *
     * @SWG\Api(
     *   path="/commit/{sha}",
     *   @SWG\Operation(
     *     method="GET",
     *     summary="single commit",
     *     type="Commit",
     *     @SWG\Parameters(
     *       @SWG\Parameter(
     *         name="sha",
     *         description="reference",
     *         paramType="path",
     *         required=true,
     *         type="string",
     *         defaultValue=""
     *       )
     *     )
     *   )
     * )
     */
    public function commit(Application $app, $sha)
    {
        return $app->rawJson($app->serialize($app->getRepository()->getCommit($sha), 'json'));
    }

    /**
     * @param Application $app
     *
     * @return \CypressLab\GitWalrus\HttpFoundation\JsonRawResponse
     *
     * @SWG\Api(
     *   path="/branches",
     *   @SWG\Operation(
     *     method="GET",
     *     summary="lists branches",
     *     type="Branch"
     *   )
     * )
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
     *
     * @SWG\Api(
     *   path="/branch/{name}",
     *   @SWG\Operation(
     *     method="GET",
     *     summary="single branch",
     *     type="Branch",
     *     @SWG\Parameters(
     *       @SWG\Parameter(
     *         name="name",
     *         description="name of the branch",
     *         paramType="path",
     *         required=true,
     *         type="string",
     *         defaultValue="master"
     *       )
     *     )
     *   )
     * )
     */
    public function branch(Application $app, $name)
    {
        $branch = $app->getRepository()->getBranch($name);
        return $app->rawJson($app->serialize($branch, 'json'));
    }

    /**
     * @param Application $app
     * @param string      $ref
     * @param null        $path
     *
     * @return \CypressLab\GitWalrus\HttpFoundation\JsonRawResponse
     *
     * @SWG\Api(
     *   path="/tree/{ref}",
     *   @SWG\Operation(
     *     method="GET",
     *     summary="tree",
     *     type="Tree",
     *     @SWG\Parameters(
     *       @SWG\Parameter(
     *         name="ref",
     *         description="reference",
     *         paramType="path",
     *         required=true,
     *         type="string",
     *         defaultValue="master"
     *       )
     *     )
     *   )
     * )
     * @SWG\Api(
     *   path="/tree/{ref}/{path}",
     *   @SWG\Operation(
     *     method="GET",
     *     summary="tree for a given path",
     *     type="Commit",
     *     @SWG\Parameters(
     *       @SWG\Parameter(
     *         name="ref",
     *         description="reference",
     *         paramType="path",
     *         required=true,
     *         type="string",
     *         defaultValue="master"
     *       ),
     *       @SWG\Parameter(
     *         name="path",
     *         description="path of the tree root",
     *         paramType="path",
     *         required=true,
     *         type="string",
     *         defaultValue="src"
     *       )
     *     )
     *   )
     * )
     */
    public function tree(Application $app, $ref, $path = null)
    {
        $tree = $app->getRepository()->getTree($ref, $path);
        return $app->rawJson($app->serialize($tree, 'json', $app['serializer.list_context']));
    }

    /**
     * @param Application                               $app
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \CypressLab\GitWalrus\HttpFoundation\JsonRawResponse
     *
     * @SWG\Api(
     *   path="/status/index",
     *   @SWG\Operation(
     *     method="GET",
     *     summary="index status"
     *   ),
     *   @SWG\Operation(
     *     method="PUT",
     *     summary="update the index (stage)",
     *     @SWG\Parameters(
     *       @SWG\Parameter(
     *         name="data",
     *         description="expression to match files",
     *         paramType="body",
     *         required=true,
     *         type="string",
     *         defaultValue=""
     *       )
     *     )
     *   )
     * )
     */
    public function index(Application $app, Request $request)
    {
        if ('PUT' == $request->getMethod()) {
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
     *
     * @SWG\Api(
     *   path="/status/working-tree",
     *   @SWG\Operation(
     *     method="GET",
     *     summary="working tree status"
     *   ),
     *   @SWG\Operation(
     *     method="PUT",
     *     summary="update the working tree (unstage)",
     *     @SWG\Parameters(
     *       @SWG\Parameter(
     *         name="data",
     *         description="expression to match files",
     *         paramType="body",
     *         required=true,
     *         type="string",
     *         defaultValue=""
     *       )
     *     )
     *   )
     * )
     */
    public function workingTree(Application $app, Request $request)
    {
        if ('PUT' == $request->getMethod()) {
            $file = json_decode($request->getContent());
            $app->getRepository()->unstage($file->name);
            return new Response();
        }
        $status = $app->getRepository()->getWorkingTreeStatus();
        return $app->rawJson($app->serialize($status, 'json', $app['serializer.list_context']));
    }
}
