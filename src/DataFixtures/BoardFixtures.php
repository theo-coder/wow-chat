<?php

namespace App\DataFixtures;

use App\Entity\Board;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BoardFixtures extends Fixture implements DependentFixtureInterface
{
    private array $boards = [
        [
            "Informatique",
            "Hardware",
            "Gadgets"
        ],
        [
            "Chien",
            "Domestique",
            "Sauvage"
        ],
        [
            "Paysage",
            "Forêt",
            "Lieu"
        ],
        [
            "Rock/Métal",
            "Pop",
            "Rap/Hip-hop",
            "Classique"
        ],
        [
            "France",
            "Europe",
            "Espace"
        ]
    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->boards as $categoryIdx => $boardCategory) {
            foreach ($boardCategory as $board) {
                $newBoard = new Board();
                $newBoard->setName($board);
                $newBoard->setCategory($this->getReference(CategoryFixtures::CATEGORY_BOARD_REFERENCE . "_$categoryIdx"));

                $manager->persist($newBoard);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
