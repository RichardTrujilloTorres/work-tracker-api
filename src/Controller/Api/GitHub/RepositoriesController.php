<?php

namespace App\Controller\Api\GitHub;

use Github\Client;
use Github\Exception\RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RepositoriesController
 * @package App\Controller\Api\GitHub
 */
class RepositoriesController extends AbstractController
{
    /**
     * @var Client
     */
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @Route("/api/github/repositories", methods={"GET"}, name="api.github.repositories.index")
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function index()
    {
        try {
            $repositories = $this->client->api('user')
                ->repositories(getenv('GITHUB_USERNAME'));
        } catch (RuntimeException $e) {
            return $this->json(
                [
                    'message' => $e->getMessage()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return $this->json(
            [
                'data' => compact('repositories')
            ],
            Response::HTTP_OK,
            []
        );
    }

    /**
     * @Route("/api/github/repositories/{repository}", methods={"GET"}, name="api.github.repositories.show")
     * @param string $repository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function show(string $repository)
    {
        try {
            $repository = $this->client->api('repo')
                ->show(getenv('GITHUB_USERNAME'), $repository);
        } catch (RuntimeException $e) {
            return $this->json(
                [
                    'message' => $e->getMessage()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return $this->json(
            [
                'data' => compact('repository')
            ],
            Response::HTTP_OK,
            []
        );
    }

    /**
     * @Route("/api/github/repositories/{repository}/branches", methods={"GET"}, name="api.github.repositories.branches.index")
     * @param string $repository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function branches(string $repository)
    {
        try {
            $branches = $this->client->api('repo')
                ->branches(getenv('GITHUB_USERNAME'), $repository);
        } catch (RuntimeException $e) {
            return $this->json(
                [
                    'message' => $e->getMessage()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return $this->json(
            [
                'data' => compact('branches')
            ],
            Response::HTTP_OK,
            []
        );
    }
}
