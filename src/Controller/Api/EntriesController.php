<?php

namespace App\Controller\Api;

use App\Entity\Entry;
use App\Exception\NotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EntriesController
 * @package App\Controller\Api
 */
class EntriesController extends AbstractController
{
    /**
     * @Route("/api/entries", name="api.entries", methods={"GET"})
     */
    public function index()
    {
        $entryRepository = $this->getDoctrine()->getRepository(Entry::class);

        $entries = $entryRepository->findAll();

        return $this->json([
            'data' => compact('entries'),
        ]);
    }

    /**
     * @Route("/api/entries/{id<\d+>}", name="api.entries.show")
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

        return $this->json([
            'data' => compact('entry'),
        ]);
    }

    /**
     * @Route("api/entries", methods={"POST"})
     */
    public function store(Request $request)
    {
        // TODO request validation

        $entry = $this->getRepository()->createEntry($request->request->all());

        return $this->json([
            'data' => compact('entry')
        ], 201);
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getRepository()
    {
        return $this->getDoctrine()->getRepository(Entry::class);
    }
}
