<?php
namespace App\Service;

use App\Entity\Membership;
use App\Entity\Subscription;
use App\Repository\PlanRepository;
use App\Repository\SubscriptionRepository;
use App\Repository\TransactionRepository;
use App\Service\Subscription\Contract\SubscriptionServiceInterface;

/**
 * Class SubscriptionService
 * @package App\Service
 */
class SubscriptionService
{
    /**
     * @var \App\Service\Subscription\Contract\SubscriptionServiceInterface
     */
    private $subscriptionService;
    /**
     * @var PlanRepository
     */
    private $planRepository;
    /**
     * @var SubscriptionRepository
     */
    private $subscriptionRepository;
    /**
     * @var TransactionRepository
     */
    private $transactionRepository;

    /**
     * @param SubscriptionServiceInterface $service
     * @param PlanRepository $planRepository
     * @param SubscriptionRepository $subscriptionRepository
     * @param TransactionRepository $transactionRepository
     */
    public function __construct(SubscriptionServiceInterface $service, PlanRepository $planRepository, SubscriptionRepository $subscriptionRepository, TransactionRepository $transactionRepository)
    {
        $this->subscriptionService = $service;
        $this->planRepository = $planRepository;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * @param Membership $membership
     * @param $payload
     * @param $plan_id
     * @param $external_plan_id
     * @return \App\Entity\Subscription
     * @throws \Exception
     */
    public function createSubscription(Membership $membership, $payload, $plan_id, $external_plan_id): \App\Entity\Subscription
    {
        $plan = $this->planRepository->find($plan_id);

        if (empty($plan) || $plan_id != $plan->getId() || $external_plan_id != $plan->getExternalId()) {
           throw new \Exception('Plan not found');
        }

        $subscription = $this->subscriptionService->createSubscription($payload, $external_plan_id);

        if (empty($subscription)) {
            throw new \Exception('Failed to create subscription');
        }

        $this->subscriptionRepository->disableDefault($membership);
        $newSubscription = $this->subscriptionRepository->create($membership, $plan, $subscription->id, $subscription->startDate, $subscription->endDate);

        $transactions = $subscription->transactions;

        foreach ($transactions as $transaction) {
            $this->transactionRepository->create($newSubscription, $transaction->id, $transaction->amount, $transaction->currencyCode);
        }

        return $newSubscription;
    }

    /**
     * @param $plan_id
     * @return mixed
     */
    public function getPlan($plan_id)
    {
        return $this->subscriptionService->getPlan($plan_id);
    }

    /**
     * @param $customer_id
     * @return string|null
     */
    public function createClientToken($customer_id): ?string
    {
        return $this->subscriptionService->createClientToken($customer_id);
    }

    /**
     * @param Subscription $subscription
     * @return bool
     */
    public function cancelSubscription(Subscription $subscription): bool
    {
        $external_id = $subscription->getExternalId();
        $success = $this->subscriptionService->cancelSubscription($external_id);

        if ($success) {
           $this->subscriptionRepository->cancel($subscription);
           return true;
        }

        return false;
    }

}