<?php
namespace App\Controller;

use App\Repository\PlanGroupFeatureRepository;
use App\Security\UserProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetAccountSummary extends AbstractController
{
    public function __invoke(Request $request, UserProvider $provider, PlanGroupFeatureRepository $planGroupFeatureRepository)
    {
        $user = $provider->getUser();

        $membership = $user->getMembership()[0];

        $subscription = $membership->getActiveSubscription();

        $plan = $subscription->getPlan();
        $features = $planGroupFeatureRepository->findBy(['group' => $plan->getGroup()]);
        $featureItems = [];
        $faker = \Faker\Factory::create();

        foreach ($features as $feature) {
            $featureItems[] = [
                'name' => $feature->getFeature()->getName(),
                'count' => $faker->randomDigit
            ];
        }
        return new JsonResponse([
            'user' => [
                'id' => $user->getId(),
                'first_name' => $user->getFirstName(),
                'last_name' => $user->getLastName()
            ],
            'subscription' => [
                'id' => $subscription->getId(),
                'membership_id' => $subscription->getMembership()->getId(),
            ],
            'plan' => [
                'id' => $plan->getId(),
                'name' => $plan->getName(),
            ],
            'features' => $featureItems
        ]);
    }
}