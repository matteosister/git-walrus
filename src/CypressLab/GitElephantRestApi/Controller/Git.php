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
}
