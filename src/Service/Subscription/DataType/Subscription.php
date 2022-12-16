<?php
namespace App\Service\Subscription\DataType;

class Subscription
{
    public $id;
    public $startDate;
    public $endDate;
    /**
     * @var Transaction[]
     */
    public $transactions = [];
}