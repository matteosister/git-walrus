<?php
/**
 * User: matteo
 * Date: 16/08/13
 * Time: 12.11
 * Just for fun...
 */

namespace CypressLab\GitElephantRestApi\Controller;

use CypressLab\GitElephantRestApi\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Git
 *
 * git controller
 */
class Git
{
    public function log(Request $request, Application $app)
    {
        var_dump($app['serializer.normalizers']);die;
        $log = $app->getRepository()->getLog();
        var_dump($app->serialize($log, 'json'));
        var_dump(iterator_to_array($log));
        die;
        return $app->json(iterator_to_array($log));
    }
}