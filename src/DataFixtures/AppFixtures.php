<?php

namespace App\DataFixtures;

use App\Entity\Claim;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        //ADMIN
        $admin = new User();
        $admin->setEmail("admin@mail.ru");
        $admin->setRoles(['ROLE_ADMIN']);
        $password = $this->hasher->hashPassword($admin, '123456');
        $admin->setPassword($password);
        $manager->persist($admin);

        //MANAGER
        $user = new User();
        $user->setEmail("manager@mail.ru");
        $user->setRoles(['ROLE_MANAGER']);
        $password = $this->hasher->hashPassword($user, '123456');
        $user->setPassword($password);
        $manager->persist($user);

        $users = [];

        for($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setEmail("user" . $i ."@mail.ru");
            $password = $this->hasher->hashPassword($user, '123456');
            $user->setPassword($password);
            $manager->persist($user);

            $users[] = $user;
        }
        for($i = 0; $i < 5; $i++) {
            shuffle($users);
            foreach ($users as $item) {
                $claim = (new Claim($item))
                    ->setTitle("Claim Title" . $i)
                    ->setText("Claim Text" . $i)
                ;
                $manager->persist($claim);
            }
        }

        $manager->flush();
    }
}
