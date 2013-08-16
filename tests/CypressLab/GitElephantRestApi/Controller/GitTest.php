<?php
/**
 * User: matteo
 * Date: 16/08/13
 * Time: 13.16
 * Just for fun...
 */

namespace CypressLab\GitElephantRestApi\Controller;

use CypressLab\WebTestCase;
use Symfony\Component\HttpKernel\HttpKernel;

class GitTest extends WebTestCase
{
    public function testLog()
    {
        $client = $this->createClient();
        $client->request('get', '/log');
        $this->isJsonResponse($client);
    }
}