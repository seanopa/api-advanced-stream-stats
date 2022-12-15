<?php

namespace App\DataFixtures;

use App\Entity\Membership;
use App\Entity\SubscriptionPlan;
use App\Entity\User;
use App\Service\Contract\SubscriptionServiceInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class AppFixtures
 * @package App\DataFixtures
 */
class AppFixtures extends Fixture
{
    /**
     * @var SubscriptionServiceInterface
     */
    private $subscriptionService;
    /**
     * @var UserPasswordHasherInterface
     */
    private $passwordHasher;

    /**
     * @param SubscriptionServiceInterface $subscriptionService
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(SubscriptionServiceInterface $subscriptionService, UserPasswordHasherInterface $passwordHasher)
    {
        $this->subscriptionService = $subscriptionService;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $default_plan = $this->createPlans($manager);
        $this->createUsers($manager, $default_plan);
        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     * @return SubscriptionPlan|null
     */
    private function createPlans(ObjectManager $manager)
    {
        $plans = [
            ['name' => 'Free', 'default' => true, 'frequency' => 12, 'currency_code' => 'CAD', 'price' => 0],
            ['name' => 'Gold Monthly', 'default' => false, 'frequency' => 1, 'currency_code' => 'CAD', 'price' => 14.99],
            ['name' => 'Gold Yearly', 'default' => false, 'frequency' => 12, 'currency_code' => 'CAD', 'price' => 149.99], //  save $30
        ];

        $default_plan = null;

        foreach ($plans as $plan) {
            $plan_id = $this->subscriptionService->createPlan($plan['name'], $plan['frequency'], $plan['currency_code'], $plan['price']);
            if (!empty($plan_id)) {
               $subscriptionPlan = new SubscriptionPlan();
                $subscriptionPlan
                   ->setIsDefault($plan['default'])
                   ->setIsEnabled(true)
                   ->setName($plan['name'])
                   ->setFrequency($plan['frequency'])
                   ->setCurrency($plan['currency_code'])
                   ->setPrice($plan['price'] * 100) // save in cents locally
                   ->setProvider($this->subscriptionService->getServiceProviderName())
                   ->setExternalId($plan_id)
                   ->setCreatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s')))
                   ;
               $manager->persist($subscriptionPlan);
               $manager->flush();

               if ($plan['default']) {
                   $default_plan = $subscriptionPlan;
               }
            }
        }

        return $default_plan;
    }

    /**
     * @param ObjectManager $manager
     * @param $defaultPlan
     * @return void
     */
    private function createUsers(ObjectManager $manager, $defaultPlan)
    {
        $faker = \Faker\Factory::create();

        $date = \DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

        for ($i = 0; $i < 5 ; $i++) {
            $email = $faker->email;
            $user = new User();
            $user
                ->setEmail($email)
                ->setIsEmailVerified(true)
                ->setIsDeleted(false)
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setUsername($faker->userName)
                ->setCreatedAt($date)
                ->setUpdatedAt($date)
                ;

            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                'qwer123@BB'
            );

            $user->setPassword($hashedPassword);

            $customer_id = $this->subscriptionService->createCustomer($user->getFirstName(), $user->getLastName(), $user->getEmail());

            $membership = new Membership();
            $membership
                ->setUser($user)
                ->setExternalId($customer_id)
                ->setSubscriptionPlan($defaultPlan)
                ->setCreatedAt($date)
                ->setUpdatedAt($date)
                ->setExpiresAt($date)
            ;

            $manager->persist($user);
            $manager->persist($membership);

            $manager->flush();
        }
    }
}
