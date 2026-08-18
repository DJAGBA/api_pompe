<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class AuthController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (empty($data['password'])) {
            return new JsonResponse([
                'status' => 'error',
                'code' => 400,
                'message' => 'Le mot de passe est obligatoire.',
            ], 400);
        }

        if (strlen($data['password']) < 8) {
            return new JsonResponse([
                'status' => 'error',
                'code' => 400,
                'message' => 'Le mot de passe doit contenir au moins 8 caractères.',
            ], 400);
        }

        $user = new User();
        $user->setEmail($data['email'] ?? '');
        $user->setNom($data['nom'] ?? '');
        $user->setPrenom($data['prenom'] ?? '');
        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setPassword(
            $userPasswordHasher->hashPassword($user, $data['password'])
        );

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[] = $error->getMessage();
            }
            return new JsonResponse([
                'status' => 'error',
                'code' => 400,
                'message' => implode(' ', $messages),
            ], 400);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse([
            'status' => 'success',
            'code' => 201,
            'message' => 'Compte créé avec succès.',
        ], 201);
    }
}