<?php
namespace App\Service\Subscription\Contract;

use App\Service\Subscription\DataType\Subscription;

interface SubscriptionServiceInterface
{
    /**
     * Must return plan id or null on failure
     * @param $name
     * @param $billingFrequency
     * @param $currencyIsoCode
     * @param $price
     * @return string|null
     */
    public function createPlan($name, $billingFrequency, $currencyIsoCode, $price): ?string;

    /**
     * @param $id
     * @return mixed
     */
    public function getPlan($id);

    /**
     * Must return customer id or null on failure
     * @param $firstName
     * @param $lastName
     * @param $email
     * @return string|null
     */
    public function createCustomer($firstName, $lastName, $email): ?string;

    /**
     * Must return subscription id or null on failure
     * @param $payload
     * @param $plan_id
     * @return Subscription|null
     */
    public function createSubscription($payload, $plan_id): ?Subscription;

    /**
     * @return string|null
     */
    public function getServiceProviderName(): ?string;

    /**
     * @param $customerId
     * @return string|null
     */
    public function createClientToken($customerId): ?string;

    public function cancelSubscription(string $subscription_id): bool;
}