<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach(CategoryFixtures::CATEGORIES as $category){
            for($i = 0 ; $i < 5 ; $i++){
                $program = new Program();
                $program->setTitle('Titre du film ' . $i);
                $program->setSynopsis('Ceci est une super description, obtenue en bouclant sur des fixtures et des jointures en BDD. Le tout fonctionne aux petits oignons');
                $program->setCategory($this->getReference('category_' . $category));
                $manager->persist($program);
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
