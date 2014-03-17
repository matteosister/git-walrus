<?php
/**
 * @author Matteo Giachino <matteog@gmail.com>
 */


namespace CypressLab\GitWalrus\Swagger;

use CypressLab\GitWalrus\Application;
use Swagger\Swagger;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Main
{
    public function index(Application $app)
    {
        $swagger = new Swagger(__DIR__.'/../Controller');
        return new JsonResponse($swagger->getResourceList([]));
    }

    public function git()
    {
        $swagger = new Swagger(__DIR__.'/../Controller');
        return new Response($swagger->getResource('/git', ['output' => 'json']));
    }
}
