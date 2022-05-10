<?php

namespace App\DataFixtures;

use App\Entity\Message;
use BaseFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MessageFixtures extends BaseFixture implements DependentFixtureInterface
{

    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(Message::class, 500, function (Message $message, $count) {
            $message->setContent($this->faker->realText);
            $message->setSubject($this->getReference(SubjectFixtures::SUBJECT_MESSAGE_REFERENCE . "_" . $this->faker->numberBetween(0, 99)));
            $message->setAuthor($this->getReference(UserFixtures::USER_SUBJECT_REFERENCE . "_" . $this->faker->numberBetween(0, 14)));
        });

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SubjectFixtures::class,
            UserFixtures::class
        ];
    }
}
