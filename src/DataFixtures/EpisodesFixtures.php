<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Episode;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EpisodesFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    public function load(ObjectManager $manager): void
    {
        //Puis ici nous demandons à la Factory de nous fournir un Faker
        $faker = Factory::create();
        /**
        * L'objet $faker que tu récupère est l'outil qui va te permettre 
        * de te générer toutes les données que tu souhaites
        */
        for($j = 0; $j < 5 ; $j++){
            for($i = 0; $i < (count(CategoryFixtures::CATEGORIES) * ProgramFixtures::PROGRAM_LOOP * SeasonsFixtures::SEASON_PER_PROGRAM); $i++) {
                $episode = new Episode();
                $title = $faker->sentence(3, true);
                $episode->setNumber($faker->numberBetween(1, 10));
                $episode->setTitle($title);
                $episode->setSlug($this->slugger->slug($title));
                $episode->setSynopsis($faker->paragraphs(3, true));
                $episode->setDuration($faker->randomNumber(2));
                $episode->setSeason($this->getReference('season_' . $i, $episode));
                
    
               $manager->persist($episode);
            }
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            SeasonsFixtures::class,
        ];
    }
}
