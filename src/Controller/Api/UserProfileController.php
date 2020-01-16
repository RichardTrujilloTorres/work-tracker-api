<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserProfileController
 * @package App\Controller\Api
 */
class UserProfileController extends BaseController
{
    /**
     * @Route("/api/user/profile", methods={"POST"}, name="api.user.profile")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws NotFoundException
     */
    public function profile(Request $request)
    {
        $content = json_decode($request->getContent());
        $username = $content->username;
        if (empty($username)) {
            return $this->jsonWithContext([
                'message' => 'No username specified',
            ], Response::HTTP_BAD_REQUEST);
        }

        /**
         * @var User $user
         */
        $user = $this->getRepository()->findOneBy([
            'email' => $username,
        ]);

        if (empty($user)) {
            throw new NotFoundException('Could not find user');
        }

        $profile = $user->getProfile() ?
            [
                'firstname' => $user->getProfile()->getFirstname(),
                'lastname' => $user->getProfile()->getLastname(),
            ]
            : null;

        return $this->jsonWithContext([
            'data' => [
                'profile' => $profile,
            ]
        ]);
    }

    /**
     * @return String
     */
    public function getEntity(): String
    {
        return User::class;
    }
}
