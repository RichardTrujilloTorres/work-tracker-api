<?php

namespace App\Controller\Api;

use App\Controller\Api\Contracts\BaseControllerContract;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * Class BaseController
 * @package App\Controller\Api
 */
abstract class BaseController extends AbstractController implements BaseControllerContract
{
    /**
     * @param $data
     * @param int $status
     * @param array $headers
     * @return \Symfony\Component\HttpFoundation\JsonResponse
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

    /**
     * @return array
     */
    public function getDefaultContext()
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
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository()
    {
        return $this->getDoctrine()->getRepository($this->getEntity());
    }
}
