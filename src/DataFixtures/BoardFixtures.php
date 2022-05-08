<?php

namespace App\DataFixtures;

use App\Entity\Board;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BoardFixtures extends Fixture
{
    private array $boards = [
        1 => [
            "Informatique",
            "Hardware",
            "Gadgets"
        ],
        2 => [
            "Chien",
            "Domestique",
            "Sauvage"
        ],
        3 => [
            "Paysage",
            "Forêt",
            "Lieu"
        ],
        4 => [
            "Rock/Métal",
            "Pop",
            "Rap/Hip-hop",
            "Classique"
        ],
        5 => [
            "France",
            "Europe",
            "Espace"
        ]
    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->boards as $boardCategory => $boardContent) {
            $newBoard = new Board();
            $newBoard->setCategory($boardCategory);

            foreach ($boardContent as $board) {
                $newBoard->setName($board);

                $manager->persist($newBoard);
            }
        }

        $manager->flush();
    }
}
