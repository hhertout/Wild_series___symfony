<?php 

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Actor;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        
        for($j = 1 ; $j <= 3 ; $j++){
            for($i = 1; $i <= (count(CategoryFixtures::CATEGORIES) * ProgramFixtures::PROGRAM_LOOP) ; $i++) {
                $actor = new Actor();
                $name = $faker->lastName() . ' ' . $faker->firstName();
                $actor->setName($name);
                $actor->setSlug($name);
                $this->addReference('programs_' . $i . $j, $actor);
                $actor->addProgram($this->getReference('program_' . $i));

                $manager->persist($actor);        
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ProgramFixtures::class,
        ];
    }
}