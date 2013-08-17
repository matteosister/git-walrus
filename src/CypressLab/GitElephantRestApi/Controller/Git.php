<?php
/**
 * User: matteo
 * Date: 16/08/13
 * Time: 12.11
 * Just for fun...
 */

namespace CypressLab\GitElephantRestApi\Controller;

use CypressLab\GitElephantRestApi\Application;
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
    public function log(Request $request, Application $app)
    {
        $log = $app->getRepository()->getLog();
        $commits = iterator_to_array($log);
        //return new JsonRe
        return $app->rawJson($app['serializer']->serialize($commits, 'json'));
    }

    public function branches(Request $request, Application $app)
    {

    }
}