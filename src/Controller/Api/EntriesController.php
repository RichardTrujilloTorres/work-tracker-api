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
        $content = json_decode($request->getContent());

        $entry = $this->getRepository()->create((array) $content);

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
     * @Route("api/entries/{id<\d+>}/associate", methods={"POST"}, name="api.entries.associate")
     * @param $id
     * @param Request $request
     * @return JsonResponse
     * @throws NotFoundException
     */
    public function associate($id, Request $request)
    {
        $entry = $this->getRepository()->find($id);
        if (! $entry) {
            throw new NotFoundException('Could not find entry with ID '. $id);
        }

        $content = json_decode($request->getContent());
        $sha = @$content->sha;
        if (empty($sha) || !is_array($sha)) {
            return $this->jsonWithContext([
                'message' => 'No valid sha specified',
            ], Response::HTTP_BAD_REQUEST);
        }

        $entry = $this->getRepository()->associate($entry->getId(), $sha);

        return $this->jsonWithContext([
            'data' => compact('entry'),
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
