<?php
namespace App\Controller;

use App\Security\UserProvider;
use App\Service\SubscriptionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CancelSubscription extends AbstractController
{
    public function __invoke(Request $request, UserProvider $userProvider, SubscriptionService $subscriptionService)
    {
        $user = $userProvider->getUser();

        $membership = $user->getMembership()[0];
        $subscription = $membership->getActiveSubscription();

        if ($subscriptionService->cancelSubscription($subscription)) {
            return new JsonResponse([
                'ok' => true
            ]);
        }

        return new JsonResponse([
            'ok' => false,
            'error' => 'An error occurred while trying to cancel your subscription'
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}