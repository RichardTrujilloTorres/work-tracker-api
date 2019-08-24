<?php

namespace App\Controller\Api\Contracts;

use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Interface BaseControllerContract
 * @package App\Controller\Api\Contracts
 */
interface BaseControllerContract
{
    /**
     * @return String
     */
    public function getEntity(): String;

    /**
     * @return mixed
     */
    public function getRepository();
}
