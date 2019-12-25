<?php

namespace App\Tests\Controller\Api;

use App\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserProfileControllerTest
 * @package App\Tests\Controller\Api
 */
class UserProfileControllerTest extends BaseControllerTest
{
    /**
     * @test Profile action
     */
    public function profile()
    {
        $this->client->request(
            'POST',
            '/api/user/profile',
            [
                'username' => 'test@test.com',
            ],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token,
                'CONTENT_TYPE' => 'application/json',
                'ACCEPT_ENCODING' => 'application/json',
            ]
        );

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->assertContains('profile', $this->client->getResponse()->getContent());
    }

    /**
     * @test
     */
    public function throwNotFoundException()
    {
        $this->client->request(
            'POST',
            '/api/user/profile',
            [
                'username' => 'richard.trujillo.torres@gmail.com',
            ],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token,
                'CONTENT_TYPE' => 'application/json',
                'ACCEPT_ENCODING' => 'application/json',
            ]
        );

        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        $this->assertContains(
            (new NotFoundException('Could not find user'))->getMessage(),
            $this->client->getResponse()->getContent()
        );
    }

    /**
     * @test
     */
    public function returnsBadRequestOnMissingUsername()
    {
        $this->client->request('POST', '/api/user/profile',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token,
                'CONTENT_TYPE' => 'application/json',
                'ACCEPT_ENCODING' => 'application/json',
            ]);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());

        $this->assertContains('No username specified', $this->client->getResponse()->getContent());
    }
}
