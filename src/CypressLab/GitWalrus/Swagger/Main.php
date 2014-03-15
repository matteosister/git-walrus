<?php
/**
 * @author Matteo Giachino <matteog@gmail.com>
 */


namespace CypressLab\GitWalrus\Swagger;

use CypressLab\GitWalrus\Application;
use Swagger\Swagger;
use Symfony\Component\HttpFoundation\Response;

class Main
{
    public function index(Application $app)
    {
        $swagger = new Swagger(__DIR__.'/../Controller');
        $r = new Response($swagger->getResource('/', ['output' => 'json']));
        return $r;
    }
}
