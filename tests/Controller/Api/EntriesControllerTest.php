<?php

namespace App\Tests\Controller\Api;

use App\Entity\Entry;
use Symfony\Component\HttpFoundation\Response;

class EntriesControllerTest extends BaseControllerTest
{
    /**
     * @test Index action
     */
    public function index()
    {
        $this->client->request('GET', '/api/entries');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->assertContains('entries', $this->client->getResponse()->getContent());
    }

    /**
     * @test Show action.
     */
    public function testShow()
    {
        $this->client->request('GET', '/api/entries/3');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->assertContains('entry', $this->client->getResponse()->getContent());

        // test content matching through direct DB retrieve
        /**
         * @var Entry $entry
         */
        $entry = $this->entityManager
            ->getRepository(Entry::class)
            ->find(3);

        $this->assertEquals(
            $this->client->getResponse()->getContent(),
            $this->jsonWithContext([
                'data' => compact('entry'),
            ])->getContent()
        );
    }
}
