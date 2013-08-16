<?php
/**
 * User: matteo
 * Date: 16/08/13
 * Time: 13.35
 * Just for fun...
 */


namespace CypressLab\GitElephantRestApi;

use GitElephant\Repository;

trait RepositoryTrait
{
    /**
     * @return Repository
     */
    public function getRepository()
    {
        return $this['repository'];
    }
}