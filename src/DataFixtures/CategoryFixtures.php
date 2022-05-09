<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    const CATEGORY_BOARD_REFERENCE = "category-board";

    private array $categories = [
        "High Tech",
        "Animaux",
        "Nature",
        "Musique",
        "Histoire"
    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->categories as $idx => $category) {
            $newCategory = new Category();
            $newCategory->setName($category);
            $newCategory->setAuthorizedRoles(["ROLE_USER"]);

            $manager->persist($newCategory);

            $this->addReference(self::CATEGORY_BOARD_REFERENCE . "_$idx", $newCategory);
        }

        $manager->flush();
    }
}
