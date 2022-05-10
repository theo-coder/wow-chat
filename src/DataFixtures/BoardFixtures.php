<?php

namespace App\DataFixtures;

use App\Entity\Board;
use BaseFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BoardFixtures extends BaseFixture implements DependentFixtureInterface
{
    const BOARD_SUBJECT_REFERENCE = "board-subject";

    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(Board::class, 50, function (Board $board, $count) {
            $board->setName($this->faker->catchPhrase);
            $board->setCategory($this->getReference(CategoryFixtures::CATEGORY_BOARD_REFERENCE . "_" . $this->faker->numberBetween(0, 9)));

            $this->addReference(self::BOARD_SUBJECT_REFERENCE . '_' . $count, $board);
        });

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
