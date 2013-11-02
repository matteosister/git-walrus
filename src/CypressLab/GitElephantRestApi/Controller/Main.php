<?php
/**
 * User: matteo
 * Date: 16/08/13
 * Time: 12.05
 * Just for fun...
 */

namespace CypressLab\GitElephantRestApi\Controller;

use Symfony\Component\HttpFoundation\Request;
use CypressLab\GitElephantRestApi\Application;

/**
 * Class Main
 *
 * main controller
 */
class Main
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request  $request
     * @param \CypressLab\GitElephantRestApi\Application $app
     *
     * @return string
     */
    public function homepage(Request $request, Application $app)
    {
        $links = [
            'tree' => $app->url('tree', ['ref' => '{ref}']),
            'log' => $app->url('log'),
            'branches' => $app->url('branches')
        ];

        return $app->json(
            array_map(
                function ($v) {
                    return urldecode($v);
                },
                $links
            )
        );
    }
}
