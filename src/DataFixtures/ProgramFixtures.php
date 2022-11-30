<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Program;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public const PROGRAM_LOOP = 5; 

    public function load(ObjectManager $manager): void
    {
        $j=1;
        foreach(CategoryFixtures::CATEGORIES as $category){
            for($i = 1 ; $i <= 5 ; $i++){
                $faker = Factory::create();

                $program = new Program();
                $program->setTitle($faker->sentence(3, true));
                $program->setSynopsis($faker->paragraph(2));
                $program->setCategory($this->getReference('category_' . $category));
                $this->addReference('program_' . $j, $program);
                $manager->persist($program);
                $j++;
            }
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
