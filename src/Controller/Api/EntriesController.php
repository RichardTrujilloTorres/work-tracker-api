<?php

namespace App\Controller\Api;

use App\Entity\Entry;
use App\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EntriesController
 * @package App\Controller\Api
 */
class EntriesController extends BaseController
{
    /**
     * @Route("/api/entries", methods={"GET"}, name="api.entries.index")
     */
    public function index()
    {
        $entries = $this->getRepository()->findAll();

        return $this->jsonWithContext([
            'data' => compact('entries'),
        ]);
    }

    /**
     * @Route("/api/entries/{id<\d+>}", methods={"GET"}, name="api.entries.show")
     * @param int $id
     * @return JsonResponse
     * @throws NotFoundException
     */
    public function show(int $id)
    {
        $entry = $this->getRepository()->find($id);
        if (! $entry) {
            throw new NotFoundException('Could not find entry with ID '. $id);
        }

        return $this->jsonWithContext([
            'data' => compact('entry'),
        ]);
    }

    /**
     * @Route("api/entries", methods={"POST"}, name="api.entries.store")
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        // TODO request validation

        $entry = $this->getRepository()->create($request->request->all());

        return $this->jsonWithContext([
            'data' => compact('entry')
        ], Response::HTTP_CREATED);
    }

    /**
     * @Route("api/entries/{id<\d+>}", methods={"DELETE"}, name="api.entries.delete")
     * @param $id
     * @return JsonResponse
     */
    public function delete($id)
    {
        $this->getRepository()->delete($id);

        return $this->jsonWithContext([
            'data' => [],
        ]);
    }

    /**
     * @return String
     */
    public function getEntity(): String
    {
        return Entry::class;
    }
}
