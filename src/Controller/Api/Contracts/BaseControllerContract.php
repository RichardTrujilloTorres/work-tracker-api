<?php

namespace App\Controller\Api\Contracts;

/**
 * Interface BaseControllerContract.
 */
interface BaseControllerContract
{
    /**
     * @return string
     */
    public function getEntity(): string;

    /**
     * @return mixed
     */
    public function getRepository();
}
