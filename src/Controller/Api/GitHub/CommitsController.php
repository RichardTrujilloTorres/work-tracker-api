<?php

namespace App\Controller\Api\GitHub;

use Github\Client;
use Github\Exception\RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommitsController.
 */
class CommitsController extends AbstractController
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
     * @Route("/api/github/commits/{repository}/{branch}", methods={"GET"}, name="api.github.commits.index")
     *
     * @param string $repository
     * @param string $branch
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function index(string $repository, string $branch)
    {
        try {
            $commits = $this->client->api('repo')
                ->commits()
                ->all(getenv('GITHUB_USERNAME'), $repository, ['sha' => $branch]);
        } catch (RuntimeException $e) {
            return $this->json(
                [
                    'message' => $e->getMessage(),
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return $this->json(
            [
                'data' => compact('commits'),
            ],
            Response::HTTP_OK,
            []
        );
    }

    /**
     * @Route("/api/github/commits/show/{repository}/{branch}/{sha}", methods={"GET"}, name="api.github.commits.show")
     *
     * @param string $repository
     * @param string $branch
     * @param string $sha
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function show(string $repository, string $branch, string $sha)
    {
        try {
            $commit = $this->client->api('repo')
                ->commits()
                ->show(getenv('GITHUB_USERNAME'), $repository, $sha);
        } catch (RuntimeException $e) {
            return $this->json(
                [
                    'message' => $e->getMessage(),
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return $this->json(
            [
                'data' => compact('commit'),
            ],
            Response::HTTP_OK,
            []
        );
    }
}
