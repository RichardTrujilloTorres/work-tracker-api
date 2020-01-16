<?php

namespace App\Tests\Controller\Api;

use App\Entity\Entry;
use App\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\Response;

class EntriesControllerTest extends BaseControllerTest
{
    /**
     * @test Index action
     */
    public function index()
    {
        $this->client->request(
            'GET',
            '/api/entries',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token,
                'CONTENT_TYPE' => 'application/json',
                'ACCEPT_ENCODING' => 'application/json',
            ]
        );

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->assertContains('entries', $this->client->getResponse()->getContent());
    }

    /**
     * @test Latest action
     */
    public function latest()
    {
        $this->client->request(
            'GET',
            '/api/entries/latest',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token,
                'CONTENT_TYPE' => 'application/json',
                'ACCEPT_ENCODING' => 'application/json',
            ]
        );

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->assertContains('entries', $this->client->getResponse()->getContent());
    }

    /**
     * @test Show action.
     */
    public function testShow()
    {
        $this->client->request(
            'GET',
            '/api/entries/1',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token,
                'CONTENT_TYPE' => 'application/json',
                'ACCEPT_ENCODING' => 'application/json',
            ]
        );

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->assertContains('entry', $this->client->getResponse()->getContent());

        // test content matching through direct DB retrieve
        /**
         * @var Entry $entry
         */
        $entry = $this->entityManager
            ->getRepository(Entry::class)
            ->find(1);

        $this->assertEquals(
            $this->client->getResponse()->getContent(),
            $this->jsonWithContext([
                'data' => compact('entry'),
            ])->getContent()
        );
    }

    /**
     * @test Throws a NotFoundException
     */
    public function throwsNotFoundException()
    {
        $nonExistingEntryId = 999999;

        $this->client->request(
            'GET',
            '/api/entries/' . $nonExistingEntryId,
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token,
                'CONTENT_TYPE' => 'application/json',
                'ACCEPT_ENCODING' => 'application/json',
            ]
        );

        $this->assertContains(
            (new NotFoundException('Could not find entry with ID '. $nonExistingEntryId))->getMessage(),
            $this->client->getResponse()->getContent()
        );
    }
}
