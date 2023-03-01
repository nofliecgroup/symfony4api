<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Personnes;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[Route('/api')]
class UsersController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    

    #[Route('/users', name: 'get_all_users', methods: ['GET'])]
    public function getAllUsers(): JsonResponse
    {
        $users = $this->entityManager->getRepository(Users::class)->findAll();

       // $users = $this->getDoctrine()->getRepository(Users::class)->findAll();

        if (!$users) {
            return new JsonResponse(["error" => "No users found"], Response::HTTP_NOT_FOUND);
        }

         $usersData = [];
    foreach ($users as $user) {
        $idpers = $user->getIdpers();
        $idpersId = $idpers ? $idpers->getId() : null;

        $usersData[] = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'idpers' => $idpersId,
        ];
    }

        return new JsonResponse($usersData, Response::HTTP_OK);
    }

    #[Route('/users/{id}', name: 'get_one_user', methods: ['GET'])]
    public function getOneUser(int $id): JsonResponse
    {
        $user = $this->entityManager->getRepository(Users::class)->find($id);

        if (!$user) {
            return new JsonResponse(["error" => "User not found"], Response::HTTP_NOT_FOUND);
        }

        $idpers = $user->getIdpers();
        $idpersId = $idpers ? $idpers->getId() : null;

        $userData = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'idpers' => $idpersId,
        ];

        return new JsonResponse($userData, Response::HTTP_OK);
    }

    #[Route('/login', name: 'add_user', methods: ['POST'])]
    public function addUser(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = new Users();
        $user->setEmail($data['email']);
        $user->setRoles($data['roles']);
        $user->setIdpers($this->entityManager->getRepository(Personnes::class)->find($data['idpers']));
    
        $password = $data['password'];
        if (empty($data['email']) || empty($data['roles']) || empty($password)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }
        $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
    
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    
        return new JsonResponse(['status' => 'User created!'], Response::HTTP_CREATED);
    }

    #[Route('/register/{id}', name: 'update_user', methods: ['PUT'])]
    public function updateUser(int $id, Request $request): JsonResponse
    {
        $user = $this->entityManager->getRepository(Users::class)->find($id);
        $data = json_decode($request->getContent(), true);

        empty($data['email']) ? true : $user->setEmail($data['email']);
        empty($data['roles']) ? true : $user->setRoles($data['roles']);
        empty($data['idpers']) ? true : $user->setIdpers($this->entityManager->getRepository(Personnes::class)->find($data['idpers']));

        $updatedUser = $this->entityManager->getRepository(Users::class)->find($id);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'User updated!'], Response::HTTP_OK);
    }

    #[Route('/users/{id}', name: 'delete_user', methods: ['DELETE'])]

    public function deleteUser(int $id): JsonResponse
    {
        $user = $this->entityManager->getRepository(Users::class)->find($id);

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'User deleted'], Response::HTTP_OK);
    }

    

    
}
