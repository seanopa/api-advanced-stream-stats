<?php
namespace App\Controller;

use App\Repository\PlanGroupFeatureRepository;
use App\Repository\SubscriptionPlanRepository;
use App\Service\SubscriptionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class GetSubscriptionPlans
 * @package App\Controller
 */
class GetSubscriptionPlans extends AbstractController
{
    public function __invoke(SubscriptionPlanRepository $planRepository, PlanGroupFeatureRepository  $groupFeatureRepository, SubscriptionService $subscriptionService)
    {
        $plans = $planRepository->findAll(); // Ideally we would want to use plans that are marked as active. Only doing it like this for demo

        $features = $groupFeatureRepository->findAll();
        $groups = [];

        foreach ($features as $feature) {
            if (!isset($groups[$feature->getGroup()->getId()]['name'])) {
                $groups[$feature->getGroup()->getId()]['name'] = $feature->getGroup()->getName();
            }
            $groups[$feature->getGroup()->getId()]['features'][] = $feature->getFeature()->getName();
        }

        foreach ($plans as $plan) {
            // Only to demo we can get additional info from service and not use local database.
            //Cannot get all at once because there could be 100 plans we don't use so only get by id
            $managedPlan = $subscriptionService->getPlan($plan->getExternalId());

            $groups[$plan->getGroup()->getId()]['plans'][] = [
                'id' => $plan->getId(),
                'external_id' => $managedPlan->id,
                'name' => $plan->getName(),
                'price' => (float) $managedPlan->price, // Relying on service
                'is_payable' => $managedPlan->price > 0, // Relying on service
                'description' => sprintf('Billed every %s months', $managedPlan->billingFrequency), // Relying on service
                'currency_code' => $managedPlan->currencyIsoCode, // Relying on service
                'currency_symbol' => '$' // No need to implement this as it's for demo purposes only
            ];
        }

        return new JsonResponse([
            'subscriptions' => $groups
        ]);
    }
}