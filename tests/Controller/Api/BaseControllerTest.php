<?php

namespace App\Tests\Controller\Api;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

abstract class BaseControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var
     */
    protected $token;


    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();

        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->logIn();
    }

    /**
     * Logs the user in.
     */
    protected function logIn(): void
    {
        $result = $this->client->request(
            'POST',
            '/api/login_check',
        [],
        [],
        [
            'CONTENT_TYPE' => 'application/json',
            'ACCEPT_ENCODING' => 'application/json',
        ],
        json_encode([
            'username' => 'test@test.com',
            'password' => 'test',
        ])
        );

        $content = json_decode($this->client->getResponse()->getContent());
        $this->token = $content->token;
    }

    /**
     * @return array
     */
    protected function getDefaultContext()
    {
        $dateCallback = function ($innerObject, $outerObject, string $attributeName, string $format = null, array $context = []) {
            return $innerObject instanceof \DateTime ? $innerObject->format(\DateTime::ISO8601) : '';
        };

        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getId();
            },
            AbstractNormalizer::CALLBACKS => [
                'createdAt' => $dateCallback,
            ],
        ];

        return $defaultContext;
    }

    /**
     * @param $data
     * @param int $status
     * @param array $headers
     * @return JsonResponse
     */
    public function jsonWithContext($data, $status = Response::HTTP_OK, $headers = [])
    {
        return $this->json(
            $data,
            $status,
            $headers,
            $this->getDefaultContext()
        );
    }

    // TODO eliminate this duplication
    /**
     * @param $data
     * @param int $status
     * @param array $headers
     * @param array $context
     * @return JsonResponse
     */
    protected function json($data, int $status = 200, array $headers = [], array $context = []): JsonResponse
    {
        $kernel = self::bootKernel();

        if ($kernel->getContainer()->has('serializer')) {
            $json = $kernel->getContainer()->get('serializer')->serialize($data, 'json', array_merge([
                'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
            ], $context));

            return new JsonResponse($json, $status, $headers, true);
        }

        return new JsonResponse($data, $status, $headers);
    }
}
