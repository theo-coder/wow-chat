<?php

namespace App\DataFixtures;

use App\Entity\Subject;
use BaseFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SubjectFixtures extends BaseFixture implements DependentFixtureInterface
{
    const SUBJECT_MESSAGE_REFERENCE = "subject-message";

    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(Subject::class, 100, function (Subject $subject, $count) {
            $subject->setTitle($this->faker->catchPhrase);
            $subject->setContent($this->faker->realText);
            $subject->setBoard($this->getReference(BoardFixtures::BOARD_SUBJECT_REFERENCE . "_" . $this->faker->numberBetween(0, 49)));
            $subject->setAuthor($this->getReference(UserFixtures::USER_SUBJECT_REFERENCE . "_" . $this->faker->numberBetween(0, 14)));

            $this->addReference(self::SUBJECT_MESSAGE_REFERENCE . '_' . $count, $subject);
        });

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            BoardFixtures::class,
            UserFixtures::class
        ];
    }
}
