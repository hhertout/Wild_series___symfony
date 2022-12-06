<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Season;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class SeasonsFixtures extends Fixture implements DependentFixtureInterface
{
    public const SEASON_PER_PROGRAM = 5;
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $loopIndex = 0;
        for($j = 0; $j < self::SEASON_PER_PROGRAM ; $j++){
            for($i = 0; $i < (count(CategoryFixtures::CATEGORIES) * ProgramFixtures::PROGRAM_LOOP) ; $i++) {
                $season = new Season();
                $season->setNumber($faker->randomDigitNotNull());
                $season->setYear($faker->year());
                $season->setDescription($faker->paragraphs(3, true));
                $season->setProgram($this->getReference('program_' . $faker->numberBetween(1, count(CategoryFixtures::CATEGORIES) * ProgramFixtures::PROGRAM_LOOP)));
                $this->addReference('season_' . $loopIndex, $season);
                $manager->persist($season);
                $loopIndex++;
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
