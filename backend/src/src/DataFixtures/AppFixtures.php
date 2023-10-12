<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\User;
use App\Entity\Message;
use App\Entity\Channel;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
     * @var Generator
     */
    private Generator $faker;
    private UserPasswordHasherInterface $hasher; 

    public function __construct($hasher)
    {
        $this->faker = Factory::create('fr_FR');
        $this -> hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Users
        $users = [];

        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $username = $username = $this->faker->unique()->userName;
            $user->setUsername((string) $username)
                ->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER'])
                ->setPlainPassword('password');


      $users[] = $user;
      $manager->persist($user);
        }

        $manager->flush();
}
}