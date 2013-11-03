<?php
/**
 * User: matteo
 * Date: 16/08/13
 * Time: 12.23
 * Just for fun...
 */

namespace CypressLab\GitElephantRestApi;

use CypressLab\GitElephantRestApi\Controller\Traits\ControllerTrait;
use CypressLab\GitElephantRestApi\HttpFoundation\JsonRawResponse;
use GitElephant\Repository;
use Silex\Application as SilexApplication;

class Application extends SilexApplication
{
    use SilexApplication\UrlGeneratorTrait;

    /**
     * Convert some data into a JSON response.
     *
     * @param string  $data    The response data
     * @param integer $status  The response status code
     * @param array   $headers An array of response headers
     *
     * @return JsonRawResponse
     */
    public function rawJson($data, $status = 200, $headers = array())
    {
        return new JsonRawResponse($data, $status, $headers);
    }

    /**
     * @return Repository
     */
    public function getRepository()
    {
        return $this['repository'];
    }

    /**
     * @param mixed  $data
     * @param string $format
     * @param null   $context
     *
     * @return mixed
     */
    public function serialize($data, $format, $context = null)
    {
        return $this['serializer']->serialize($data, $format, $context);
    }
}
