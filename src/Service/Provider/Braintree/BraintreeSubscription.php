<?php
namespace App\Service\Provider\Braintree;

use App\Service\Contract\SubscriptionServiceInterface;
use Braintree\Exception\NotFound;
use Braintree\Gateway;
use Psr\Log\LoggerInterface;

/**
 * Class BraintreeSubscription
 * @package App\Service\Provider\Braintree
 */
class BraintreeSubscription implements SubscriptionServiceInterface
{
    const PROVIDER_NAME = 'BRAINTREE';
    /**
     * @var Gateway
     */
    private $gateway;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->gateway = new Gateway([
            'environment' => $_ENV['BRAINTREE_ENV'],
            'merchantId' => $_ENV['BRAINTREE_MERCHANT_ID'],
            'publicKey' => $_ENV['BRAINTREE_PUBLIC_KEY'],
            'privateKey' => $_ENV['BRAINTREE_PRIVATE_KEY']
        ]);
        $this->logger = $logger;
    }

    /**
     * @param $firstName
     * @param $lastName
     * @param $email
     * @return string|null
     */
    public function createCustomer($firstName, $lastName, $email): ?string
    {
        $payload = [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
        ];

        $this->logger->debug('Creating Customer '. json_encode($payload));

        $result = $this->gateway->customer()->create($payload);

        if ($result->success) {
            return $result->customer->id;
        }

        return null;
    }

    /**
     * @param $name
     * @param $billingFrequency
     * @param $currencyIsoCode
     * @param $price
     * @return string|null
     */
    public function createPlan($name, $billingFrequency, $currencyIsoCode, $price): ?string
    {
        $payload = [
            'name' => $name,
            'billingFrequency' => $billingFrequency,
            'currencyIsoCode' => $currencyIsoCode,
            'price' => $price
        ];

        $this->logger->debug('Creating Plan '. json_encode($payload));

        $result = $this->gateway->plan()->create($payload);

        $this->logger->debug('Creating Plan Result '. json_encode($result));

        if ($result->success) {
            return $result->plan->id;
        }
        return null;
    }

    /**
     * @throws NotFound
     */
    public function getPlan($id): \Braintree\Plan
    {
        return $this->gateway->plan()->find($id);
    }

    /**
     * @return array
     */
    public function getAllPlans(): array
    {
        return $this->gateway->plan()->all();
    }

    /**
     * @param $token
     * @param $plan_id
     * @return string|null
     */
    public function createSubscription($token, $plan_id): ?string
    {
        $payload = [
            'paymentMethodToken' => $token,
            'planId' => $plan_id
        ];

        $this->logger->debug('Creating Subscription ' . json_encode($payload));

        $result = $this->gateway->subscription()->create($payload);

        if ($result->success) {
            return $result->subscription->id;
        }

        return null;
    }

    /**
     * @return string|null
     */
    public function getServiceProviderName(): ?string
    {
        return static::PROVIDER_NAME;
    }
}