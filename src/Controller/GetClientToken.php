<?php
namespace App\Controller;

use App\Repository\SubscriptionPlanRepository;
use App\Security\UserProvider;
use App\Service\SubscriptionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class GetClientToken
 * @package App\Controller
 */
class GetClientToken extends AbstractController
{
    public function __invoke(Request $request, UserProvider $provider, SubscriptionService $subscriptionService)
    {
        $user = $provider->getUser();
        $membership = $user->getMembership()[0];

        $token = $subscriptionService->createClientToken($membership->getExternalId());

        return new JsonResponse([
            'client_token' => $token
        ]);
    }
}