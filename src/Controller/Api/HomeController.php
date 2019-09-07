<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 * @package App\Controller\Api
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="home")
     * @Route("/api", methods={"GET"}, name="api.home")
     */
    public function home()
    {
        return $this->json([
            'data' => 'All good',
        ]);
    }
}
