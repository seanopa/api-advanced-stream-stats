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
                'is_recurring' => $subscription->isRecurring(),
                'start_date' => $subscription->getStartDate(),
                'end_date' => $subscription->getEndDate(),
                'is_cancelled' => !empty($subscription->getDeletedAt())
            ],
            'plan' => [
                'id' => $plan->getId(),
                'name' => $plan->getName(),
                'is_upgradable' => $plan->getPrice() == 0,
                'price' => $plan->getPrice()/100,
                'description' => sprintf('Billed every %s months', $plan->getFrequency()), // Relying on service
                'currency_code' => $plan->getCurrency(),
                'currency_symbol' => '$' // No need to implement this as it's for demo purposes only
            ],
            'features' => $featureItems
        ]);
    }
}