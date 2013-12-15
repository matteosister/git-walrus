<?php
/**
 * User: matteo
 * Date: 17/08/13
 * Time: 15.56
 * Just for fun...
 */


namespace CypressLab\GitWalrus\HttpFoundation;


use Symfony\Component\HttpFoundation\JsonResponse;

class JsonRawResponse extends JsonResponse
{
    /**
     * @param array $data
     *
     * @return JsonResponse
     */
    public function setData($data = array())
    {
        $this->data = $data;

        return $this->update();
    }
}
