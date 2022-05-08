<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    private array $categories = [
        "High Tech",
        "Animaux",
        "Nature",
        "Musique",
        "Histoire"
    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->categories as $category) {
            $newCategory = new Category();
            $newCategory->setName($category);

            $manager->persist($newCategory);
        }

        $manager->flush();
    }
}
