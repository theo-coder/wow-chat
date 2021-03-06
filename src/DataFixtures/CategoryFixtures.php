<?php

namespace App\DataFixtures;

use App\Entity\Category;
use BaseFixture;
use Doctrine\Persistence\ObjectManager;

use Faker;

class CategoryFixtures extends BaseFixture
{
    const CATEGORY_BOARD_REFERENCE = "category-board";

    private static $categoryTitles = [
        "High Tech",
        "Animaux",
        "Nature",
        "Musique",
        "Histoire",
        "Sport",
        "Mode",
        "Cuisine",
        "Bricolage",
        "Voyage"
    ];

    private static $categoryAuthorizedRoles = [
        "ROLE_USER",
        "ROLE_INSIDER",
        "ROLE_COLLABORATOR",
        "ROLE_EXTERNAL"
    ];

    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(Category::class, 10, function (Category $category, $count) {
            $category->setName(self::$categoryTitles[$count]);
            $category->setAuthorizedRoles($this->faker->randomElements(self::$categoryAuthorizedRoles));

            $this->addReference(self::CATEGORY_BOARD_REFERENCE . "_" . $count, $category);
        });

        $manager->flush();
    }
}
