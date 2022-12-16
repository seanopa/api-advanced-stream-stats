<?php

namespace App\DataFixtures;

use App\Entity\Membership;
use App\Entity\Plan;
use App\Entity\PlanFeature;
use App\Entity\PlanGroup;
use App\Entity\PlanGroupFeature;
use App\Entity\Subscription;
use App\Entity\User;
use App\Service\Subscription\Contract\SubscriptionServiceInterface;
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
     * @param \App\Service\Subscription\Contract\SubscriptionServiceInterface $subscriptionService
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
        $features = $this->createPlanFeatures($manager);
        list($group1, $group2) = $this->createGroups($manager, $features);

        $default_plan = $this->createPlans($manager, $group1, $group2);
        $this->createUsers($manager, $default_plan);
        $manager->flush();
    }

    private function createPlanFeatures($manager): array
    {
        $features = [
            ['name' => 'total viewers'],
            ['name' => 'viewer hours'],
            ['name' => 'peak viewers'],
            ['name' => 'average viewers'],
            ['name' => 'total followers'],
            ['name' => 'total likes'],
            ['name' => 'total streams'],
            ['name' => 'followers lost'],
        ];

        $result = [];

        foreach ($features as $feature) {
            $planFeature = new PlanFeature();
            $planFeature->setName($feature['name']);
            $manager->persist($planFeature);
            $manager->flush();
            $result[] = $planFeature;
        }

        return $result;
    }

    /**
     * @param $manager
     * @param $features
     * @return array
     */
    private function createGroups($manager, $features): array
    {
        $items = [
            ['name' => 'Free', 'features' => array_splice($features, 0, 2)],
            ['name' => 'Gold', 'features' => $features],
        ];

        $result = [];

        foreach ($items as $item) {
            $group = new PlanGroup();
            $group
                ->setName($item['name'])
            ;

            $manager->persist($group);
            $manager->flush();

            $result[] = $group;

            foreach ($item['features'] as $feature) {
                $planGroupFeature = new PlanGroupFeature();

                $planGroupFeature
                    ->setGroup($group)
                    ->setFeature($feature);

                $manager->persist($planGroupFeature);
                $manager->flush();
            }
        }
        return $result;
    }

    /**
     * @param ObjectManager $manager
     * @param $group1
     * @param $group2
     * @return Plan|null
     */
    private function createPlans(ObjectManager $manager, $group1, $group2)
    {
        $plans = [
            ['name' => 'Free', 'default' => true, 'frequency' => 12, 'currency_code' => 'CAD', 'price' => 0, 'group' => $group1],
            ['name' => 'Gold Monthly', 'default' => false, 'frequency' => 1, 'currency_code' => 'CAD', 'price' => 14.99, 'group' => $group2],
            ['name' => 'Gold Yearly', 'default' => false, 'frequency' => 12, 'currency_code' => 'CAD', 'price' => 149.99, 'group' => $group2], //  save $30
        ];

        $default_plan = null;

        foreach ($plans as $plan) {
            $plan_id = $this->subscriptionService->createPlan($plan['name'], $plan['frequency'], $plan['currency_code'], $plan['price']);
            if (!empty($plan_id)) {
               $subscriptionPlan = new Plan();
                $subscriptionPlan
                   ->setIsDefault($plan['default'])
                   ->setIsEnabled(true)
                   ->setName($plan['name'])
                    ->setGroup($plan['group'])
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
                ->setRoles(["ROLE_APP_USER"])
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
                ->setProvider($this->subscriptionService->getServiceProviderName())
                ->setExternalId($customer_id)
                ->setCreatedAt($date)
                ->setUpdatedAt($date)
                ->setExpiresAt($date)
            ;

            $subscription = new Subscription();
            $subscription
                ->setPlan($defaultPlan)
                ->setActive(true)
                ->setCreatedAt($date)
                ->setUpdatedAt($date)
                ;

            $membership->addSubscription($subscription);

            $manager->persist($user);
            $manager->persist($membership);

            $manager->flush();
        }
    }
}
