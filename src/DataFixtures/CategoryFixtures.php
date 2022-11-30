<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CategoryFixtures extends Fixture
{
    public const CATEGORIES = ['Action', 'Aventure', 'Animation', 'Fantastique', 'Horreur'];

    public function load(ObjectManager $manager)
    {
        foreach(self::CATEGORIES as $categoryName){
            $category = new Category();
            $category->setName($categoryName);
            $this->addReference('category_' . $categoryName, $category);
            
            $manager->persist($category);
        }

        $manager->flush();
    }
}