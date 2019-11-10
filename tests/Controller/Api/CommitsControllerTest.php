<?php

namespace App\Tests\Controller\Api;

use App\Entity\Commit;
use App\Entity\Entry;
use App\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\Response;

class CommitsControllerTest extends BaseControllerTest
{
    /**
     * @test Index action
     */
    public function index()
    {
        $this->client->request('GET', '/api/commits',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token,
                'CONTENT_TYPE' => 'application/json',
                'ACCEPT_ENCODING' => 'application/json',
            ]);

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->assertContains('commits', $this->client->getResponse()->getContent());
    }

    /**
     * @test Show action.
     */
    public function testShow()
    {
        $this->client->request('GET', '/api/commits/1',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token,
                'CONTENT_TYPE' => 'application/json',
                'ACCEPT_ENCODING' => 'application/json',
            ]);

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->assertContains('commit', $this->client->getResponse()->getContent());

        // test content matching through direct DB retrieve
        /**
         * @var Entry $entry
         */
        $commit = $this->entityManager
            ->getRepository(Commit::class)
            ->find(1);

        $this->assertEquals(
            $this->client->getResponse()->getContent(),
            $this->jsonWithContext([
                'data' => compact('commit'),
            ])->getContent()
        );
    }

    /**
     * @test Throws a NotFoundException
     */
    public function throwsNotFoundException()
    {
        $nonExistingEntryId = 999999;

        $this->client->request('GET', '/api/commits/' . $nonExistingEntryId,
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token,
                'CONTENT_TYPE' => 'application/json',
                'ACCEPT_ENCODING' => 'application/json',
            ]);

        $this->assertContains(
            (new NotFoundException('Could not find commit with ID '. $nonExistingEntryId))->getMessage(),
            $this->client->getResponse()->getContent()
        );
    }
}
