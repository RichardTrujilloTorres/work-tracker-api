<?php

namespace App\Controller\Api;

use App\Entity\Commit;
use App\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommitsController
 * @package App\Controller\Api
 */
class CommitsController extends BaseController
{
    /**
     * @Route("/api/commits", methods={"GET"}, name="api.commits.index")
     */
    public function index()
    {
        $commits = $this->getRepository()->findAll();

        return $this->json([
            'data' => compact('commits'),
        ]);
    }

    /**
     * @Route("/api/commits/{id<\d+>}", name="api.commits.show")
     * @param int $id
     * @return JsonResponse
     * @throws NotFoundException
     */
    public function show(int $id)
    {
        $commit = $this->getRepository()->find($id);
        if (! $commit) {
            throw new NotFoundException('Could not find commit with ID '. $id);
        }

        return $this->json([
            'data' => compact('commit'),
        ]);
    }

    /**
     * @Route("api/commits", methods={"POST"}, name="api.commits.store")
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $commit = $this->getRepository()->create($request->request->all());

        return $this->json([
            'data' => compact('commit')
        ], 201);
    }

    /**
     * @Route("api/commits/{id<\d+>}", methods={"DELETE"}, name="api.commits.delete")
     * @param $id
     * @return JsonResponse
     */
    public function delete($id)
    {
        $this->getRepository()->delete($id);

        return $this->json([
            'data' => [],
        ], 200);
    }

    /**
     * @return String
     */
    public function getEntity(): String
    {
        return Commit::class;
    }
}
