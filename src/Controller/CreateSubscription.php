<?php
namespace App\Controller;

use App\Security\UserProvider;
use App\Service\SubscriptionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CreateSubscription extends AbstractController
{
    /**
     * @var SubscriptionService
     */
    private $subscriptionService;

    public function __invoke(Request $request, UserProvider $provider, SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;

        $input = json_decode($request->getContent());

        $user = $provider->getUser();

        $membership = $user->getMembership()[0];

        $subscription = $this->subscriptionService->createSubscription($membership, $input->payload, $input->plan_id, $input->external_id);

        return new JsonResponse([
            'ok' => 1,
            'subscription_id' => $subscription->getId()
        ]);
    }
}