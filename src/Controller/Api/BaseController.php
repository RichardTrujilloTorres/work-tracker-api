<?php

namespace App\Controller\Api;

use App\Controller\Api\Contracts\BaseControllerContract;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * Class BaseController
 * @package App\Controller\Api
 */
abstract class BaseController extends AbstractController implements BaseControllerContract
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var PaginatorInterface
     */
    protected $paginator;

    /**
     * BaseController constructor.
     * @param RequestStack $requestStack
     * @param PaginatorInterface $paginatorBundle
     */
    public function __construct(RequestStack $requestStack, PaginatorInterface $paginatorBundle)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->paginator = $paginatorBundle;
    }

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
     * @param $data
     * @param string $resourceKey
     * @param int $status
     * @param array $headers
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function paginatedJsonWithContext($data, string $resourceKey, $status = Response::HTTP_OK, $headers = [])
    {
        $page = $this->request->query->getInt('page', 1);
        $perPage = $this->request->query->getInt('per_page', 10);

        $pagination = $this->paginator->paginate(
            $data,
            $page,
            $perPage
        );

        return $this->json(
            [
                'page' => $page,
                'per_page' => $perPage,
                'data' => [ $resourceKey => $pagination, ],
            ],
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
