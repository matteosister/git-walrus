<?php
/**
 * User: matteo
 * Date: 02/11/13
 * Time: 23.44
 * Just for fun...
 */

namespace CypressLab\GitWalrus\Tests;

class ApplicationTest extends WebTestCase
{
    public function testRawJson()
    {
        $app = $this->createApplication();
        $this->assertInstanceOf('CypressLab\GitWalrus\HttpFoundation\JsonRawResponse', $app->rawJson('[]'));
    }
}
