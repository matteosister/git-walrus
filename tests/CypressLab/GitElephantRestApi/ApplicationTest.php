<?php
/**
 * User: matteo
 * Date: 02/11/13
 * Time: 23.44
 * Just for fun...
 */

namespace CypressLab\GitElephantRestApi;

use CypressLab\WebTestCase;

class ApplicationTest extends WebTestCase
{
    public function testRawJson()
    {
        $app = $this->createApplication();
        $this->assertInstanceOf('CypressLab\GitElephantRestApi\HttpFoundation\JsonRawResponse', $app->rawJson('[]'));
    }
}
