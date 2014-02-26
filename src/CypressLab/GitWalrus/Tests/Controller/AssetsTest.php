<?php
/**
 * User: matteo
 * Date: 16/08/13
 * Time: 13.16
 * Just for fun...
 */

namespace CypressLab\GitWalrus\Controller;

use CypressLab\GitWalrus\Tests\WebTestCase;

class AssetsTest extends WebTestCase
{
    public function testCss()
    {
        $client = $this->createClient();
        $client->request('get', '/git-walrus.css');
        $this->isOK($client);
        $this->isContentTypeResponse($client, 'text/css; charset=UTF-8');
    }

    public function testJs()
    {
        $client = $this->createClient();
        $client->request('get', '/git-walrus.js');
        $this->isOK($client);
        $this->isContentTypeResponse($client, 'text/javascript; charset=UTF-8');
    }

    public function testPartial()
    {
        $client = $this->createClient();
        $client->request('get', '/partial/homepage.html');
        $this->assertNotEmpty($client->getResponse()->getContent());
    }
}
