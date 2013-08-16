<?php
/**
 * User: matteo
 * Date: 16/08/13
 * Time: 13.22
 * Just for fun...
 */


namespace CypressLab;

use Silex\WebTestCase as SilexWebTestCase;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\HttpKernel\HttpKernel;

class WebTestCase extends SilexWebTestCase
{
    protected function isJsonResponse(Client $client)
    {
        $this->assertEquals('application/json', $client->getResponse()->headers->get('content-type'));
    }

    /**
     * Creates the application.
     *
     * @return HttpKernel
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../../app/app.php';
        $app['debug'] = true;
        $app['exception_handler']->disable();

        return $app;
    }
}