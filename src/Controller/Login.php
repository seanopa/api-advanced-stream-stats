<?php
namespace App\Controller;

use App\Repository\ApiTokenRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class Login
 * @package App\Controller
 */
class Login extends AbstractController
{
    /**
     * @param Request $request
     * @param UserRepository $userRepository
     * @param UserPasswordHasherInterface $passwordHasher
     * @param ApiTokenRepository $tokenRepository
     * @return JsonResponse
     * @throws \Exception
     */
    public function __invoke(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, ApiTokenRepository $tokenRepository)
    {
        $email = $request->get('email');
        $plaintextPassword = $request->get('password');

        $user = $userRepository->findOneBy(['email' => $email]);

        if (empty($user)) {
            return new JsonResponse(['error' => 'Incorrect username or password'], Response::HTTP_UNAUTHORIZED);
        }

        if (!$passwordHasher->isPasswordValid($user, $plaintextPassword)) {
            return new JsonResponse(['error' => 'Incorrect username or password'], Response::HTTP_UNAUTHORIZED);
        }

        $token = $tokenRepository->create($user);

        return new JsonResponse([
            'token' => $token->getToken(),
            'expires' => $token->getExpiresAt()->getTimestamp()
        ]);
    }
}