<?php
namespace App\Service;

use App\Service\Contract\SubscriptionServiceInterface;

/**
 * Class SubscriptionService
 * @package App\Service
 */
class SubscriptionService
{
    /**
     * @var SubscriptionServiceInterface
     */
    private $subscriptionService;

    /**
     * @param SubscriptionServiceInterface $service
     */
    public function __construct(SubscriptionServiceInterface $service)
    {
        $this->subscriptionService = $service;
    }
}