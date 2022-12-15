<?php
namespace App\Service\Contract;

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
     * Must return customer id or null on failure
     * @param $firstName
     * @param $lastName
     * @param $email
     * @return string|null
     */
    public function createCustomer($firstName, $lastName, $email): ?string;

    /**
     * Must return subscription id or null on failure
     * @param $token
     * @param $plan_id
     * @return string|null
     */
    public function createSubscription($token, $plan_id): ?string;

    public function getServiceProviderName(): ?string;
}