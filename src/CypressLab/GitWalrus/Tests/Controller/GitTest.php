<?php
/**
 * User: matteo
 * Date: 16/08/13
 * Time: 13.16
 * Just for fun...
 */

namespace CypressLab\GitWalrus\Tests\Controller;

use CypressLab\GitWalrus\Tests\WebTestCase;

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
        $client->request('get', '/api/log');
        $this->isJsonResponse($client);
        $this->countItems($client, 1, 'commits');
        $this->addFile('test2');
        $this->commit();
        $client->request('get', '/api/log');
        $this->isJsonResponse($client);
        $this->countItems($client, 2, 'commits');
        $this->createBranch('develop');
        $client->request('get', '/api/log/develop');
        $this->isJsonResponse($client);
        $this->countItems($client, 2, 'commits');
        $this->checkout('develop');
        $this->addFile('test3');
        $this->commit();
        $client->request('get', '/api/log/develop');
        $this->isJsonResponse($client);
        $this->countItems($client, 3, 'commits');
        $client->request('get', '/api/log/master');
        $this->isJsonResponse($client);
        $this->countItems($client, 2, 'commits');
        $jsonContent = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayNotHasKey('diff', $jsonContent['commits'][0]);
    }

    public function testCommit()
    {
        $this->addFile('test');
        $this->commit('test message');
        $log = $this->getRepo()->getLog('HEAD', null, 1);
        $lastCommit = $log[0];
        $client = $this->createClient();
        $url = '/api/commit/'.$lastCommit->getSha();
        $client->request('get', $url);
        $this->isJsonResponse($client);
        $jsonResponse = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($lastCommit->getSha(), $jsonResponse['ref']);
        $this->assertEquals('test message', $jsonResponse['message']);
        $this->assertArrayHasKey('diff', $jsonResponse);
    }

    public function testBranches()
    {
        $client = $this->createClient();
        $client->request('get', '/api/branches');
        $this->isJsonResponse($client);
        $this->countItems($client, 0);
        $this->addFile('test');
        $this->commit();
        $client->request('get', '/api/branches');
        $this->countItems($client, 1);
        $this->createBranch('test');
        $client->request('get', '/api/branches');
        $this->countItems($client, 2);
    }

    public function testBranch()
    {
        $this->addFile('test');
        $this->commit();
        $client = $this->createClient();
        $client->request('get', '/api/branch/master');
        $this->isJsonResponse($client);
        $result = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('master', $result['name']);
        $this->assertEquals($this->getRepo()->getBranch('master')->getSha(), $result['sha']);
        $this->assertTrue($result['current']);
        $this->assertEquals('commit automatic test message', $result['comment']);
        $this->createBranch('test-branch');
        $client->request('get', '/api/branch/test-branch');
        $this->isJsonResponse($client);
        $result = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('test-branch', $result['name']);
        $this->assertEquals($this->getRepo()->getBranch('test-branch')->getSha(), $result['sha']);
        $this->assertFalse($result['current']);
        $this->assertEquals('commit automatic test message', $result['comment']);
    }

    public function testTree()
    {
        $this->addFile('test');
        $this->commit();
        $client = $this->createClient();
        $client->request('get', '/api/tree/master');
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

        $client->request('get', '/api/tree');
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

    public function testTreeObject()
    {
        $this->addFile('test');
        $this->addFile('.test');
        $this->commit();
        $client = $this->createClient();
        $client->request('get', '/api/tree/master/test');
        $this->isJsonResponse($client);
        $result = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('master', $result['ref']);
        $this->assertEmpty($result['children']);
        $this->assertEmpty($result['path_children']);
        $this->assertEquals(false, $result['root']);
        $this->assertEquals('test content', $result['binary_data']);

        $client->request('get', '/api/tree/master/.test');
        $this->isJsonResponse($client);
    }

    public function testIndexStatus()
    {
        $client = $this->createClient();
        $client->request('get', '/api/status/working-tree');
        $this->isJsonResponse($client);
        $result = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('all', $result);
        $this->assertArrayHasKey('untracked', $result);
        $this->assertArrayHasKey('modified', $result);
        $this->assertArrayHasKey('added', $result);
        $this->assertArrayHasKey('deleted', $result);
        $this->assertArrayHasKey('renamed', $result);
        $this->assertArrayHasKey('copied', $result);
    }

    public function testWorkingTreeStatus()
    {
        $client = $this->createClient();
        $client->request('get', '/api/status/index');
        $this->isJsonResponse($client);
        $result = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('all', $result);
        $this->assertArrayHasKey('untracked', $result);
        $this->assertArrayHasKey('modified', $result);
        $this->assertArrayHasKey('added', $result);
        $this->assertArrayHasKey('deleted', $result);
        $this->assertArrayHasKey('renamed', $result);
        $this->assertArrayHasKey('copied', $result);
    }
}
