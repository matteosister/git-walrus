<?php
/**
 * User: matteo
 * Date: 17/08/13
 * Time: 15.56
 * Just for fun...
 */


namespace CypressLab\GitElephantRestApi;


use Symfony\Component\HttpFoundation\JsonResponse;

class JsonRawResponse extends JsonResponse
{
    public function setData($data = array())
    {
        $this->data = $data;

        return $this->update();
    }
}
