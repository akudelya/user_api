<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\User;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GroupContentController extends AbstractController
{
    #[Route('/groups/{group_id}/add', name: 'app_group_user_add', methods: ['POST'])]
    public function addUserToGroup(Request $request, string $group_id, EntityManagerInterface $entityManager, UserRepository $userRepos, GroupRepository $groupRepos): Response
    {
        $userId = $request->getPayload()->get('user_id');
        $user = $userRepos->find($userId);
        $group = $groupRepos->find($group_id);
        if ($user && $group) {
            $group->addUser($user);
            $entityManager->flush();
            $response = JsonResponse::fromJsonString('{ "message": "User is added to group" }');
        }
        else {
            $response = JsonResponse::fromJsonString('{ "message": "User or group is not found" }');
            $response->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $response;
    }

    #[Route('/groups/{group_id}/delete', name: 'app_group_user_delete', methods: ['DELETE'])]
    public function deleteUserFromGroup(Request $request, string $group_id, EntityManagerInterface $entityManager, UserRepository $userRepos, GroupRepository $groupRepos): Response
    {
        $userId = $request->getPayload()->get('user_id');
        $user = $userRepos->find($userId);
        $group = $groupRepos->find($group_id);
        if ($user && $group) {
            $group->deleteUser($user);
            $entityManager->flush();
            $response = new JsonResponse($group);
            $response = JsonResponse::fromJsonString('{ "message": "User is deleted from group" }');
        }
        else {
            $response = JsonResponse::fromJsonString('{ "message": "User or group is not found" }');
            $response->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $response;
    }
}