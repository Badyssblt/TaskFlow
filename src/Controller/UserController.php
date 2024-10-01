<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/api/verification-code', name: 'app_user_verification_code', methods: ['POST'])]
    public function index(JWTTokenManagerInterface $jwt, Request $request, UserRepository $userRepository, EntityManagerInterface $manager): JsonResponse
    {
        $code = $request->getPayload()->get('code');
        $email = $request->getPayload()->get('email');

        $user = $userRepository->findOneBy(['email' => $email]);

        if($user->getVerificationCode() === $code) {
            $user->setVerified(true);
        }else {
            return $this->json(['message' => 'Le code est incorrect'], Response::HTTP_BAD_REQUEST);
        }

        $manager->persist($user);
        $manager->flush();

        $token = $jwt->create($user);

        return $this->json(['token' => $token, 'message' => 'Votre compte a été vérifié avec succès']);
    }
}
