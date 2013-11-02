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
        $this->addFile('test');
        $this->commit();
        $client = $this->createClient();
        $client->request('get', '/log');
        $this->isJsonResponse($client);
        $this->countItems($client, 1);
        $this->addFile('test2');
        $this->commit();
        $client->request('get', '/log');
        $this->countItems($client, 2);
    }

    public function testBranch()
    {
        $client = $this->createClient();
        $client->request('get', '/branches');
        $this->isJsonResponse($client);
    }
}
