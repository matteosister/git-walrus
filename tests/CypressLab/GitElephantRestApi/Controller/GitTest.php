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

/**
 * Class GitTest
 *
 * @group controller
 * @group controller-git
 */
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
        $this->countItems($client, 0);
        $this->addFile('test');
        $this->commit();
        $client->request('get', '/branches');
        $this->countItems($client, 1);
        $this->createBranch('test');
        $client->request('get', '/branches');
        $this->countItems($client, 2);
    }

    public function testTree()
    {
        $this->addFile('test');
        $this->commit();
        $client = $this->createClient();
        $client->request('get', '/tree/master');
        $this->isJsonResponse($client);
        $result = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('root', $result);
        $this->assertTrue($result['root']);
        $this->assertArrayHasKey('children', $result);
        $this->assertCount(1, $result['children']);
        $this->assertArrayHasKey('path_children', $result);
        $this->assertContains('test', $result['path_children']);
        $this->assertEquals('test', $result['children'][0]['name']);
        $this->assertStringEndsWith('test', $result['children'][0]['url']);
    }
}
