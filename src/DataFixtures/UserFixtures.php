<?php

namespace App\DataFixtures;

use App\Entity\User;
use BaseFixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends BaseFixture
{
    private $hasher;

    const USER_SUBJECT_REFERENCE = "user-subject";

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->hasher = $userPasswordHasher;
    }

    private static $userEmails = [
        "theo@gmail.fr",
        "theo@admin.fr",
        "theo@insider.fr",
        "theo@collaborator.fr",
        "theo@external.fr",
        "theo@editor.fr"
    ];

    public function loadData(ObjectManager $manager): void
    {
        foreach (self::$userEmails as $id => $email) {
            $user = new User();
            $user->setEmail($email);

            $role = \str_ends_with($email, "@editor.fr") ? ["ROLE_EDITOR"] : (\str_ends_with($email, "@admin.fr") ? ["ROLE_ADMIN"] : (\str_ends_with($user->getEmail(), "@insider.fr") ? ["ROLE_INSIDER"] : (\str_ends_with($user->getEmail(), "@collaborator.fr") ? ["ROLE_COLLABORATOR"] : (\str_ends_with($user->getEmail(), "@external.fr") ? ["ROLE_EXTERNAL"] : []))));

            $user->setRoles($role);

            $user->setPassword(
                $this->hasher->hashPassword(
                    $user,
                    "azerty"
                )
            );

            $user->setPseudo(
                explode("@", $email)[0] . '_' . str_replace(".fr", "", explode("@", $email)[1])
            );

            $this->addReference(self::USER_SUBJECT_REFERENCE . '_' . $id, $user);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
