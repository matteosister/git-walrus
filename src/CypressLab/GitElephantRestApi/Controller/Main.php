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
     * @param \CypressLab\GitElephantRestApi\Application $app
     *
     * @return string
     */
    public function homepage(Application $app)
    {
        $links = [
            'tree' => $app->url('tree', ['ref' => '{ref}']),
            'log' => $app->url('log', ['ref' => '{ref}']),
            'branches' => $app->url('branches'),
            'branch' => $app->url('branch', ['name' => '{name}'])
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
