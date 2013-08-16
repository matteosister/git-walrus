<?php
/**
 * User: matteo
 * Date: 16/08/13
 * Time: 13.49
 * Just for fun...
 */


namespace CypressLab\GitElephantRestApi;

/**
 * Trait SerializerTrait
 */
trait SerializerTrait
{
    public function serialize($data, $format, array $context = array())
    {
        $this['serializer']->serialize($data, $format, $context);
    }
}