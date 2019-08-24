<?php

namespace App\Controller\Api;

use App\Controller\Api\Contracts\BaseControllerContract;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BaseController
 * @package App\Controller\Api
 */
abstract class BaseController extends AbstractController implements BaseControllerContract
{
    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository()
    {
        return $this->getDoctrine()->getRepository($this->getEntity());
    }
}
