<?php
namespace App\Service\Subscription\Provider;

use App\Service\Subscription\Contract\SubscriptionServiceInterface;
use App\Service\Subscription\DataType\Subscription;
use App\Service\Subscription\DataType\Transaction;
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
    public function getPlan($id)
    {
        return $this->gateway->plan()->find($id);
    }

    /**
     * @param $payload
     * @param $plan_id
     * @return Subscription|null
     */
    public function createSubscription($payload, $plan_id): ?Subscription
    {
        $input = [
            'planId' => $plan_id
        ];

        if (!empty($payload->nonce)) {
            $input['paymentMethodNonce'] = $payload->nonce;
        } else {
            $input['paymentMethodToken'] = $payload->token;
        }

        $this->logger->debug('Creating Subscription ' . json_encode($input));

        $result = $this->gateway->subscription()->create($input);

        $this->logger->debug('Braintree Subscription Response: ' . json_encode($result));

        if ($result->success) {
            $subscription = new Subscription();
            $subscription->id = $result->subscription->id;
            $subscription->startDate = $result->subscription->billingPeriodStartDate;
            $subscription->endDate = $result->subscription->billingPeriodEndDate;

            foreach ($result->subscription->transactions as $transaction) {
                $trans = new Transaction();

                $trans->id = $transaction->id;
                $trans->amount = $transaction->amount;
                $trans->currencyCode = $transaction->currencyIsoCode;

                $subscription->transactions[] = $trans;
            }

            return $subscription;
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

    public function createClientToken($customerId): ?string
    {
        return $this->gateway->clientToken()->generate([
            "customerId" => $customerId
        ]);
    }

    public function cancelSubscription(string $subscription_id): bool
    {
        $result = $this->gateway->subscription()->cancel($subscription_id);

        return $result->success;
    }
}