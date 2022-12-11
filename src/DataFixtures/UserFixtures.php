<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const NUMBER_OF_USERS = 20;
    private UserPasswordHasherInterface $passwordHasher;
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        for($i = 0 ; $i < self::NUMBER_OF_USERS ; $i++){
            $faker = Factory::create();

            $user = new User();
            $password = $faker->password();
            $firstname = $faker->firstName();
            $lastname = $faker->lastName();
            $user->setLastname($lastname);
            $user->setFirstname($firstname);
            $user->setRoles(['ROLE_CONTRIBUTOR']);
            $user->setPassword($this->passwordHasher->hashPassword($user, $password));
            $user->setEmail($faker->freeEmail());
            $user->setSlug($lastname . $firstname);

            $manager->persist($user);
        }

        $visitor = new User();
        $visitorPassword = 'visitor';
        $visitor->setRoles(['ROLE_CONTRIBUTOR']);
        $visitor->setLastname('Doe');
        $visitor->setFirstname('John');
        $visitor->setEmail('visitor@wildseries.com');
        $visitor->setPassword($this->passwordHasher->hashPassword($visitor, $visitorPassword));
        $visitor->setSlug('admin');
        $manager->persist($visitor);

        $admin = new User();
        $adminPassword = 'admin';
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setLastname('WildSeries');
        $admin->setFirstname('admin');
        $admin->setEmail('admin@wildseries.com');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, $adminPassword));
        $admin->setSlug('admin');
        $manager->persist($admin);

        $manager->flush();
        
        

        
    }
}
